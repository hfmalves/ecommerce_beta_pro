<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class EcommerceSeeder extends Seeder
{
    public function run()
    {
        helper('url');
        $faker = Factory::create('pt_PT');

        // === Limpar tabelas ===
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
        $this->db->table('products_images')->truncate();
        $this->db->table('products_variant_attributes')->truncate();
        $this->db->table('products_variants')->truncate();
        $this->db->table('products_attribute_values')->truncate();
        $this->db->table('products_attributes')->truncate();
        $this->db->table('products')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');

        // === ATRIBUTOS FIXOS ===
        $attributes = [
            ['code' => 'color', 'name' => 'Cor', 'type' => 'select'],
            ['code' => 'size', 'name' => 'Tamanho', 'type' => 'select'],
            ['code' => 'gender', 'name' => 'Tipo', 'type' => 'select'],
        ];

        $attrIds = [];
        foreach ($attributes as $attr) {
            $this->db->table('products_attributes')->insert(array_merge($attr, [
                'is_required'   => true,
                'is_filterable' => true,
                'created_at'    => date('Y-m-d H:i:s'),
            ]));
            $attrIds[$attr['code']] = $this->db->insertID();
        }

        // === VALORES DOS ATRIBUTOS ===
        $values = [
            'color'  => ['Preto', 'Branco', 'Azul', 'Vermelho', 'Verde'],
            'size'   => ['38', '40', '42', '44', '46'],
            'gender' => ['Homem', 'Mulher'],
        ];

        $valueIds = [];
        foreach ($values as $attrCode => $list) {
            foreach ($list as $val) {
                $this->db->table('products_attribute_values')->insert([
                    'attribute_id' => $attrIds[$attrCode],
                    'value'        => $val,
                    'created_at'   => date('Y-m-d H:i:s'),
                ]);
                $valueIds[$attrCode][$val] = $this->db->insertID();
            }
        }

        // === Preparar listas ===
        $brandIds     = array_column($this->db->table('brands')->select('id')->get()->getResultArray(), 'id');
        $categoryIds  = array_column($this->db->table('categories')->select('id')->get()->getResultArray(), 'id');
        $taxClasses   = $this->db->table('conf_taxes')->select('id, rate')->get()->getResultArray();

        // === PRODUTOS (50) ===
        for ($i = 1; $i <= 50; $i++) {
            $name = "Produto Exemplo $i";
            $sku  = "P$i-" . strtoupper($faker->bothify('??###'));

            // preços
            $costPrice  = $faker->randomFloat(2, 5, 80);
            $basePrice  = $faker->randomFloat(2, $costPrice + 5, $costPrice + 150);

            // desconto
            $hasDiscount   = $faker->boolean(60);
            $discountType  = $hasDiscount ? $faker->randomElement(['percent', 'fixed']) : null;
            $discountValue = $hasDiscount ? (
            $discountType === 'percent'
                ? $faker->numberBetween(5, 30)
                : $faker->randomFloat(2, 5, 20)
            ) : null;

            $specialPrice  = null;
            if ($hasDiscount) {
                if ($discountType === 'percent') {
                    $specialPrice = max(0, $basePrice - ($basePrice * ($discountValue / 100)));
                } elseif ($discountType === 'fixed') {
                    $specialPrice = max(0, $basePrice - $discountValue);
                }
            }

            $specialStart = $hasDiscount ? date('Y-m-d H:i:s', strtotime('+1 day')) : null;
            $specialEnd   = $hasDiscount ? date('Y-m-d H:i:s', strtotime('+10 days')) : null;

            // stock e dimensões
            $stockQty    = $faker->boolean(80) ? $faker->numberBetween(1, 100) : 0;
            $manageStock = $faker->boolean(70);
            $weight      = $faker->randomFloat(2, 0.5, 5);
            $width       = $faker->randomFloat(2, 10, 50);
            $height      = $faker->randomFloat(2, 5, 30);
            $length      = $faker->randomFloat(2, 15, 100);

            // escolher aleatoriamente uma tax_class
            $taxClass   = $faker->randomElement($taxClasses);
            $taxClassId = $taxClass['id'];
            $taxRate    = $taxClass['rate'];

            // calcular preço com imposto
            $basePriceTax = $basePrice * (1 + ($taxRate / 100));

            // inserir produto
            $this->db->table('products')->insert([
                'sku'                 => $sku,
                'ean'                 => $faker->ean13(),
                'upc'                 => $faker->ean8(),
                'isbn'                => $faker->isbn13(),
                'gtin'                => $faker->numerify('##############'),
                'name'                => $name,
                'slug'                => url_title($name, '-', true),
                'short_description'   => $faker->sentence(10),
                'description'         => $faker->paragraph(3),
                'cost_price'          => $costPrice,
                'base_price'          => $basePrice,
                'base_price_tax'      => $basePriceTax,
                'discount_type'       => $discountType,
                'discount_value'      => $discountValue,
                'special_price'       => $specialPrice,
                'special_price_start' => $specialStart,
                'special_price_end'   => $specialEnd,
                'stock_qty'           => $stockQty,
                'manage_stock'        => $manageStock,
                'weight'              => $weight,
                'width'               => $width,
                'height'              => $height,
                'length'              => $length,
                'meta_title'          => $faker->sentence(6),
                'meta_description'    => $faker->sentence(12),
                'meta_keywords'       => implode(',', $faker->words(5)),
                'is_featured'         => $faker->boolean(),
                'is_new'              => $faker->boolean(),
                'status'              => 'active',
                'brand_id'            => $faker->randomElement($brandIds),
                'category_id'         => $faker->randomElement($categoryIds),
                'tax_class_id'        => $taxClassId,
                'created_at'          => date('Y-m-d H:i:s'),
            ]);
            $productId = $this->db->insertID();

            // imagem base
            $this->db->table('products_images')->insert([
                'owner_type' => 'product',
                'owner_id'   => $productId,
                'path'       => "images/products/produto-$i/main.jpg",
                'position'   => 1,
                'alt_text'   => "$name - Imagem principal",
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // criar 3 combinações de variantes
            $combinations = [];
            while (count($combinations) < 3) {
                $combo = [
                    'color'  => $faker->randomElement(array_keys($valueIds['color'])),
                    'size'   => $faker->randomElement(array_keys($valueIds['size'])),
                    'gender' => $faker->randomElement(array_keys($valueIds['gender'])),
                ];
                $key = implode('-', $combo);
                if (!isset($combinations[$key])) {
                    $combinations[$key] = $combo;
                }
            }

            // inserir variantes
            foreach ($combinations as $idx => $combo) {
                $variantSku = $sku . '-' . $combo['color'] . $combo['size'] . $combo['gender'];

                $this->db->table('products_variants')->insert([
                    'product_id' => $productId,
                    'sku'        => $variantSku,
                    'price'      => $basePrice,
                    'stock_qty'  => $faker->numberBetween(0, 20),
                    'is_default' => $idx === array_key_first($combinations),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                $variantId = $this->db->insertID();

                // ligar atributos
                $this->db->table('products_variant_attributes')->insertBatch([
                    ['variant_id' => $variantId, 'attribute_value_id' => $valueIds['color'][$combo['color']]],
                    ['variant_id' => $variantId, 'attribute_value_id' => $valueIds['size'][$combo['size']]],
                    ['variant_id' => $variantId, 'attribute_value_id' => $valueIds['gender'][$combo['gender']]],
                ]);

                // imagem variante
                $this->db->table('products_images')->insert([
                    'owner_type' => 'variant',
                    'owner_id'   => $variantId,
                    'path'       => "images/products/produto-$i/variante-$idx.jpg",
                    'position'   => 1,
                    'alt_text'   => "$name variante $idx",
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
