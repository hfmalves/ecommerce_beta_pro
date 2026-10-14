<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WebsiteThemeSeeder extends Seeder
{
    public function run()
    {
        if (! $this->db->tableExists('website_themes')) {
            return;
        }
        foreach (['base' => 'Base', 'starter' => 'Starter'] as $code => $name) {
            $theme = $this->db->table('website_themes')->where('code', $code)->get()->getRowArray();
            if (! $theme) {
                $this->db->table('website_themes')->insert([
                    'code' => $code, 'name' => $name, 'version' => '1.0.0', 'created_at' => date('Y-m-d H:i:s'),
                ]);
                $theme = ['id' => $this->db->insertID()];
            }
            foreach ($this->db->table('stores')->select('id')->get()->getResultArray() as $store) {
                $key = ['store_id' => $store['id'], 'theme_id' => $theme['id']];
                if (! $this->db->table('website_theme_settings')->where($key)->countAllResults()) {
                    $this->db->table('website_theme_settings')->insert($key + [
                        'is_active' => $code === 'base', 'settings' => '{}', 'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
        (new \App\Services\WebsiteBlockSettingsImporter($this->db))->import();
    }
}
