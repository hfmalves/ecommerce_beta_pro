<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/** Companies using the platform, separate from admin users and shop customers. */
class CreateClientsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 150],
            'code' => ['type' => 'VARCHAR', 'constraint' => 80],
            'email' => ['type' => 'VARCHAR', 'constraint' => 254, 'null' => true],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'tax_id' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'is_active' => ['type' => 'BOOLEAN', 'default' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('clients');
    }

    public function down()
    {
        $this->forge->dropTable('clients');
    }
}
