<?php

namespace App\Services;

class WebsiteBlockSettingsImporter
{
    public function __construct(private \CodeIgniter\Database\BaseConnection $db)
    {
    }

    public function import(): void
    {
        $configs = [
            'hero' => 'website_block_hero', 'grid_banner' => 'website_block_grid_banner',
            'products_grid' => 'website_block_products_grid', 'blog_grid' => 'website_block_blog_grid',
            'home_deals_day' => 'website_block_home_deals_day',
            'service_promotion' => 'website_block_service_promotion',
            'top_category_filter' => 'website_block_top_category_filter', 'footer' => 'website_block_footer',
        ];
        foreach ($this->db->table('website_home_blocks')->get()->getResultArray() as $block) {
            // Never overwrite a JSON configuration edited after the migration.
            if ($block['settings'] !== null) {
                continue;
            }
            $type = $block['block_type'];
            $configTable = $configs[$type] ?? null;
            $settings = ['config' => [], 'items' => []];
            if ($configTable) {
                $settings['config'] = $this->rows($configTable, ['block_id' => $block['id']])[0] ?? [];
                $settings['items'] = $this->rows($configTable . '_items', ['block_id' => $block['id']]);
            }
            if ($type === 'hero') {
                $settings['slides'] = $this->rows('website_block_hero_slides', ['block_id' => $block['id']]);
            }
            if ($type === 'grid_banner') {
                foreach ($settings['items'] as &$item) {
                    $item['targets'] = $this->rows('website_block_grid_banner_item_targets', ['banner_item_id' => $item['id']]);
                }
                unset($item);
            }
            if ($type === 'top_category_filter') {
                $settings['tabs'] = $this->rows('website_block_top_category_filter_tabs', ['block_id' => $block['id']]);
                foreach ($settings['tabs'] as &$tab) {
                    $tab['items'] = $this->rows('website_block_top_category_filter_tab_items', ['tab_id' => $tab['id']]);
                }
                unset($tab);
            }
            if ($type === 'footer') {
                $settings['categories'] = $this->rows('website_block_footer_menu_categories', ['block_id' => $block['id']]);
                foreach ($settings['categories'] as &$category) {
                    $category['items'] = $this->rows('website_block_footer_menu_items', ['category_id' => $category['id']]);
                }
                unset($category);
            }
            if ($type === 'faq') {
                // Keep reusable FAQ content in its own tables.
                $faqs = $this->rows('website_faqs', ['context_type' => 'home', 'context_id' => $block['home_id']]);
                $settings['faq_id'] = $faqs[0]['id'] ?? null;
            }
            $this->db->table('website_home_blocks')->where('id', $block['id'])->update([
                'settings' => json_encode($settings, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
                'settings_version' => 1,
            ]);
        }
    }

    private function rows(string $table, array $where): array
    {
        $objects = $this->objects();
        if (! isset($objects[$this->db->prefixTable($table)])) {
            return [];
        }
        // A missing relationship column is a schema mismatch, never silent data loss.
        return $this->db->table($table)->where($where)->get()->getResultArray();
    }

    private function objects(): array
    {
        $objects = [];
        foreach ($this->db->query('SHOW FULL TABLES')->getResultArray() as $row) {
            $values = array_values($row);
            $objects[$values[0]] = $values[1];
        }
        return $objects;
    }

}
