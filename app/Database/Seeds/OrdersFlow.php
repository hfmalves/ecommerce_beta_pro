<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrdersFlow extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $shippingMethods = $db->table('conf_shipping')->get()->getResultArray();
        $paymentMethods = $db->table('conf_payments')->get()->getResultArray();
        $users = $db->table('users')->select('id')->get()->getResultArray();

        // Validate references before clearing any sales data.
        if (empty($shippingMethods) || empty($paymentMethods) || empty($users)) {
            throw new \RuntimeException('OrdersFlow requer métodos em conf_shipping/conf_payments e utilizadores em users. Execute DatabaseSeeder primeiro.');
        }

        $userId = $users[array_rand($users)]['id'];

        echo "⚙️ A preparar base de dados...\n";

        // ============================================================
        // 1. DESATIVAR FK E LIMPAR TABELAS
        // ============================================================
        $db->query('SET FOREIGN_KEY_CHECKS = 0');

        $tables = [
            'orders_items',
            'orders_status_history',
            'payments',
            'financial_documents',
            'orders',
            'orders_shipments'
        ];

        foreach ($tables as $table) {
            $db->query("TRUNCATE TABLE {$table}");
        }

        $db->query('SET FOREIGN_KEY_CHECKS = 1');
        echo "🧹 Tabelas limpas.\n";

        // ============================================================
        // 2. BUSCAR CLIENTES, MORADAS E PRODUTOS REAIS
        // ============================================================
        $customers = $db->table('customers')->where('is_active', 1)->get()->getResultArray();
        $addresses = $db->table('customers_addresses')->get()->getResultArray();
        $products  = $db->table('products')->where('status', 1)->get()->getResultArray();

        if (empty($customers) || empty($addresses) || empty($products)) {
            echo "❌ Dados insuficientes para gerar encomendas.\n";
            return;
        }

        echo "🚀 A gerar encomendas...\n";
        $counter = 0;

        // ============================================================
        // 3. GERAR ENCOMENDAS COM ESTADOS REALISTAS
        // ============================================================
        foreach ($customers as $cust) {
            if ($counter >= 200) break;

            $custAddresses = array_values(array_filter($addresses, fn($a) => $a['customer_id'] == $cust['id']));
            if (empty($custAddresses)) continue;

            $billingId  = $custAddresses[0]['id'];
            $shippingId = $custAddresses[0]['id'];

            // Escolher shipping e payment aleatórios
            $shippingMethod = $shippingMethods[array_rand($shippingMethods)];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            $shippingMethodId = $shippingMethod['id'];
            $paymentMethodId = $paymentMethod['id'];

            // Produtos diferentes
            shuffle($products);
            $selected = array_slice($products, 0, rand(2, 5));

            $totalItems = 0;
            $subtotal   = 0;
            $totalTax   = 0;
            $orderItems = [];

            foreach ($selected as $p) {
                $qty = rand(1, 4);
                $lineSubtotal = $p['base_price'] * $qty;
                $lineTax = $p['base_price_tax'] * $qty;

                $orderItems[] = [
                    'product' => $p,
                    'qty'     => $qty,
                    'subtotal'=> $lineSubtotal,
                    'tax'     => $lineTax,
                    'total'   => $lineSubtotal + $lineTax,
                ];

                $totalItems += $qty;
                $subtotal   += $lineSubtotal;
                $totalTax   += $lineTax;
            }

            $grandTotal = $subtotal + $totalTax;

            $possibleStatuses = [
                'pending'    => 25,
                'processing' => 25,
                'completed'  => 30,
                'canceled'   => 10,
                'refunded'   => 10,
            ];

            $rand = rand(1, 100);
            $accum = 0;
            $status = 'pending';
            foreach ($possibleStatuses as $s => $p) {
                $accum += $p;
                if ($rand <= $accum) {
                    $status = $s;
                    break;
                }
            }

            $paymentStatus = match ($status) {
                'pending'    => 'pending',
                'processing' => 'partial',
                'completed'  => 'paid',
                'canceled'   => 'failed',
                'refunded'   => 'refunded',
                default      => 'pending',
            };

            // Criar encomenda
            $order = [
                'customer_id'         => $cust['id'],
                'user_id'             => $userId,
                'status'              => $status,
                'total_items'         => $totalItems,
                'total_tax'           => $totalTax,
                'total_discount'      => 0,
                'grand_total'         => $grandTotal,
                'billing_address_id'  => $billingId,
                'shipping_address_id' => $shippingId,
                'shipping_method_id'  => $shippingMethodId,
                'payment_method_id'   => $paymentMethodId,
                'created_at'          => date('Y-m-d H:i:s', strtotime('-' . rand(1, 60) . ' days')),
                'updated_at'          => date('Y-m-d H:i:s'),
            ];
            $db->table('orders')->insert($order);
            $orderId = $db->insertID();

            // Inserir itens
            foreach ($orderItems as $line) {
                $p = $line['product'];
                $qty = $line['qty'];

                $db->table('orders_items')->insert([
                    'order_id'   => $orderId,
                    'cart_id'    => null,
                    'product_id' => $p['id'],
                    'variant_id' => null,
                    //'name'       => $p['name'],
                    //'sku'        => $p['sku'] ?: 'SKU-' . $p['id'],
                    'qty'        => $qty,
                    'price'      => $p['base_price'],
                    //'price_tax'  => $p['base_price_tax'],
                    //'discount'   => 0,
                    //'row_total'  => ($p['base_price'] + $p['base_price_tax']) * $qty,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Documento financeiro
            $db->table('financial_documents')->insert([
                'order_id'            => $orderId,
                'customer_id'         => $cust['id'],
                'billing_address_id'  => $billingId,
                'shipping_address_id' => $shippingId,
                'shipping_method_id'  => $shippingMethodId,
                'payment_method_id'   => $paymentMethodId,
                'invoice_number'      => str_pad($orderId, 6, '0', STR_PAD_LEFT),
                'series'              => 'A',
                'type'                => 'invoice',
                'status'              => in_array($status, ['canceled']) ? 'canceled' : 'paid',
                'payment_status'      => $paymentStatus,
                'paid_at'             => in_array($paymentStatus, ['paid','refunded']) ? date('Y-m-d H:i:s') : null,
                'due_date'            => date('Y-m-d H:i:s', strtotime('+10 days')),
                'total'               => $grandTotal,
                'subtotal'            => $subtotal,
                'tax_total'           => $totalTax,
                'discount_total'      => 0,
                'currency'            => 'EUR',
                'notes'               => 'Documento gerado automaticamente pelo seeder.',
                'pdf_path'            => null,
                'hash'                => md5(uniqid((string)$orderId, true)),
                'created_by'          => $userId,
                'updated_by'          => $userId,
                'created_at'          => date('Y-m-d H:i:s'),
                'updated_at'          => date('Y-m-d H:i:s'),
            ]);

            $invoiceId = $db->insertID();

            // Pagamento
            $db->table('payments')->insert([
                'invoice_id'        => $invoiceId,
                'order_id'          => $orderId,
                'amount'            => $grandTotal,
                'currency'          => 'EUR',
                'exchange_rate'     => 1.0,
                'method'            => $paymentMethod['name'],
                'payment_method_id' => $paymentMethodId,
                'status'            => $paymentStatus,
                'transaction_id'    => 'TXN-' . str_pad($orderId, 6, '0', STR_PAD_LEFT),
                'reference'         => strtoupper(substr($order['status'], 0, 3)) . '-' . rand(100000, 999999),
                'notes'             => 'Pagamento ' . $paymentStatus . ' gerado automaticamente.',
                'created_by'        => $userId,
                'updated_by'        => $userId,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
                'paid_at'           => in_array($paymentStatus, ['paid','refunded']) ? date('Y-m-d H:i:s') : null,
            ]);

            // Histórico
            $history = [
                [
                    'order_id'   => $orderId,
                    'status'     => 'pending',
                    'comment'    => 'Encomenda criada.',
                    'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                ]
            ];

            if ($status !== 'pending') {
                $history[] = [
                    'order_id'   => $orderId,
                    'status'     => $status,
                    'comment'    => ucfirst($status) . ' automaticamente pelo seeder.',
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }

            $db->table('orders_status_history')->insertBatch($history);
            $totalWeight = 0;
            $totalVolume = 0;

            foreach ($orderItems as $item) {
                $p = $item['product'];
                $qty = $item['qty'];

                $w = $p['weight'] ?? 0;
                $width  = $p['width']  ?? 0;
                $height = $p['height'] ?? 0;
                $length = $p['length'] ?? 0;

                $totalWeight += $w * $qty;
                $totalVolume += ($width * $height * $length) * $qty;
            }
            // ============================================================
            // 4. GERAR ENVIO (orders_shipments)
            // ============================================================
            $shippingStatus = match ($status) {
                'completed'  => 'delivered',
                'processing' => 'shipped',
                'pending'    => 'pending',
                'canceled'   => 'canceled',
                'refunded'   => 'returned',
                default      => 'pending',
            };

            $carrier = $shippingMethod['name'];

            $trackingNumber = strtoupper(substr($carrier, 0, 3)) . '-' . rand(100000, 999999);
            $baseTrackingUrl = match ($carrier) {
                'CTT Expresso' => 'https://www.ctt.pt/feapl_2/app/open/objectSearch/objectSearch.jspx?objects=',
                'DHL'          => 'https://www.dhl.com/pt-pt/home/tracking.html?tracking-id=',
                'UPS'          => 'https://www.ups.com/track?tracknum=',
                default        => '#',
            };

            $shippedAt = in_array($shippingStatus, ['shipped', 'delivered', 'returned'])
                ? date('Y-m-d H:i:s', strtotime('-' . rand(1, 10) . ' days'))
                : null;

            $deliveredAt = ($shippingStatus === 'delivered')
                ? date('Y-m-d H:i:s', strtotime('-' . rand(0, 3) . ' days'))
                : null;

            $returnedAt = ($shippingStatus === 'returned')
                ? date('Y-m-d H:i:s', strtotime('-' . rand(0, 2) . ' days'))
                : null;

            $db->table('orders_shipments')->insert([
                'order_id'           => $orderId,
                'tracking_number'    => $trackingNumber,
                'tracking_url'       => $baseTrackingUrl . $trackingNumber,
                'shipping_label_url' => null,
                'carrier'            => $carrier,
                'status'             => $shippingStatus,
                'shipped_at'         => $shippedAt,
                'delivered_at'       => $deliveredAt,
                'returned_at'        => $returnedAt,
                'comments'           => 'Envio gerado automaticamente pelo seeder.',
                'created_at'         => date('Y-m-d H:i:s'),
                'weight'             => $totalWeight,
                'volume'             => $totalVolume,
            ]);

            $counter++;
        }

        echo "✅ {$counter} encomendas reais criadas com sucesso.\n";
    }
}
