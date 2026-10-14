<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class CartsSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('pt_PT');


        $customers = $this->db->table('customers')->get()->getResultArray();
        $products  = $this->db->table('products')->get()->getResultArray();
        $variants  = $this->db->table('products_variants')->get()->getResultArray();

        if (empty($products) || empty($customers)) {
            throw new \RuntimeException('CartsSeeder requer produtos e clientes. Execute DatabaseSeeder primeiro.');
        }

        for ($i = 0; $i < 200; $i++) {
            // The current schema requires a registered customer (NOT NULL FK).
            $customer = $faker->randomElement($customers);
            $sessionId = $faker->uuid;

            // status do carrinho
            $status = $faker->randomElement(['active','abandoned','converted']);

            $cartData = [
                'customer_id'        => $customer['id'],
                'user_id'            => null,
                'session_id'         => $sessionId,
                'status'             => $status,
                'total_items'        => 0,
                'total_value'        => 0,
                'abandoned_at'       => $status === 'abandoned' ? $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s') : null,
                'converted_order_id' => null,
                'created_at'         => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d H:i:s'),
                'updated_at'         => null,
            ];

            $this->db->table('orders_carts')->insert($cartData);
            $cartId = $this->db->insertID();

            $totalItems = 0;
            $totalValue = 0;

            // adicionar 1–5 itens ao carrinho
            $numItems = rand(1, 5);
            for ($j = 0; $j < $numItems; $j++) {
                $product = $faker->randomElement($products);
                $prodVariants = array_filter($variants, fn($v) => $v['product_id'] == $product['id']);
                $variant = !empty($prodVariants) ? $faker->randomElement($prodVariants) : null;

                $qty   = rand(1, 3);
                $price = $variant['price'] ?? $product['base_price'] ?? $faker->randomFloat(2, 5, 100);
                $discount = $faker->boolean(30) ? $faker->randomFloat(2, 1, 5) : 0;
                $subtotal = ($price - $discount) * $qty;

                $this->db->table('orders_cart_items')->insert([
                    'cart_id'    => $cartId,
                    'product_id' => $product['id'],
                    'variant_id' => $variant['id'] ?? null,
                    'qty'        => $qty,
                    'price'      => $price,
                    'discount'   => $discount,
                    'subtotal'   => $subtotal,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => null,
                    'removed_at' => $faker->boolean(20) ? $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s') : null,
                ]);

                $totalItems += $qty;
                $totalValue += $subtotal;
            }

            // atualizar totais no carrinho
            $this->db->table('orders_carts')->where('id', $cartId)->update([
                'total_items' => $totalItems,
                'total_value' => $totalValue,
            ]);
        }

        echo "✅ Foram criados 200 carrinhos de teste de clientes com itens.\n";
    }
}
