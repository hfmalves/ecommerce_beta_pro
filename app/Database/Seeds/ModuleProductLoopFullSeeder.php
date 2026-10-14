<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ModuleProductLoopFullSeeder extends Seeder
{
    public function run()
    {
        helper('website_schema');
        //-----------------------------------------------------
        // LIMPAR TABELAS (DELETE + RESET AI)
        //-----------------------------------------------------
        $tables = [
            website_presentation_table('website_module_product_loop_products'),
            website_presentation_table('website_module_product_loop_categories'),
            website_presentation_table('website_module_product_loop_link'),
        ];

        foreach ($tables as $tbl) {
            $this->db->query("DELETE FROM `$tbl`");
            $this->db->query("ALTER TABLE `$tbl` AUTO_INCREMENT = 1");
        }

        //-----------------------------------------------------
        // 1) MÓDULO PRINCIPAL (TITLE DO BLOCO)
        //-----------------------------------------------------
        $module = [
            'title'         => 'Shop By Departments',
            'subtitle'      => null,
            'limit_products'=> 8,
            'sort_order'    => 1,
            'is_active'     => 1,
        ];

        $this->db->table(website_presentation_table('website_module_product_loop_link'))->insert($module);

        //-----------------------------------------------------
        // 2) CATEGORIAS DO TOPO (TABS)
        //-----------------------------------------------------
        $categoriesData = [
            [
                'name'        => 'Produtos novos na loja',
                'sort_order'  => 1,
                'category_id' => null,
            ],
            [
                'name'        => 'Os produtos mais procurados',
                'sort_order'  => 2,
                'category_id' => null,
            ],
            [
                'name'        => 'Até 60% desconto',
                'sort_order'  => 3,
                'category_id' => null,
            ],
        ];

        $this->db->table(website_presentation_table('website_module_product_loop_categories'))->insertBatch($categoriesData);

        $moduleCategories = $this->db
            ->table(website_presentation_table('website_module_product_loop_categories'))
            ->select('id')
            ->orderBy('id')
            ->get()
            ->getResultArray();

        //-----------------------------------------------------
        // 3) PRODUTOS (8 POR CADA CATEGORIA)
        //-----------------------------------------------------
        $products = [];
        $productIds = array_column(
            $this->db->table('products')->select('id')->get()->getResultArray(),
            'id'
        );

        foreach ($moduleCategories as $cat) {
            $categoryId = $cat['id'];

            shuffle($productIds);
            foreach (array_slice($productIds, 0, 8) as $position => $productId) {
                $products[] = [
                    'module_category_id' => $categoryId,
                    'product_id'         => $productId,
                    'sort_order'         => $position + 1,
                ];
            }
        }

        if (! empty($products)) {
            $this->db->table(website_presentation_table('website_module_product_loop_products'))->insertBatch($products);
        }
    }
}
