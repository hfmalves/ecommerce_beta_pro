<?php

namespace App\Services;

use App\Models\Admin\Catalog\ProductsModel;
use App\Models\Admin\Catalog\ProductsImagesModel;
use App\Models\Admin\Catalog\ProductsVariantsModel;

/** Adapts saved JSON to storefront templates; never reads legacy presentation tables. */
class ShowcasePreview
{
    public function render(array $block): string
    {
        $type = $block['block_type'];
        if (! array_key_exists($type, config('ShowcaseBlocks')->types)) {
            throw new \InvalidArgumentException('Unsupported preview block');
        }
        $settings = json_decode($block['settings'] ?? '{}', true);
        $settings = is_array($settings) ? $settings : [];
        $config = is_array($settings['config'] ?? null) ? $settings['config'] : [];
        $items = $this->ordered($settings['items'] ?? []);
        $block['blockConfig'] = $config;
        $block['config'] = $config;
        $block['items'] = $items;
        $block['data'] = ['items' => $items];

        if ($type === 'hero') {
            $block = (new HeroPreview())->prepare($block);
        } elseif ($type === 'products_grid' || $type === 'home_deals_day') {
            $limit = max(1, min(24, (int) ($config['items_limit'] ?? 8)));
            $block['products'] = [];
            if ($type === 'products_grid' && ($config['grid_type'] ?? '') !== 'manual') {
                $items = (new ProductsModel())->where('status', 'active')->orderBy('id')->findAll($limit);
                $items = array_map(static fn ($row) => ['product_id' => $row['id']], $items);
            }
            foreach (array_slice($items, 0, $limit) as $item) {
                $product = $this->product((int) ($item['product_id'] ?? 0), (int) ($item['product_variant_id'] ?? 0));
                if ($product) { $block['products'][] = $product; }
            }
        } elseif ($type === 'top_category_filter') {
            $block['tabs'] = $this->ordered($settings['tabs'] ?? []);
            foreach ($block['tabs'] as &$tab) {
                $tab['items'] = $this->ordered($tab['items'] ?? []);
                foreach ($tab['items'] as &$item) {
                    $item['product'] = $this->product((int) ($item['product_id'] ?? 0), (int) ($item['product_variant_id'] ?? 0));
                }
                unset($item);
            }
            unset($tab);
        } elseif ($type === 'faq') {
            $id = (int) ($settings['faq_id'] ?? 0);
            $block['faq'] = $id ? (new \App\Models\Website\FaqModel())->find($id) : null;
            $block['items'] = $id ? (new \App\Models\Website\FaqItemModel())->where('faq_id', $id)->orderBy('position')->findAll() : [];
        } elseif ($type === 'footer') {
            $block['footer'] = ['config' => $config, 'categories' => $this->ordered($settings['categories'] ?? [])];
        }

        // A newly added block stays visibly empty, rather than inventing saved content.
        $empty = match ($type) {
            'grid_banner', 'service_promotion', 'blog_grid' => ! $items,
            'products_grid', 'home_deals_day' => ! $block['products'],
            'top_category_filter' => ! $block['tabs'],
            'faq' => ! $block['faq'] || ! $block['items'],
            default => false,
        };
        if ($empty) {
            return view('admin/website/showcases/preview_empty', ['blockName' => config('ShowcaseBlocks')->types[$type]['name']]);
        }
        return $this->localImages(view('layout/partials_website/blocks/' . $type, ['block' => $block]));
    }

    private function ordered($items): array
    {
        $items = is_array($items) ? array_values(array_filter($items, 'is_array')) : [];
        $items = array_values(array_filter($items, static fn ($item) => ! isset($item['is_active']) || (bool) $item['is_active']));
        usort($items, static fn ($a, $b) => (int) ($a['position'] ?? $a['sort_order'] ?? 0) <=> (int) ($b['position'] ?? $b['sort_order'] ?? 0));
        return $items;
    }

    private function product(int $id, int $variantId): ?array
    {
        $variant = $variantId ? (new ProductsVariantsModel())->find($variantId) : null;
        if (! $id && $variant) { $id = (int) $variant['product_id']; }
        $product = $id ? (new ProductsModel())->find($id) : null;
        if (! $product || ($variant && (int) $variant['product_id'] !== $id)) { return null; }
        $product['images'] = (new ProductsImagesModel())->where('owner_type', 'product')->where('owner_id', $id)->orderBy('position')->findAll();
        if (! $product['images']) { $product['images'] = [['path' => '']]; }
        if ($variant) { $product['variation'] = $variant; }
        $product['in_wishlist'] = false;
        return $product;
    }

    private function localImages(string $html): string
    {
        $document = new \DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        try {
            $document->loadHTML('<?xml encoding="UTF-8"><html><body>' . $html . '</body></html>', LIBXML_NONET);
            foreach ($document->getElementsByTagName('*') as $element) {
                foreach (iterator_to_array($element->attributes) as $attribute) {
                    if (str_starts_with(strtolower($attribute->name), 'on')) { $element->removeAttribute($attribute->name); }
                }
            }
            foreach ($document->getElementsByTagName('img') as $image) {
                $source = $image->getAttribute('src');
                $host = parse_url($source, PHP_URL_HOST);
                $path = rawurldecode(parse_url($source, PHP_URL_PATH) ?? '');
                $relative = ltrim($path, '/');
                $file = realpath(FCPATH . $relative);
                $root = realpath(FCPATH) . DIRECTORY_SEPARATOR;
                $valid = (! $host || $host === parse_url(base_url(), PHP_URL_HOST))
                    && $file && str_starts_with(strtolower($file), strtolower($root)) && is_file($file)
                    && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif', 'svg'], true);
                if (! $valid) {
                    // Product portraits and landscape banners get suitable proportions.
                    $portrait = (int) $image->getAttribute('height') > (int) $image->getAttribute('width');
                    $image->setAttribute('src', base_url('assets/images/' . ($portrait ? 'product-placeholder.svg' : 'slide-placeholder.svg')));
                }
                $image->removeAttribute('srcset');
                $image->setAttribute('loading', 'eager');
            }
            $body = $document->getElementsByTagName('body')->item(0);
            $result = '';
            foreach ($body->childNodes as $node) { $result .= $document->saveHTML($node); }
            return $result;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
    }
}
