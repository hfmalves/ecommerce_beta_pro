<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class ShowcaseBlocks extends BaseConfig
{
    public array $types = [
        'hero' => ['name' => 'Banner principal', 'description' => 'Slider com imagens, títulos e botões.'],
        'grid_banner' => ['name' => 'Banners', 'description' => 'Imagens promocionais lado a lado.'],
        'products_grid' => ['name' => 'Grelha de produtos', 'description' => 'Uma seleção de produtos da loja.'],
        'home_deals_day' => ['name' => 'Ofertas do dia', 'description' => 'Produtos em destaque num carrossel.'],
        'top_category_filter' => ['name' => 'Coleções', 'description' => 'Produtos organizados por separadores.'],
        'service_promotion' => ['name' => 'Serviços', 'description' => 'Envios, apoio e vantagens da loja.'],
        'blog_grid' => ['name' => 'Blog', 'description' => 'Artigos e novidades.'],
        'faq' => ['name' => 'Perguntas frequentes', 'description' => 'Perguntas e respostas.'],
        'footer' => ['name' => 'Rodapé', 'description' => 'Contactos e links da loja.'],
    ];
}
