<?php

namespace App\Commands;

use App\Database\Migrations\ArchiveWebsitePresentationTables;
use App\Database\Migrations\CreateWebsiteThemeStructure;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

/** Exercises real MySQL DDL against an isolated, disposable copy. */
class TestWebsiteTransition extends BaseCommand
{
    protected $group = 'Website';
    protected $name = 'website:test-transition';
    protected $description = 'Test theme migration up/down/up on a temporary database copy.';

    public function run(array $params)
    {
        require_once APPPATH . 'Database/Migrations/2026-10-14-220000_CreateWebsiteThemeStructure.php';
        require_once APPPATH . 'Database/Migrations/2026-10-14-220100_ArchiveWebsitePresentationTables.php';
        $source = db_connect();
        $temporaryName = 'codex_theme_check_' . bin2hex(random_bytes(6));
        $quoted = $source->escapeIdentifiers($temporaryName);
        $source->query('CREATE DATABASE ' . $quoted . ' CHARACTER SET utf8mb4');
        $copy = null;
        try {
            $configuration = config('Database')->{config('Database')->defaultGroup};
            $configuration['database'] = $temporaryName;
            $copy = Database::connect($configuration, false);
            $copy->disableForeignKeyChecks();
            $counts = [];
            foreach ($source->query('SHOW FULL TABLES')->getResultArray() as $row) {
                $values = array_values($row);
                if ($values[1] !== 'BASE TABLE') {
                    continue;
                }
                $table = $values[0];
                $ddl = array_values($source->query('SHOW CREATE TABLE ' . $source->escapeIdentifiers($table))->getRowArray())[1];
                $copy->query($ddl);
                $records = $source->table($table)->get()->getResultArray();
                $counts[$table] = count($records);
                if ($records) {
                    $copy->table($table)->insertBatch($records, null, 100);
                }
            }
            $copy->enableForeignKeyChecks();
            CLI::write('Isolated database cloned; source data unchanged.');
            $forge = Database::forge($copy);
            $schema = new CreateWebsiteThemeStructure($forge);
            $archive = new ArchiveWebsitePresentationTables($forge);
            // Also allow this command after the source has already been migrated.
            // Normalize only the disposable clone back to the old schema first.
            if ($copy->tableExists('website_themes')) {
                $archive->down();
                $schema->down();
                $copy->resetDataCache();
                $counts = [];
                foreach ($copy->query('SHOW FULL TABLES')->getResultArray() as $row) {
                    $values = array_values($row);
                    if ($values[1] === 'BASE TABLE') {
                        $counts[$values[0]] = $copy->table($values[0])->countAllResults();
                    }
                }
            }
            $schema->up();
            $archive->up();
            $this->verify($copy, $counts);
            CLI::write('UP passed: JSON, themes, archives, compatibility reads and counts.');
            $archive->down();
            $schema->down();
            foreach ($counts as $table => $count) {
                if ($copy->table($table)->countAllResults() !== $count) {
                    throw new \RuntimeException('Rollback count mismatch: ' . $table);
                }
            }
            CLI::write('DOWN passed: original tables and row counts restored.');
            $schema->up();
            $archive->up();
            $this->verify($copy, $counts);
            CLI::write('Second UP passed.');

            // Exercise legacy presentation seeders on the isolated connection too.
            $config = config('Database');
            $originalGroup = $config->defaultGroup;
            $originalTests = $config->tests;
            $config->tests = $configuration;
            $config->defaultGroup = 'tests';
            try {
                $seedDb = db_connect();
                if ($seedDb->getDatabase() !== $temporaryName) {
                    throw new \RuntimeException('Refusing to seed a non-test database.');
                }
                $seedDb->disableForeignKeyChecks();
                try {
                    foreach ($seedDb->query('SHOW FULL TABLES')->getResultArray() as $row) {
                        $values = array_values($row);
                        $name = substr($values[0], strlen($seedDb->DBPrefix));
                        if ($values[1] === 'BASE TABLE'
                            && (str_starts_with($name, 'website_') || str_starts_with($name, 'legacy_website_'))) {
                            $seedDb->table($values[0])->truncate();
                        }
                    }
                } finally {
                    $seedDb->enableForeignKeyChecks();
                }
                foreach ([
                    'WebsitePagesSeeder', 'WebsiteMenuSeeder', 'HomeModulesSeeder', 'WebsiteSliderSeeder',
                    'WebsiteModuleIconsSeeder', 'WebsiteModuleCategoriesSeeder', 'WebsiteModuleTestimonialSeeder',
                    'WebsiteBannerProductsSeeder', 'ModuleProductLoopFullSeeder', 'WebsiteHomesSeeder', 'WebsiteThemeSeeder',
                ] as $class) {
                    $class = 'App\\Database\\Seeds\\' . $class;
                    (new $class($config))->run();
                }
                foreach (db_connect()->table('website_home_blocks')->get()->getResultArray() as $block) {
                    json_decode($block['settings'], true, 512, JSON_THROW_ON_ERROR);
                }
                CLI::write('Website seeders passed on the isolated database.');
            } finally {
                Database::connect('tests')->close();
                $config->defaultGroup = $originalGroup;
                $config->tests = $originalTests;
            }
        } finally {
            if ($copy) {
                $copy->close();
            }
            // Name is generated here, never supplied by a caller.
            $source->query('DROP DATABASE ' . $quoted);
        }
    }

    private function verify($db, array $counts): void
    {
        foreach (ArchiveWebsitePresentationTables::TABLES as $table) {
            $physical = $db->prefixTable($table);
            if (! array_key_exists($physical, $counts)) {
                continue;
            }
            $expected = $counts[$physical];
            if ($db->table($table)->countAllResults() !== $expected
                || $db->table('legacy_' . $table)->countAllResults() !== $expected) {
                throw new \RuntimeException('Archive count mismatch: ' . $table);
            }
        }
        if ($db->table('website_themes')->countAllResults() !== 2) {
            throw new \RuntimeException('Expected Base and Starter themes.');
        }
        foreach ($db->table('website_home_blocks')->get()->getResultArray() as $block) {
            json_decode($block['settings'], true, 512, JSON_THROW_ON_ERROR);
        }
    }
}
