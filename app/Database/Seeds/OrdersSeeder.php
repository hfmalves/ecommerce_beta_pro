<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class OrdersSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('pt_PT');

        // 🔥 Limpa tabelas na ordem certa
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0;');
        $this->db->table('orders_items')->truncate();
        $this->db->table('orders')->truncate();
        $this->db->table('shipping_methods')->truncate();
        $this->db->table('payment_methods')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1;');

        // 🔹 Shipping Methods
        $shippingMethods = [
            ['code' => 'ctt', 'name' => 'CTT Expresso', 'created_at' => date('Y-m-d H:i:s')],
            ['code' => 'ups', 'name' => 'UPS',          'created_at' => date('Y-m-d H:i:s')],
            ['code' => 'dhl', 'name' => 'DHL',          'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('shipping_methods')->insertBatch($shippingMethods);

        // 🔹 Payment Methods
        $paymentMethods = [
            ['code' => 'multibanco', 'name' => 'Multibanco', 'created_at' => date('Y-m-d H:i:s')],
            ['code' => 'mbway',      'name' => 'MBWay',      'created_at' => date('Y-m-d H:i:s')],
            ['code' => 'paypal',     'name' => 'PayPal',     'created_at' => date('Y-m-d H:i:s')],
            ['code' => 'visa',       'name' => 'Visa',       'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('payment_methods')->insertBatch($paymentMethods);

        // 🔹 Buscar IDs reais
        $shippingIds = array_column(
            $this->db->table('shipping_methods')->select('id')->get()->getResultArray(),
            'id'
        );
        $paymentIds = array_column(
            $this->db->table('payment_methods')->select('id')->get()->getResultArray(),
            'id'
        );

        // 🔹 Dados de referência
        $customers = $this->db->table('customers')->get()->getResultArray();
        $addresses = $this->db->table('customers_addresses')->get()->getResultArray();
        $products  = $this->db->table('products')->get()->getResultArray();

        if (empty($customers) || empty($addresses) || empty($products)) {
            echo "⚠️ Não há customers, addresses ou products suficientes para gerar orders.\n";
            return;
        }

        $statuses = ['pending','processing','completed','canceled','refunded'];

        // 🔹 Criar 100 orders
        for ($i = 1; $i <= 100; $i++) {
            $customer   = $faker->randomElement($customers);
            $userAddrs  = array_filter($addresses, fn($a) => $a['customer_id'] == $customer['id']);
            if (empty($userAddrs)) continue;

            $billing  = $faker->randomElement($userAddrs);
            $shipping = $faker->randomElement($userAddrs);

            $orderData = [
                'customer_id'         => $customer['id'],
                'status'              => $faker->randomElement($statuses),
                'billing_address_id'  => $billing['id'],
                'shipping_address_id' => $shipping['id'],
                'shipping_method_id'  => $faker->randomElement($shippingIds),
                'payment_method_id'   => $faker->randomElement($paymentIds),
                'created_at'          => $faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d H:i:s'),
                'updated_at'          => null,
            ];
            $this->db->table('orders')->insert($orderData);
            $orderId = $this->db->insertID();

            // 🔹 Itens para esta order
            $totalUnits  = $faker->numberBetween(1, 20);
            $numProducts = min($totalUnits, $faker->numberBetween(1, 3));

            $subtotal   = 0;
            $unitsLeft  = $totalUnits;
            $orderItems = [];

            for ($j = 1; $j <= $numProducts; $j++) {
                $product = $faker->randomElement($products);
                $price   = (float) $product['base_price_tax'];

                if ($j === $numProducts) {
                    $qty = $unitsLeft;
                } else {
                    $qty = $faker->numberBetween(1, $unitsLeft - ($numProducts - $j));
                }
                $unitsLeft -= $qty;

                $lineTotal = $price * $qty;
                $subtotal += $lineTotal;

                $orderItems[] = [
                    'order_id'   => $orderId,
                    'cart_id'    => null,
                    'product_id' => $product['id'],
                    'variant_id' => null,
                    'qty'        => $qty,
                    'price'      => $price,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => null,
                ];
            }

            // insere items
            $this->db->table('orders_items')->insertBatch($orderItems);

            // recalcula totais
            $tax      = $subtotal * 0.23;
            $discount = ($faker->boolean(20)) ? $subtotal * 0.1 : 0;
            $grand    = $subtotal + $tax - $discount;

            $this->db->table('orders')
                ->where('id', $orderId)
                ->update([
                    'total_items'    => $totalUnits,
                    'total_tax'      => $tax,
                    'total_discount' => $discount,
                    'grand_total'    => $grand,
                    'updated_at'     => date('Y-m-d H:i:s'),
                ]);

            echo "Ordem #{$orderId} → {$totalUnits} unidades, {$numProducts} produtos, total {$grand}€\n";
        }

        echo "✅ Foram geradas 100 orders com items + shipping_methods + payment_methods!\n";
    }
}
