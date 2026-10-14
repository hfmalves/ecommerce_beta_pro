<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class BackupWebsiteSchema extends BaseCommand
{
    protected $group = 'Website';
    protected $name = 'website:backup';
    protected $description = 'Back up website tables and migration history before a schema transition.';

    public function run(array $params)
    {
        $db = db_connect();
        $directory = WRITEPATH . 'backups/';
        if (! is_dir($directory) && ! mkdir($directory, 0700, true)) {
            throw new \RuntimeException('Cannot create backup directory.');
        }
        $path = $directory . 'website-' . date('Ymd-His') . '.sql';
        $handle = fopen($path, 'xb');
        if ($handle === false) {
            throw new \RuntimeException('Cannot create backup file.');
        }
        $write = static function (string $sql) use ($handle): void {
            if (fwrite($handle, $sql) !== strlen($sql)) {
                throw new \RuntimeException('Incomplete backup write.');
            }
        };
        $count = 0;
        try {
            $write("-- Website schema transition backup\nSET FOREIGN_KEY_CHECKS=0;\n");
            $tables = $db->query('SHOW FULL TABLES')->getResultArray();
            foreach ($tables as $row) {
                $values = array_values($row);
                $table = $values[0];
                $name = substr($table, strlen($db->DBPrefix));
                if ($values[1] !== 'BASE TABLE' || (! str_starts_with($name, 'website_')
                    && ! str_starts_with($name, 'legacy_website_') && $name !== config('Migrations')->table)) {
                    continue;
                }
                $quoted = $db->escapeIdentifiers($table);
                $ddl = array_values($db->query('SHOW CREATE TABLE ' . $quoted)->getRowArray())[1];
                $write("DROP TABLE IF EXISTS {$quoted};\n{$ddl};\n");
                foreach ($db->table($table)->get()->getResultArray() as $record) {
                    $columns = implode(', ', array_map([$db, 'escapeIdentifiers'], array_keys($record)));
                    $values = implode(', ', array_map([$db, 'escape'], array_values($record)));
                    $write("INSERT INTO {$quoted} ({$columns}) VALUES ({$values});\n");
                }
                $count++;
            }
            $write("SET FOREIGN_KEY_CHECKS=1;\n");
        } finally {
            fclose($handle);
        }
        CLI::write('Backup: ' . $path);
        CLI::write('Tables: ' . $count . '; SHA-256: ' . hash_file('sha256', $path));
    }
}
