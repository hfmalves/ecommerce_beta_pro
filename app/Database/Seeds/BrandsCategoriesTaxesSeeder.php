<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BrandsCategoriesTaxesSeeder extends Seeder
{
    public function run()
    {
        // === BrandsModel ===
        $brands = [
            ['name' => 'Nike', 'slug' => 'nike', 'description' => 'Marca global de calçado e vestuário desportivo', 'logo' => 'brands/nike.png'],
            ['name' => 'Adidas', 'slug' => 'adidas', 'description' => 'Equipamentos e calçado desportivo', 'logo' => 'brands/adidas.png'],
            ['name' => 'Puma', 'slug' => 'puma', 'description' => 'Moda e desporto de performance', 'logo' => 'brands/puma.png'],
            ['name' => 'Reebok', 'slug' => 'reebok', 'description' => 'Linha fitness e streetwear', 'logo' => 'brands/reebok.png'],
            ['name' => 'New Balance', 'slug' => 'new-balance', 'description' => 'Calçado desportivo premium', 'logo' => 'brands/newbalance.png'],
            ['name' => 'Asics', 'slug' => 'asics', 'description' => 'Equipamentos de corrida e performance', 'logo' => 'brands/asics.png'],
            ['name' => 'Converse', 'slug' => 'converse', 'description' => 'Clássicos streetwear', 'logo' => 'brands/converse.png'],
            ['name' => 'Levi’s', 'slug' => 'levis', 'description' => 'Jeans e moda casual', 'logo' => 'brands/levis.png'],
            ['name' => 'Zara', 'slug' => 'zara', 'description' => 'Moda internacional acessível', 'logo' => 'brands/zara.png'],
            ['name' => 'Mango', 'slug' => 'mango', 'description' => 'Vestuário contemporâneo', 'logo' => 'brands/mango.png'],
        ];
        $this->db->table('brands')->upsertBatch($brands);

        // === CategoriesModel (hierarquia simples) ===
        $categories = [
            // Top-level
            ['id' => 1, 'parent_id' => null, 'name' => 'Homem', 'slug' => 'homem', 'description' => 'Produtos masculinos'],
            ['id' => 2, 'parent_id' => null, 'name' => 'Mulher', 'slug' => 'mulher', 'description' => 'Produtos femininos'],
            ['id' => 3, 'parent_id' => null, 'name' => 'Criança', 'slug' => 'crianca', 'description' => 'Produtos infantis'],

            // Subcategorias
            ['id' => 4, 'parent_id' => 1, 'name' => 'Calçado', 'slug' => 'calcado-homem', 'description' => 'Sapatilhas, botas, chinelos'],
            ['id' => 5, 'parent_id' => 1, 'name' => 'Roupa', 'slug' => 'roupa-homem', 'description' => 'T-shirts, camisas, calças'],
            ['id' => 6, 'parent_id' => 1, 'name' => 'Acessórios', 'slug' => 'acessorios-homem', 'description' => 'Relógios, mochilas, bonés'],

            ['id' => 7, 'parent_id' => 2, 'name' => 'Calçado', 'slug' => 'calcado-mulher', 'description' => 'Sapatos, botas, sandálias'],
            ['id' => 8, 'parent_id' => 2, 'name' => 'Roupa', 'slug' => 'roupa-mulher', 'description' => 'Vestidos, saias, casacos'],
            ['id' => 9, 'parent_id' => 2, 'name' => 'Acessórios', 'slug' => 'acessorios-mulher', 'description' => 'Mala, bijuteria, óculos'],

            ['id' => 10, 'parent_id' => 3, 'name' => 'Calçado', 'slug' => 'calcado-crianca', 'description' => 'Sapatilhas e sandálias infantis'],
            ['id' => 11, 'parent_id' => 3, 'name' => 'Roupa', 'slug' => 'roupa-crianca', 'description' => 'T-shirts, casacos, calças'],
            ['id' => 12, 'parent_id' => 3, 'name' => 'Acessórios', 'slug' => 'acessorios-crianca', 'description' => 'Bonés, mochilas infantis'],
        ];
        $this->db->table('categories')->upsertBatch($categories);

        // === Tax Classes ===
        $taxes = [
            ['name' => 'IVA Normal PT', 'rate' => 23.00, 'country' => 'PT'],
            ['name' => 'IVA Reduzido PT', 'rate' => 6.00, 'country' => 'PT'],
            ['name' => 'IVA Intermédio PT', 'rate' => 13.00, 'country' => 'PT'],
        ];
        foreach ($taxes as $tax) {
            // ConfigSeeder already supplies some of these country/rate pairs.
            $exists = $this->db->table('conf_taxes')
                ->where('country', $tax['country'])
                ->where('rate', $tax['rate'])
                ->countAllResults() > 0;

            if (! $exists) {
                $this->db->table('conf_taxes')->insert($tax);
            }
        }
    }
}
