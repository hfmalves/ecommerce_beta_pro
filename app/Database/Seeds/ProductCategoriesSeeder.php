<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductCategoriesSeeder extends Seeder
{
    public function run()
    {
        // buscar todos os produtos
        $products = $this->db->table('products')->select('id')->get()->getResultArray();
        // buscar todas as categorias
        $categories = $this->db->table('categories')->select('id')->get()->getResultArray();

        if (empty($products) || empty($categories)) {
            echo "⚠️ Nenhum produto ou categoria encontrada.\n";
            return;
        }

        foreach ($products as $product) {
            // escolher 1–3 categorias aleatórias para cada produto
            $randCategories = array_rand($categories, rand(1, min(3, count($categories))));

            // se array_rand devolve um único valor, converte para array
            if (!is_array($randCategories)) {
                $randCategories = [$randCategories];
            }

            foreach ($randCategories as $index) {
                $this->db->table('products_categories')->upsert([
                    'product_id'  => $product['id'],
                    'category_id' => $categories[$index]['id'],
                ]);
            }
        }

        echo "✅ Produtos associados a categorias com sucesso!\n";
    }
}
