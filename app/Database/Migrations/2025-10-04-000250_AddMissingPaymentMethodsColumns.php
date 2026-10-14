<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingPaymentMethodsColumns extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('payment_methods')) {
            $fields = [];

            if (! $this->db->fieldExists('provider', 'payment_methods')) {
                $fields['provider'] = ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true];
            }

            if (! $this->db->fieldExists('config_json', 'payment_methods')) {
                $fields['config_json'] = ['type' => 'JSON', 'null' => true];
            }

            if (! $this->db->fieldExists('is_default', 'payment_methods')) {
                $fields['is_default'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0];
            }

            if (! empty($fields)) {
                $this->forge->addColumn('payment_methods', $fields);
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('payment_methods')) {
            $this->forge->dropColumn('payment_methods', ['provider','config_json','is_default']);
        }
    }
}
