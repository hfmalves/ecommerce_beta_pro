<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Non-destructive transition. Legacy editors keep working through writable views.
 * JSON is a migration snapshot until the editors/renderers adopt the new schema.
 */
class ArchiveWebsitePresentationTables extends Migration
{
    public const TABLES = [
        'website_block_blog_grid', 'website_block_blog_grid_items',
        'website_block_footer', 'website_block_footer_menu_categories', 'website_block_footer_menu_items',
        'website_block_grid_banner', 'website_block_grid_banner_items', 'website_block_grid_banner_item_targets',
        'website_block_hero', 'website_block_hero_slides',
        'website_block_home_deals_day', 'website_block_home_deals_day_items',
        'website_block_newsletter', 'website_block_products_grid', 'website_block_products_grid_items',
        'website_block_service_promotion', 'website_block_service_promotion_items',
        'website_block_top_category_filter', 'website_block_top_category_filter_tabs',
        'website_block_top_category_filter_tab_items',
        'website_module_banner_products_positions', 'website_module_categories', 'website_module_home',
        'website_module_icons', 'website_module_product_loop_categories', 'website_module_product_loop_link',
        'website_module_product_loop_products', 'website_module_testimonial', 'website_sliders',
    ];

    public function up()
    {
        (new \App\Services\WebsiteBlockSettingsImporter($this->db))->import();
        $objects = $this->objects();
        $renames = [];
        foreach (self::TABLES as $name) {
            $source = $this->db->prefixTable($name);
            $target = $this->db->prefixTable('legacy_' . $name);
            if (($objects[$source] ?? null) === 'BASE TABLE') {
                if (isset($objects[$target])) {
                    throw new \RuntimeException('Archive target already exists: ' . $target);
                }
                $renames[] = $this->quote($source) . ' TO ' . $this->quote($target);
            }
        }
        // Rename related tables together; MySQL updates their foreign key references.
        if ($renames) {
            $this->db->query('RENAME TABLE ' . implode(', ', $renames));
        }
        $objects = $this->objects();
        foreach (self::TABLES as $name) {
            $source = $this->db->prefixTable($name);
            $target = $this->db->prefixTable('legacy_' . $name);
            if (($objects[$target] ?? null) === 'BASE TABLE' && ! isset($objects[$source])) {
                $this->db->query('CREATE ALGORITHM=MERGE SQL SECURITY INVOKER VIEW '
                    . $this->quote($source) . ' AS SELECT * FROM ' . $this->quote($target));
            }
        }
    }

    public function down()
    {
        $objects = $this->objects();
        $renames = [];
        // Preflight before dropping any compatibility view.
        foreach (self::TABLES as $name) {
            $source = $this->db->prefixTable($name);
            $target = $this->db->prefixTable('legacy_' . $name);
            if (($objects[$target] ?? null) !== 'BASE TABLE') {
                continue;
            }
            if (isset($objects[$source]) && $objects[$source] !== 'VIEW') {
                throw new \RuntimeException('Cannot restore over existing table: ' . $source);
            }
            $renames[] = $this->quote($target) . ' TO ' . $this->quote($source);
        }
        foreach (self::TABLES as $name) {
            $source = $this->db->prefixTable($name);
            if (($objects[$source] ?? null) === 'VIEW'
                && ($objects[$this->db->prefixTable('legacy_' . $name)] ?? null) === 'BASE TABLE') {
                $this->db->query('DROP VIEW ' . $this->quote($source));
            }
        }
        if ($renames) {
            $this->db->query('RENAME TABLE ' . implode(', ', $renames));
        }
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

    private function quote(string $name): string
    {
        return $this->db->escapeIdentifiers($name);
    }
}
