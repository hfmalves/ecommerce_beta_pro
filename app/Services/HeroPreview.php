<?php

namespace App\Services;

/** Prepare only saved JSON and local image paths for the isolated slide preview. */
class HeroPreview
{
    public function prepare(array $block): array
    {
        $settings = json_decode($block['settings'] ?? '{}', true);
        $source = is_array($settings) && is_array($settings['slides'] ?? null) ? $settings['slides'] : [];
        if (! $source) {
            $source = [['title' => 'O seu banner', 'description' => 'A pré-visualização do slide aparece aqui.', 'background_image' => '']];
        }
        $slides = [];
        foreach ($source as $slide) {
            if (! is_array($slide)) { continue; }
            $image = is_string($slide['background_image'] ?? null) ? str_replace('\\', '/', $slide['background_image']) : '';
            $relative = str_starts_with($image, 'uploads/') ? $image : 'uploads/hero/' . ltrim($image, '/');
            $allowed = $image !== '' && ! str_contains($image, '..') && ! str_contains($image, ':')
                && in_array(strtolower(pathinfo($image, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'], true)
                && is_file(FCPATH . $relative);
            $link = is_string($slide['button_link'] ?? null) ? $slide['button_link'] : '';
            if (! preg_match('~^(?:/(?!/)|https?://)~i', $link)) { $link = ''; }
            $slides[] = [
                'title' => is_string($slide['title'] ?? null) ? $slide['title'] : '',
                'description' => is_string($slide['description'] ?? null) ? $slide['description'] : '',
                'button_text' => is_string($slide['button_text'] ?? null) ? $slide['button_text'] : '',
                'button_link' => $link,
                'background_image' => $image,
                'preview_image_url' => base_url($allowed ? $relative : 'assets/images/slide-placeholder.svg'),
                'position' => (int) ($slide['position'] ?? 0),
            ];
        }
        usort($slides, static fn ($a, $b) => $a['position'] <=> $b['position']);
        $block['data'] = ['slides' => $slides];
        return $block;
    }
}
