<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StoresStockProductsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Loja 1 (Lisboa)
            [
                'store_id'           => 1,
                'product_id'         => 1,
                'product_variant_id' => null,
                'qty'                => 100,
            ],
            [
                'store_id'           => 1,
                'product_id'         => 2,
                'product_variant_id' => null,
                'qty'                => 50,
            ],

            // Loja 2 (Porto)
            [
                'store_id'           => 2,
                'product_id'         => 1,
                'product_variant_id' => null,
                'qty'                => 30,
            ],
            [
                'store_id'           => 2,
                'product_id'         => 3,
                'product_variant_id' => null,
                'qty'                => 70,
            ],
        ];

        $this->db->table('stores_products_stock')->insertBatch($data);
    }
}
