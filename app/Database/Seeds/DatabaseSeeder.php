<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Rebuild demo data from scratch. Clears all tables except migration history.
 * Run with: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->truncateDatabase();

        // OrdersFlow includes orders, items, history, financial documents,
        // payments and shipments. Do not run the older overlapping seeders:
        // OrderSeeder, OrdersSeeder, InvoiceSeeder, ShipmentSeeder, CartSeeder,
        // CouponSeeder or OrderStatusHistorySeeder alongside this sequence.
        // ConfigSeeder already supplies currencies and integrations.
        $seeders = [
            // Configuration and reference data.
            ConfigSeeder::class,
            CountriesSeeder::class,
            CatalogSeeder::class,
            BrandsCategoriesTaxesSeeder::class,

            // Suppliers and brands must exist before products reference them.
            SupplierSeeder::class,
            BrandSeeder::class,
            EcommerceSeeder::class,
            ProductCategoriesSeeder::class,
            StoreSeeder::class,
            StoresStockProductsSeeder::class,

            // Groups must exist before customers; users are needed for returns.
            CustomersGroupsSeeder::class,
            CustomersSeeder::class,
            UserSeeder::class,

            // Sales and their dependent records.
            OrdersFlow::class,
            InvoicesSeeder::class,
            PaymentsUpdateSeeder::class,
            CartsSeeder::class,
            RmaSeeder::class,
            UpdateOrdersReturnsSeeder::class,

            // Promotions depend on products, customers and orders.
            CatalogRulesSeeder::class,
            CartRulesSeeder::class,
            CampaignSeeder::class,
            BulkCouponsSeeder::class,

            // Website content and modules.
            BlogSeeder::class,
            WebsitePagesSeeder::class,
            WebsiteMenuSeeder::class,
            HomeModulesSeeder::class,
            WebsiteSliderSeeder::class,
            WebsiteModuleIconsSeeder::class,
            WebsiteModuleCategoriesSeeder::class,
            WebsiteModuleTestimonialSeeder::class,
            WebsiteBannerProductsSeeder::class,
            ModuleProductLoopFullSeeder::class,
            WebsiteHomesSeeder::class,
            WebsiteThemeSeeder::class,
        ];

        foreach ($seeders as $seeder) {
            $this->call($seeder);
        }
    }

    private function truncateDatabase(): void
    {
        $migrationTable = $this->db->prefixTable(config('Migrations')->table);
        // Compatibility views expose legacy data but cannot be truncated.
        $tables = [];
        foreach ($this->db->query('SHOW FULL TABLES')->getResultArray() as $row) {
            $values = array_values($row);
            if ($values[1] === 'BASE TABLE') {
                $tables[] = $values[0];
            }
        }

        echo "A limpar todas as tabelas, exceto o histórico de migrações...\n";

        $this->db->disableForeignKeyChecks();

        try {
            foreach ($tables as $table) {
                if ($table === $migrationTable) {
                    continue;
                }

                if (! $this->db->table($table)->truncate()) {
                    throw new \RuntimeException('Não foi possível limpar a tabela: ' . $table);
                }
            }
        } finally {
            $this->db->enableForeignKeyChecks();
        }
    }
}
