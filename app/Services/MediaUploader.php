<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * Validates, re-encodes and stores uploaded files in the public uploads disk.
 * Images are re-encoded (stripping metadata / embedded payloads), resized to a
 * maximum width and given a thumbnail. File names are always regenerated.
 */
class MediaUploader
{
    private const IMAGE_MIMES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    private const DOCUMENT_MIMES = ['application/pdf'];

    public function upload(UploadedFile $file, ?int $userId = null, string $collection = 'default'): Media
    {
        $mime = $file->getMimeType();

        if (! in_array($mime, [...self::IMAGE_MIMES, ...self::DOCUMENT_MIMES], true)) {
            throw new \InvalidArgumentException('Desteklenmeyen dosya türü.');
        }

        $disk = Storage::disk(config('cms.uploads.disk'));
        $directory = 'media/'.date('Y/m');
        $extension = $this->extensionFor($mime);
        $baseName = Str::limit(Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '-', 'tr'), 60, '') ?: 'dosya';
        $fileName = $baseName.'-'.Str::lower(Str::random(8)).'.'.$extension;
        $path = $directory.'/'.$fileName;

        $width = $height = null;
        $thumbPath = null;

        if (in_array($mime, self::IMAGE_MIMES, true)) {
            $manager = new ImageManager(new Driver);
            $image = $manager->read($file->getRealPath());

            $maxWidth = (int) config('cms.uploads.max_width', 1920);

            if ($image->width() > $maxWidth) {
                $image->scaleDown(width: $maxWidth);
            }

            $encoded = $image->encodeByExtension($extension, quality: 85);
            $disk->put($path, (string) $encoded);

            $width = $image->width();
            $height = $image->height();

            $thumb = $manager->read($file->getRealPath())->cover((int) config('cms.uploads.thumb_width', 400), (int) config('cms.uploads.thumb_width', 400));
            $thumbPath = $directory.'/thumbs/'.$fileName;
            $disk->put($thumbPath, (string) $thumb->encodeByExtension($extension, quality: 80));
        } else {
            $disk->putFileAs($directory, $file, $fileName);
        }

        return Media::query()->create([
            'user_id' => $userId,
            'name' => $baseName,
            'file_name' => $fileName,
            'disk' => config('cms.uploads.disk'),
            'path' => $path,
            'thumb_path' => $thumbPath,
            'mime_type' => $mime,
            'size' => $disk->size($path),
            'width' => $width,
            'height' => $height,
            'collection' => $collection,
        ]);
    }

    private function extensionFor(string $mime): string
    {
        return match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf',
            default => 'bin',
        };
    }
}
