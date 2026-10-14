<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixReturnItemsOrderItemForeignKey extends Migration
{
    public function up()
    {
        $this->changeReference('orders_items', 'rma_request_items_order_item_id_foreign', 'orders_return_items_current_order_item_foreign');
    }

    public function down()
    {
        $this->changeReference('order_items', 'orders_return_items_current_order_item_foreign', 'rma_request_items_order_item_id_foreign');
    }

    private function changeReference(string $target, string $oldConstraint, string $newConstraint): void
    {
        $table = $this->db->escapeIdentifiers($this->db->prefixTable('orders_return_items'));
        $targetTable = $this->db->escapeIdentifiers($this->db->prefixTable($target));
        $oldKey = $this->db->escapeIdentifiers($oldConstraint);
        $newKey = $this->db->escapeIdentifiers($newConstraint);

        // One ALTER keeps the existing constraint if validation of the new one fails.
        // Existing return items must already reference valid IDs in the target table.
        $this->db->query("ALTER TABLE {$table}
            DROP FOREIGN KEY {$oldKey},
            ADD CONSTRAINT {$newKey}
                FOREIGN KEY (`order_item_id`) REFERENCES {$targetTable} (`id`)
                ON DELETE CASCADE ON UPDATE CASCADE");
    }
}
