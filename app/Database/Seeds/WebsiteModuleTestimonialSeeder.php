<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WebsiteModuleTestimonialSeeder extends Seeder
{
    public function run()
    {
        helper('website_schema');
        $data = [

            [
                'title'          => 'Melhor Qualidade de Produto',
                'subtitle'       => 'Qualidade incomparável',
                'review_text'    => '“A qualidade do produto é excelente e tudo funciona de forma suave do início ao fim.”',
                'author_name'    => 'Brooklyn Simmons',
                'author_verified'=> 1,
                'rating'         => 5,
                'product_id'     => 1,
                'sort_order'     => 1,
                'is_active'      => 1,
            ],

            [
                'title'          => 'Serviço Dedicado',
                'subtitle'       => 'Apoio exemplar',
                'review_text'    => '“A equipa é extremamente prestável. Sempre que tínhamos dúvidas, ajudaram de imediato.”',
                'author_name'    => 'Mas Shin',
                'author_verified'=> 1,
                'rating'         => 5,
                'product_id'     => 2,
                'sort_order'     => 2,
                'is_active'      => 1,
            ],

            [
                'title'          => 'Confiabilidade Excecional',
                'subtitle'       => 'Sempre consistente',
                'review_text'    => '“Não falha. Funciona sempre como esperado, sem surpresas desagradáveis.”',
                'author_name'    => 'Manh Tran',
                'author_verified'=> 1,
                'rating'         => 5,
                'product_id'     => 3,
                'sort_order'     => 3,
                'is_active'      => 1,
            ],

            [
                'title'          => 'Entrega Rápida',
                'subtitle'       => 'Muito acima do esperado',
                'review_text'    => '“O produto chegou antes do prazo e exatamente como descrito. Serviço impecável.”',
                'author_name'    => 'Carla Mota',
                'author_verified'=> 1,
                'rating'         => 4,
                'product_id'     => 4,
                'sort_order'     => 4,
                'is_active'      => 1,
            ],

            [
                'title'          => 'Atendimento Excelente',
                'subtitle'       => 'Equipa altamente profissional',
                'review_text'    => '“Foram prestáveis desde o primeiro contacto até ao pós-venda. Estou muito satisfeita.”',
                'author_name'    => 'Rafael Matos',
                'author_verified'=> 1,
                'rating'         => 5,
                'product_id'     => 5,
                'sort_order'     => 5,
                'is_active'      => 1,
            ],

            [
                'title'          => 'Ótimo Custo-Benefício',
                'subtitle'       => 'Vale cada euro',
                'review_text'    => '“Excelente qualidade pelo preço. Superou as minhas expectativas.”',
                'author_name'    => 'Helena Duarte',
                'author_verified'=> 0,
                'rating'         => 4,
                'product_id'     => 6,
                'sort_order'     => 6,
                'is_active'      => 1,
            ],

            [
                'title'          => 'Muito Satisfeito',
                'subtitle'       => 'Recomendo totalmente',
                'review_text'    => '“Já comprei vários produtos desta marca e todos foram impecáveis.”',
                'author_name'    => 'João Ferreira',
                'author_verified'=> 1,
                'rating'         => 5,
                'product_id'     => 7,
                'sort_order'     => 7,
                'is_active'      => 1,
            ],

            [
                'title'          => 'Design Impressionante',
                'subtitle'       => 'Estética e funcionalidade',
                'review_text'    => '“Além de bonito, é muito prático no dia a dia. Não podia pedir mais.”',
                'author_name'    => 'Maria Lopes',
                'author_verified'=> 1,
                'rating'         => 5,
                'product_id'     => 8,
                'sort_order'     => 8,
                'is_active'      => 1,
            ],

        ];

        $this->db->table(website_presentation_table('website_module_testimonial'))->insertBatch($data);
    }
}
