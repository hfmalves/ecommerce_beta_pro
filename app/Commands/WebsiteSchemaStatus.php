<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class WebsiteSchemaStatus extends BaseCommand
{
    protected $group = 'Website';
    protected $name = 'website:schema-status';
    protected $description = 'Check archived presentation tables and generic JSON settings (read-only).';

    public function run(array $params)
    {
        $db = db_connect();
        $archives = 0;
        $views = 0;
        $objects = [];
        foreach ($db->query('SHOW FULL TABLES')->getResultArray() as $row) {
            $values = array_values($row);
            $objects[$values[0]] = $values[1];
        }
        foreach ($objects as $table => $type) {
            $name = substr($table, strlen($db->DBPrefix));
            if (! str_starts_with($name, 'legacy_website_') || $type !== 'BASE TABLE') {
                continue;
            }
            $archives++;
            $alias = substr($name, strlen('legacy_'));
            if (($objects[$db->prefixTable($alias)] ?? null) !== 'VIEW') {
                throw new \RuntimeException('Missing compatibility view: ' . $alias);
            }
            if ($db->table($alias)->countAllResults() !== $db->table($table)->countAllResults()) {
                throw new \RuntimeException('Archive row count mismatch: ' . $alias);
            }
            $views++;
        }
        $blocks = $db->table('website_home_blocks')->get()->getResultArray();
        foreach ($blocks as $block) {
            if ($block['settings'] === null) {
                throw new \RuntimeException('Missing JSON for block ' . $block['id']);
            }
            json_decode($block['settings'], true, 512, JSON_THROW_ON_ERROR);
        }
        CLI::write('Archived tables: ' . $archives . '; compatible views: ' . $views);
        CLI::write('Vitrines: ' . $db->table('website_homes')->countAllResults());
        CLI::write('Blocks with valid JSON: ' . count($blocks));
        CLI::write('Themes: ' . implode(', ', array_column($db->table('website_themes')->get()->getResultArray(), 'code')));
        CLI::write('Theme/store configurations: ' . $db->table('website_theme_settings')->countAllResults());
        CLI::write('Compatibility phase: old editors still write legacy data; JSON is a snapshot.');
    }
}
