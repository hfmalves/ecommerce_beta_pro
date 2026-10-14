<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('pt_PT');

        // limpa tabelas (opcional, cuidado em produção!)
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
        $this->db->table('users_tokens')->truncate();
        $this->db->table('users_reviews')->truncate();
        $this->db->table('users_wishlists')->truncate();
        $this->db->table('users_addresses')->truncate();
        $this->db->table('users')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');

        // ids de produtos existentes
        $productIds = array_column(
            $this->db->table('products')->select('id')->get()->getResultArray(),
            'id'
        );

        if (empty($productIds)) {
            echo "⚠️ Nenhum produto encontrado. Corre o EcommerceSeeder primeiro.\n";
            return;
        }

        // cria 10 utilizadores
        for ($i = 1; $i <= 10; $i++) {
            $name  = $faker->name;
            $email = $faker->unique()->safeEmail();

            // === Users ===
            $this->db->table('users')->insert([
                'name'       => $name,
                'email'      => $email,
                'password'   => password_hash('password123', PASSWORD_BCRYPT),
                'phone'      => $faker->phoneNumber(),
                'login_2step' => $faker->boolean(30),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $userId = $this->db->insertID();

            // === Addresses ===
            $this->db->table('users_addresses')->insertBatch([
                [
                    'user_id'    => $userId,
                    'type'       => 'billing',
                    'street'     => $faker->streetAddress(),
                    'city'       => $faker->city(),
                    'postcode'   => $faker->postcode(),
                    'country'    => 'PT',
                    'is_default' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'user_id'    => $userId,
                    'type'       => 'shipping',
                    'street'     => $faker->streetAddress(),
                    'city'       => $faker->city(),
                    'postcode'   => $faker->postcode(),
                    'country'    => 'PT',
                    'is_default' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ],
            ]);

            // === Wishlists (2 produtos aleatórios) ===
            $wishlistProducts = $faker->randomElements($productIds, 2);
            foreach ($wishlistProducts as $pid) {
                $this->db->table('users_wishlists')->insert([
                    'user_id'    => $userId,
                    'product_id' => $pid,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // === Reviews (1 produto aleatório) ===
            $reviewProduct = $faker->randomElement($productIds);
            $this->db->table('users_reviews')->insert([
                'user_id'    => $userId,
                'product_id' => $reviewProduct,
                'rating'     => $faker->numberBetween(1, 5),
                'comment'    => $faker->sentence(12),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // === Tokens (simulação de password reset) ===
            if ($faker->boolean(40)) { // 40% dos users têm token ativo
                $this->db->table('users_tokens')->insert([
                    'user_id'    => $userId,
                    'token'      => bin2hex(random_bytes(16)),
                    'type'       => 'password_reset',
                    'expires_at' => date('Y-m-d H:i:s', strtotime('+1 day')),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        echo "✅ 10 utilizadores com moradas, wishlists, reviews e tokens criados!\n";
    }
}
