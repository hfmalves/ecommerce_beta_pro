<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/** Read-only rendering check; does not seed or modify the database. */
class CheckShowcasePreviews extends BaseCommand
{
    protected $group = 'Website';
    protected $name = 'website:check-previews';
    protected $description = 'Render each supported showcase block and verify local image fallbacks.';

    public function run(array $params)
    {
        $renderer = new \App\Services\ShowcasePreview();
        $blocks = db_connect()->table('website_home_blocks')->get()->getResultArray();
        $types = config('ShowcaseBlocks')->types;
        $checked = [];
        foreach ($blocks as $block) {
            $type = $block['block_type'];
            if (! isset($types[$type])) { continue; }
            $html = $renderer->render($block);
            $this->checkHtml($html, $type);
            $checked[$type] = true;
        }
        foreach ($types as $type => $definition) {
            $html = $renderer->render(['id' => 9999, 'home_id' => 1, 'block_type' => $type, 'settings' => '{}']);
            $this->checkHtml($html, $type . ' (empty)');
            $checked[$type] = true;
        }
        CLI::write('Preview templates checked: ' . implode(', ', array_keys($checked)));
        CLI::write('Saved JSON and empty configurations rendered; no data changed.');
    }

    private function checkHtml(string $html, string $type): void
    {
        if (trim($html) === '' || str_contains($html, 'onerror=') || str_contains($html, 'https://placehold.co')) {
            throw new \RuntimeException('Invalid preview: ' . $type);
        }
        $full = view('admin/website/showcases/block_preview_frame', ['previewHtml' => $html, 'previewType' => explode(' ', $type)[0]]);
        if (! str_contains($full, 'showcase-preview-content')) {
            throw new \RuntimeException('Preview frame failed: ' . $type);
        }
    }
}
