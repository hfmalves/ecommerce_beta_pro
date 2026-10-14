<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWebsiteThemeStructure extends Migration
{
    public function up()
    {
        $this->db->resetDataCache();
        if (! $this->db->tableExists('website_themes')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'code' => ['type' => 'VARCHAR', 'constraint' => 80],
                'name' => ['type' => 'VARCHAR', 'constraint' => 150],
                'version' => ['type' => 'VARCHAR', 'constraint' => 30],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('code');
            $this->forge->createTable('website_themes');
        }

        if (! $this->db->tableExists('website_theme_settings')) {
            $this->forge->addField([
                'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'store_id' => ['type' => 'INT', 'unsigned' => true],
                'theme_id' => ['type' => 'INT', 'unsigned' => true],
                'is_active' => ['type' => 'BOOLEAN', 'default' => false],
                'settings' => ['type' => 'JSON', 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey(['store_id', 'theme_id']);
            $this->forge->addKey(['store_id', 'is_active']);
            $this->forge->addForeignKey('store_id', 'stores', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('theme_id', 'website_themes', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('website_theme_settings');
        }

        if (! $this->db->fieldExists('settings', 'website_home_blocks')) {
            $this->forge->addColumn('website_home_blocks', [
                'settings' => ['type' => 'JSON', 'null' => true],
                'settings_version' => ['type' => 'SMALLINT', 'unsigned' => true, 'default' => 1],
            ]);
        }

        foreach (['base' => 'Base', 'starter' => 'Starter'] as $code => $name) {
            if (! $this->db->table('website_themes')->where('code', $code)->countAllResults()) {
                $this->db->table('website_themes')->insert([
                    'code' => $code, 'name' => $name, 'version' => '1.0.0',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        $themes = $this->db->table('website_themes')->get()->getResultArray();
        foreach ($this->db->table('stores')->select('id')->get()->getResultArray() as $store) {
            foreach ($themes as $theme) {
                $key = ['store_id' => $store['id'], 'theme_id' => $theme['id']];
                if (! $this->db->table('website_theme_settings')->where($key)->countAllResults()) {
                    $this->db->table('website_theme_settings')->insert($key + [
                        'is_active' => $theme['code'] === 'base', 'settings' => '{}',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
    }

    public function down()
    {
        $this->db->resetDataCache();
        $this->forge->dropTable('website_theme_settings', true);
        $this->forge->dropTable('website_themes', true);
        if ($this->db->fieldExists('settings', 'website_home_blocks')) {
            $this->forge->dropColumn('website_home_blocks', ['settings', 'settings_version']);
        }
    }
}
