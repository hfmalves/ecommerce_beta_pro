<?php

namespace App\Services;

/** Validates editor input without copying identifiers or timestamps into slide content. */
class HeroSettings
{
    public function normalize(array $input, array $existing = []): array
    {
        $slides = $input['slides'] ?? null;
        if (! is_array($slides) || ! array_is_list($slides) || count($slides) > 20) {
            throw new \InvalidArgumentException('Pode configurar até 20 slides.');
        }
        $delay = filter_var($input['delay'] ?? 5000, FILTER_VALIDATE_INT);
        if ($delay === false || $delay < 1000 || $delay > 30000) {
            throw new \InvalidArgumentException('O intervalo deve estar entre 1 e 30 segundos.');
        }
        $normalized = [];
        foreach ($slides as $index => $slide) {
            if (! is_array($slide)) { throw new \InvalidArgumentException('Slide inválido.'); }
            $item = [];
            foreach (['title' => 255, 'description' => 2000, 'button_text' => 100, 'button_link' => 2048, 'background_image' => 500] as $field => $limit) {
                $value = $slide[$field] ?? '';
                if (! is_string($value) || mb_strlen($value) > $limit) {
                    throw new \InvalidArgumentException('Verifique o campo ' . $field . ' do slide ' . ($index + 1) . '.');
                }
                $item[$field] = trim($value);
            }
            if ($item['button_link'] !== '' && ! preg_match('~^(?:/(?!/)|https?://[^\s]+$)~i', $item['button_link'])) {
                throw new \InvalidArgumentException('O destino do botão deve ser um caminho /... ou um endereço HTTP/HTTPS.');
            }
            $image = $item['background_image'];
            if ($image !== '' && (str_contains($image, '..') || str_contains($image, ':') || str_starts_with($image, '/')
                || str_contains($image, '\\') || ! in_array(strtolower(pathinfo($image, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'], true))) {
                throw new \InvalidArgumentException('Caminho da imagem inválido.');
            }
            $item['position'] = $index + 1;
            $normalized[] = $item;
        }
        $existing['config'] = is_array($existing['config'] ?? null) ? $existing['config'] : [];
        $existing['config']['autoplay'] = filter_var($input['autoplay'] ?? true, FILTER_VALIDATE_BOOLEAN);
        $existing['config']['autoplay_delay'] = $delay;
        $existing['slides'] = $normalized;
        return $existing;
    }
}
