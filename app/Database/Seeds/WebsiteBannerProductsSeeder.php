<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WebsiteBannerProductsSeeder extends Seeder
{
    public function run()
    {
        helper('website_schema');
        $db = \Config\Database::connect();

        // limpar a tabela para garantir só estes 2 blocos
        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        $db->table(website_presentation_table('website_module_banner_products_positions'))->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS = 1');

        $data = [
            // BLOCO LEFT
            [
                'title'        => 'Skincare Essentials',
                'subtitle'     => 'Up to 50% off Lorem ipsum dolor sit amet, consectetur adipiscing elit',
                'banner_image' => 'banner-2.jpg',
                'position'     => 'left',              // esquerda
                'product_ids'  => json_encode([10, 11]), // IDs reais de produtos
                'pins'         => json_encode([
                    ['slide' => 0, 'top' => 20, 'left' => 40],
                    ['slide' => 1, 'top' => 60, 'left' => 30],
                ]),
                'sort_order'   => 1,
                'created_at'   => date('Y-m-d H:i:s'),
            ],

            // BLOCO RIGHT
            [
                'title'        => 'Haircare Bundles',
                'subtitle'     => 'Save up to 30% on premium haircare sets',
                'banner_image' => 'banner-3.jpg',
                'position'     => 'right',             // direita
                'product_ids'  => json_encode([20, 21]),
                'pins'         => json_encode([
                    ['slide' => 0, 'top' => 35, 'left' => 55],
                ]),
                'sort_order'   => 2,
                'created_at'   => date('Y-m-d H:i:s'),
            ],
        ];

        $db->table(website_presentation_table('website_module_banner_products_positions'))->insertBatch($data);

        echo "✔️ Seed criado: Left + Right banners inseridos.\n";
    }
}
