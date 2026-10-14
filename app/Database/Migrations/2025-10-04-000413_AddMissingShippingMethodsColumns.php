<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingShippingMethodsColumns extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('shipping_methods')) {
            $fields = [];

            if (! $this->db->fieldExists('provider', 'shipping_methods')) {
                $fields['provider'] = ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true];
            }

            if (! $this->db->fieldExists('config_json', 'shipping_methods')) {
                $fields['config_json'] = ['type' => 'JSON', 'null' => true];
            }

            if (! $this->db->fieldExists('free_shipping_min', 'shipping_methods')) {
                $fields['free_shipping_min'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true];
            }

            if (! empty($fields)) {
                $this->forge->addColumn('shipping_methods', $fields);
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('shipping_methods')) {
            $this->forge->dropColumn('shipping_methods', ['provider','config_json','free_shipping_min']);
        }
    }
}
