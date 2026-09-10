<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaUploadRequest;
use App\Models\Media;
use App\Services\MediaUploader;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request): View
    {
        $media = Media::query()
            ->with('user')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q')->limit(100).'%'))
            ->when($request->input('type') === 'image', fn ($q) => $q->where('mime_type', 'like', 'image/%'))
            ->when($request->input('type') === 'document', fn ($q) => $q->where('mime_type', 'not like', 'image/%'))
            ->latest()
            ->paginate(36)
            ->withQueryString();

        return view('admin.media.index', ['media' => $media]);
    }

    /**
     * JSON listing consumed by the media picker modal.
     */
    public function picker(Request $request): JsonResponse
    {
        $media = Media::query()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q')->limit(100).'%'))
            ->latest()
            ->paginate(24);

        return response()->json([
            'data' => $media->getCollection()->map(fn (Media $item) => $this->serialize($item)),
            'current_page' => $media->currentPage(),
            'last_page' => $media->lastPage(),
        ]);
    }

    public function store(MediaUploadRequest $request, MediaUploader $uploader): JsonResponse|RedirectResponse
    {
        $uploaded = [];
        $errors = [];

        foreach ($request->file('files', []) as $file) {
            try {
                $uploaded[] = $this->serialize($uploader->upload($file, $request->user()->id, $request->input('collection', 'default')));
            } catch (\Throwable $e) {
                report($e);
                $errors[] = $file->getClientOriginalName().': '.$e->getMessage();
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['uploaded' => $uploaded, 'errors' => $errors], $errors && ! $uploaded ? 422 : 200);
        }

        $message = count($uploaded).' dosya yüklendi.';

        return $errors
            ? back()->with('error', $message.' Hatalar: '.implode(' ', $errors))
            : back()->with('success', $message);
    }

    public function update(Request $request, Media $medium): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'alt' => ['nullable', 'string', 'max:255'],
        ]);

        $medium->update($data);

        return back()->with('success', 'Medya bilgileri güncellendi.');
    }

    public function destroy(Media $medium): RedirectResponse
    {
        $medium->deleteFiles();
        $medium->delete();

        return back()->with('success', 'Dosya silindi.');
    }

    /**
     * @return array<string, mixed>
     */
    private function serialize(Media $media): array
    {
        return [
            'id' => $media->id,
            'name' => $media->name,
            'path' => $media->path,
            'url' => $media->url(),
            'thumb_url' => $media->thumbUrl(),
            'is_image' => $media->isImage(),
            'mime_type' => $media->mime_type,
            'size' => $media->humanSize(),
        ];
    }
}
