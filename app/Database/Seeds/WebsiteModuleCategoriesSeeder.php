<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WebsiteModuleCategoriesSeeder extends Seeder
{
    public function run()
    {
        helper('website_schema');
        $db = \Config\Database::connect();

        // limpar a tabela para meter só UMA linha
        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        $db->table(website_presentation_table('website_module_categories'))->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS = 1');

        // buscar todas categorias reais
        $categories = $db->table('categories')->get()->getResultArray();

        // extrair apenas os IDs num array
        $ids = array_column($categories, 'id');

        // montar o bloco único
        $data = [
            'title'        => 'Bloco Principal',   // <-- mete o nome do bloco que quiseres
            'subtitle'     => 'Subtítulo do Bloco', // <-- opcional
            'category_ids' => json_encode($ids),    // <-- todas categorias
            'orders'       => 1,
            'created_at'   => date('Y-m-d H:i:s'),
        ];

        // inserir a única linha
        $db->table(website_presentation_table('website_module_categories'))->insert($data);

        echo "✔️ Bloco único criado com sucesso.\n";
    }
}
