<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Cleans rich-text HTML coming from the admin editor so that stored content
 * can never contain scripts, event handlers or dangerous URLs.
 */
class HtmlSanitizer
{
    private ?HTMLPurifier $purifier = null;

    public function clean(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return null;
        }

        return $this->purifier()->purify($html);
    }

    private function purifier(): HTMLPurifier
    {
        if ($this->purifier !== null) {
            return $this->purifier;
        }

        $cachePath = storage_path('app/purifier');

        if (! is_dir($cachePath)) {
            @mkdir($cachePath, 0755, true);
        }

        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $config->set('Cache.SerializerPath', is_writable($cachePath) ? $cachePath : null);
        $config->set('HTML.Allowed', implode(',', [
            'p[style|class]', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'span[style|class]', 'sub', 'sup',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'ul', 'ol[start]', 'li[class]', 'blockquote', 'pre', 'code',
            'a[href|title|target|rel]', 'img[src|alt|title|width|height|class]',
            'table[class]', 'thead', 'tbody', 'tr', 'th[colspan|rowspan]', 'td[colspan|rowspan]',
            'hr', 'div[class|style]', 'iframe[src|width|height|frameborder]',
        ]));
        $config->set('CSS.AllowedProperties', ['text-align', 'color', 'background-color', 'font-weight', 'font-style', 'text-decoration', 'padding-left']);
        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/|www\.google\.com/maps/embed)%');
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        $config->set('Attr.AllowedRel', ['noopener', 'noreferrer', 'nofollow']);
        $config->set('AutoFormat.RemoveEmpty', false);
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true, 'tel' => true]);

        return $this->purifier = new HTMLPurifier($config);
    }
}
