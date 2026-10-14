<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RmaSeeder extends Seeder
{
    public function run()
    {
        // Limpar tabelas
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('orders_return_items')->truncate();
        $this->db->table('orders_returns')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');


        // Status possíveis
        $statuses = ['pending', 'approved', 'rejected', 'received', 'refunded'];

        // Razões em inglês
        $reasons = [
            'Defective product',
            'Wrong size',
            'Different color than expected',
            'Not what I expected',
            'Late delivery',
            'Changed my mind',
            'Missing parts',
            'Item damaged during transport',
            'Wrong product delivered',
            'Quality did not match description',
        ];

        $orders = $this->db->table('orders')->select('id, customer_id')->get()->getResultArray();
        $orderItems = $this->db->table('orders_items')->get()->getResultArray();
        $itemsByOrder = [];
        foreach ($orderItems as $item) {
            if ((int) $item['qty'] > 0) {
                $itemsByOrder[$item['order_id']][] = $item;
            }
        }
        $orders = array_values(array_filter($orders, static fn ($order) => isset($itemsByOrder[$order['id']])));
        shuffle($orders);

        $rmas  = [];
        $items = [];

        foreach (array_slice($orders, 0, 20) as $index => $order) {
            $i = $index + 1;
            $status = $statuses[array_rand($statuses)];
            $reason = $reasons[array_rand($reasons)];

            $rmas[] = [
                'id'          => $i,
                'order_id'    => $order['id'],
                'customer_id' => $order['customer_id'],
                'reason'      => $reason,
                'status'      => $status,
                'created_at'  => date('Y-m-d H:i:s', strtotime("-" . rand(0, 60) . " days")),
                'updated_at'  => date('Y-m-d H:i:s'),
            ];

            // 1 a 3 itens por cada RMA
            $candidates = $itemsByOrder[$order['id']];
            shuffle($candidates);
            $numItems = rand(1, min(3, count($candidates)));
            foreach (array_slice($candidates, 0, $numItems) as $orderItem) {
                $items[] = [
                    'rma_request_id' => $i,
                    'order_item_id'  => $orderItem['id'],
                    'qty_returned'   => rand(1, (int) $orderItem['qty']),
                    'status'         => $status,
                    'created_at'     => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s'),
                ];
            }
        }

        if (! empty($rmas)) {
            $this->db->table('orders_returns')->insertBatch($rmas);
            $this->db->table('orders_return_items')->insertBatch($items);
        }
    }
}
