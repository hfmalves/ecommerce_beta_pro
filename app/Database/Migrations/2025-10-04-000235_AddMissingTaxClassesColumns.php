<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingTaxClassesColumns extends Migration
{
    public function up()
    {
        // Verifica se já existe a tabela antes de mexer
        if ($this->db->tableExists('tax_classes')) {
            // Adicionar ou atualizar colunas
            $fields = [];

            if (! $this->db->fieldExists('name', 'tax_classes')) {
                $fields['name'] = ['type' => 'VARCHAR', 'constraint' => 100];
            }

            if (! $this->db->fieldExists('rate', 'tax_classes')) {
                $fields['rate'] = ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0.00];
            }

            if (! $this->db->fieldExists('country', 'tax_classes')) {
                $fields['country'] = ['type' => 'VARCHAR', 'constraint' => 5, 'default' => 'PT'];
            }

            if (! $this->db->fieldExists('is_active', 'tax_classes')) {
                $fields['is_active'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1];
            }

            if (! empty($fields)) {
                $this->forge->addColumn('tax_classes', $fields);
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('tax_classes')) {
            // Podes reverter só as colunas novas
            $this->forge->dropColumn('tax_classes', ['name','rate','country','is_active']);
        }
    }
}
