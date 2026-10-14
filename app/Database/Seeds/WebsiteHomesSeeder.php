<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WebsiteHomesSeeder extends Seeder
{
    public function run()
    {
        helper('website_schema');
        $db = \Config\Database::connect();

        // Helper rápido
        $now = date('Y-m-d H:i:s');

        // Include every home block table, including footer, newsletter and targets.
        // TRUNCATE requires FK checks disabled even when child tables are empty.
        $tables = [];
        $prefix = $db->DBPrefix;
        foreach ($db->query('SHOW FULL TABLES')->getResultArray() as $row) {
            $values = array_values($row);
            if ($values[1] !== 'BASE TABLE') {
                continue;
            }
            $table = $values[0];
            $name = substr($table, strlen($prefix));
            $name = preg_replace('/^legacy_/', '', $name);
            if (str_starts_with($name, 'website_block_')
                || in_array($name, ['website_home_blocks', 'website_homes', 'website_faqs', 'website_faq_items'], true)) {
                $tables[] = $table;
            }
        }

        $db->disableForeignKeyChecks();
        try {
            foreach ($tables as $table) {
                if (! $db->table($table)->truncate()) {
                    throw new \RuntimeException('Não foi possível limpar a tabela: ' . $table);
                }
            }
        } finally {
            $db->enableForeignKeyChecks();
        }

        $store = $db->table('stores')->select('id')->orderBy('id')->get()->getRowArray();
        if (! $store) {
            throw new \RuntimeException('WebsiteHomesSeeder requer uma loja. Execute StoreSeeder primeiro.');
        }
        /**
         * HOMES
         */
        $homes = [
            'default',
            'christmas',
            'easter',
        ];

        $homeIds = [];
        foreach ($homes as $code) {
            $db->table('website_homes')->insert([
                'store_id'   => $store['id'],
                'name'       => ucfirst($code),
                'is_default' => $code === 'default',
                'block_id'   => 0,
                'home_code'  => $code,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $homeIds[$code] = $db->insertID();
        }

        /**
         * Catálogo MVP: buscar produtos e variantes reais (se existirem)
         * Se não existirem, os blocos ficam configurados mas sem items.
         */
        $products = $db->table('products')->select('id')->limit(30)->get()->getResultArray();
        $variants = $db->table('products_variants')->select('id, product_id')->limit(30)->get()->getResultArray();

        /**
         * BLOCKS (usar nomes "limpos")
         */
        $blocks = [
            'hero',
            'grid_banner',
            'products_grid',
            'blog_grid',
            'home_deals_day',
            'service_promotion',
            'faq',
            'top_category_filter',
        ];

        /**
         * INSERIR BLOCKS + CONTEÚDO MVP EM TODAS AS TABELAS
         */
        foreach ($homeIds as $homeCode => $homeId) {
            $position = 1;

            foreach ($blocks as $blockType) {

                // base
                $db->table('website_home_blocks')->insert([
                    'home_id'    => $homeId,
                    'block_type' => $blockType,
                    'position'   => $position,
                    'is_active'  => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $blockId = $db->insertID();

                /**
                 * HERO
                 */
                if ($blockType === 'hero') {

                    // HERO BASE (SO BLOCK_ID)
                    $db->table(website_presentation_table('website_block_hero'))->insert([
                        'block_id' => $blockId,
                    ]);

                    // SLIDES
                    $slides = [
                        [
                            'background_image' => 'hero_1.jpg',
                            'title'            => strtoupper($homeCode) . ' SLIDE 1',
                            'description'      => 'Descrição slide 1',
                            'button_text'      => 'Ver mais',
                            'button_link'      => '/shop',
                            'position'         => 1,
                        ],
                        [
                            'background_image' => 'hero_2.jpg',
                            'title'            => strtoupper($homeCode) . ' SLIDE 2',
                            'description'      => 'Descrição slide 2',
                            'button_text'      => 'Comprar',
                            'button_link'      => '/sale',
                            'position'         => 2,
                        ],
                        [
                            'background_image' => 'hero_3.jpg',
                            'title'            => strtoupper($homeCode) . ' SLIDE 3',
                            'description'      => 'Descrição slide 3',
                            'button_text'      => 'Descobrir',
                            'button_link'      => '/new',
                            'position'         => 3,
                        ],
                    ];

                    foreach ($slides as $s) {
                        $db->table(website_presentation_table('website_block_hero_slides'))->insert([
                            'block_id'         => $blockId,
                            'background_image' => $s['background_image'],
                            'title'            => $s['title'],
                            'description'      => $s['description'],
                            'button_text'      => $s['button_text'],
                            'button_link'      => $s['button_link'],
                            'position'         => $s['position'],
                            'created_at'       => $now,
                            'updated_at'       => $now,
                        ]);
                    }
                }


                /**
                 * GRID BANNER + ITEMS (3 items)
                 */
                if ($blockType === 'grid_banner') {
                    $db->table(website_presentation_table('website_block_grid_banner'))->insert([
                        'block_id'         => $blockId,
                        'columns_desktop'  => 3,
                        'columns_tablet'   => 2,
                        'columns_mobile'   => 1,
                        'image_size'       => 'large',
                    ]);

                    $items = [
                        ['image' => 'banner_1.jpg', 'title' => 'Novidades',  'subtitle' => null,          'link' => '/shop', 'text_color' => 'dark'],
                        ['image' => 'banner_2.jpg', 'title' => 'Promoções',  'subtitle' => null,          'link' => '/sale', 'text_color' => 'dark'],
                        ['image' => 'banner_3.jpg', 'title' => 'Coleções',   'subtitle' => 'Descobre',    'link' => '/new', 'text_color' => 'light'],
                    ];

                    $pos = 1;
                    foreach ($items as $it) {
                        $db->table(website_presentation_table('website_block_grid_banner_items'))->insert([
                            'block_id'    => $blockId,
                            'image'       => $it['image'],
                            'title'       => $it['title'],
                            'subtitle'    => $it['subtitle'],
                            'link'        => $it['link'],
                            'text_color'  => $it['text_color'],
                            'position'    => $pos++,
                            'is_active'   => true,
                        ]);
                    }
                }

                /**
                 * PRODUCTS GRID (auto por tipo) + opcional manual items
                 */
                if ($blockType === 'products_grid') {
                    $db->table(website_presentation_table('website_block_products_grid'))->insert([
                        'block_id'     => $blockId,
                        'title'        => 'Produtos em Destaque',
                        'grid_type'    => 'trending',
                        'items_limit'  => 8,
                        'cols_desktop' => 4,
                        'cols_tablet'  => 3,
                        'cols_mobile'  => 2,
                    ]);

                    // se quiseres forçar MVP com items: muda grid_type para manual e popula abaixo
                    // aqui deixo items vazios por default (mas com suporte):
                    // if ($homeCode === 'default') { ... }
                }

                /**
                 * BLOG GRID + ITEMS (3 posts)
                 */
                if ($blockType === 'blog_grid') {
                    $db->table(website_presentation_table('website_block_blog_grid'))->insert([
                        'block_id'        => $blockId,
                        'section_title'   => 'Our Blog',
                        'items_limit'     => 3,
                        'slides_desktop'  => 3,
                        'slides_tablet'   => 2,
                        'slides_mobile'   => 1,
                        'autoplay_delay'  => 5000,
                        'loop'            => true,
                    ]);

                    $posts = [
                        ['image' => 'post1.jpg', 'author' => 'Admin', 'published_at' => '2025-01-10', 'title' => 'Post MVP 1', 'link' => '/blog/post-mvp-1'],
                        ['image' => 'post2.jpg', 'author' => 'Admin', 'published_at' => '2025-01-12', 'title' => 'Post MVP 2', 'link' => '/blog/post-mvp-2'],
                        ['image' => 'post3.jpg', 'author' => 'Admin', 'published_at' => '2025-01-15', 'title' => 'Post MVP 3', 'link' => '/blog/post-mvp-3'],
                    ];

                    $pos = 1;
                    foreach ($posts as $p) {
                        $db->table(website_presentation_table('website_block_blog_grid_items'))->insert([
                            'block_id'      => $blockId,
                            'image'         => $p['image'],
                            'author'        => $p['author'],
                            'published_at'  => $p['published_at'],
                            'title'         => $p['title'],
                            'link'          => $p['link'],
                            'position'      => $pos++,
                            'is_active'     => true,
                        ]);
                    }
                }

                /**
                 * HOME DEALS DAY + ITEMS (min 5 items se houver produtos/variantes)
                 */
                if ($blockType === 'home_deals_day') {
                    $db->table(website_presentation_table('website_block_home_deals_day'))->insert([
                        'block_id'        => $blockId,
                        'title'           => 'Ofertas do Dia',
                        'items_limit'     => 10,
                        'autoplay_delay'  => 5000,
                        'slides_desktop'  => 4,
                        'slides_tablet'   => 3,
                        'slides_mobile'   => 2,
                        'loop'            => false,
                    ]);

                    $pos = 1;

                    // Preferir variantes
                    foreach ($variants as $v) {
                        if ($pos > 5) break;
                        $db->table(website_presentation_table('website_block_home_deals_day_items'))->insert([
                            'block_id'            => $blockId,
                            'product_id'          => $v['product_id'],
                            'product_variant_id'  => $v['id'],
                            'position'            => $pos++,
                            'is_active'           => true,
                        ]);
                    }

                    // Fallback produtos
                    $i = 0;
                    while ($pos <= 5 && $i < count($products)) {
                        $db->table(website_presentation_table('website_block_home_deals_day_items'))->insert([
                            'block_id'   => $blockId,
                            'product_id' => $products[$i]['id'],
                            'position'   => $pos++,
                            'is_active'  => true,
                        ]);
                        $i++;
                    }
                }

                /**
                 * SERVICE PROMOTION + ITEMS (3)
                 */
                if ($blockType === 'service_promotion') {
                    $db->table(website_presentation_table('website_block_service_promotion'))->insert([
                        'block_id'     => $blockId,
                        'layout'       => 'horizontal',
                        'items_limit'  => 3,
                    ]);

                    $services = [
                        ['icon' => 'icon_shipping',  'title' => 'Envios Rápidos',   'subtitle' => 'Entrega em 24/48h'],
                        ['icon' => 'icon_headphone', 'title' => 'Apoio ao Cliente', 'subtitle' => 'Suporte dedicado'],
                        ['icon' => 'icon_shield',    'title' => 'Compra Segura',    'subtitle' => 'Pagamentos protegidos'],
                    ];

                    $pos = 1;
                    foreach ($services as $s) {
                        $db->table(website_presentation_table('website_block_service_promotion_items'))->insert([
                            'block_id'   => $blockId,
                            'icon'       => $s['icon'],
                            'title'      => $s['title'],
                            'subtitle'   => $s['subtitle'],
                            'position'   => $pos++,
                            'is_active'  => true,
                        ]);
                    }
                }

                /**
                 * FAQ + ITEMS (2)
                 * (FAQs ficam fora de "website_block_*" por design)
                 */
                if ($blockType === 'faq') {
                    $db->table('website_faqs')->insert([
                        'code'         => 'faq_' . $homeCode,
                        'title'        => 'Perguntas Frequentes',
                        'context_type' => 'home',
                        'context_id'   => $homeId,
                        'is_active'    => true,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ]);
                    $faqId = $db->insertID();

                    $qs = [
                        ['q' => 'Quais os prazos de entrega?', 'a' => 'Depende do destino e do método escolhido.'],
                        ['q' => 'Posso devolver um produto?', 'a' => 'Sim, até 14 dias após receção.'],
                    ];

                    $pos = 1;
                    foreach ($qs as $q) {
                        $db->table('website_faq_items')->insert([
                            'faq_id'    => $faqId,
                            'question'  => $q['q'],
                            'answer'    => $q['a'],
                            'position'  => $pos++,
                            'is_active' => true,
                        ]);
                    }
                }

                /**
                 * TOP CATEGORY FILTER + TABS + TAB ITEMS
                 * Pelo menos 3 tabs e 5 produtos/variantes por tab (manual).
                 */
                if ($blockType === 'top_category_filter') {
                    $db->table(website_presentation_table('website_block_top_category_filter'))->insert([
                        'block_id'        => $blockId,
                        'title'           => 'Coleções em Destaque',
                        'slides_desktop'  => 5,
                        'slides_tablet'   => 3,
                        'slides_mobile'   => 2,
                        'autoplay_delay'  => 5000,
                        'loop'            => false,
                    ]);

                    $tabs = [
                        'Todos',
                        'Mais Vendidos',
                        'Tendências',
                    ];

                    $tabPos = 1;
                    foreach ($tabs as $label) {
                        $db->table(website_presentation_table('website_block_top_category_filter_tabs'))->insert([
                            'block_id'     => $blockId,
                            'label'        => $label,
                            'source_type'  => 'manual',
                            'items_limit'  => 10,
                            'position'     => $tabPos++,
                            'is_active'    => true,
                        ]);
                        $tabId = $db->insertID();

                        $itemPos = 1;

                        // 1) variantes primeiro
                        foreach ($variants as $v) {
                            if ($itemPos > 5) break;

                            $db->table(website_presentation_table('website_block_top_category_filter_tab_items'))->insert([
                                'tab_id'             => $tabId,
                                'product_id'         => $v['product_id'],
                                'product_variant_id' => $v['id'],
                                'position'           => $itemPos++,
                                'is_active'          => true,
                            ]);
                        }

                        // 2) fallback produtos (se não houver variantes suficientes)
                        $i = 0;
                        while ($itemPos <= 5 && $i < count($products)) {
                            $db->table(website_presentation_table('website_block_top_category_filter_tab_items'))->insert([
                                'tab_id'     => $tabId,
                                'product_id' => $products[$i]['id'],
                                'position'   => $itemPos++,
                                'is_active'  => true,
                            ]);
                            $i++;
                        }
                    }
                }

                $position++;
            }
        }
    }
}
