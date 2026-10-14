<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class HomeModulesSeeder extends Seeder
{
    public function run()
    {
        helper('website_schema');
        $data = [
            [
                'type'       => 'slider_01',
                'sort_order' => 1,
                'is_active'  => 1,
                'settings'   => json_encode([]),
            ],
            [
                'type'       => 'box_icons',
                'sort_order' => 2,
                'is_active'  => 1,
                'settings'   => json_encode([]),
            ],
            [
                'type'       => 'category_loop_01',
                'sort_order' => 3,
                'is_active'  => 1,
                'settings'   => json_encode([
                    'ids' => [1, 2, 3] // mete os ids reais das tuas categorias
                ]),
            ],
            [
                'type'       => 'banner_product_left',
                'sort_order' => 4,
                'is_active'  => 1,
                'settings'   => json_encode([
                    'product_ids' => [1,2], // ids reais
                    'pins'        => [],
                    'banner'      => null
                ]),
            ],
            [
                'type'       => 'banner_product_right',
                'sort_order' => 5,
                'is_active'  => 1,
                'settings'   => json_encode([
                    'product_ids' => [3,4],
                    'pins'        => [],
                    'banner'      => null
                ]),
            ],
            [
                'type'       => 'product_loop_link',
                'sort_order' => 6,
                'is_active'  => 1,
                'settings'   => json_encode([
                    'limit' => 12
                ]),
            ],
        ];

        $this->db->table(website_presentation_table('website_module_home'))->insertBatch($data);
    }
}
