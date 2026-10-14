<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WebsiteSliderSeeder extends Seeder
{
    public function run()
    {
        helper('website_schema');
        $this->db->table(website_presentation_table('website_sliders'))->truncate();

        $placeholder = '/assets/website/uploads/placeholder-slider.jpg';

        $slides = [
            [
                'title'     => 'Pure Glow Skin',
                'price_old' => '$89,99',
                'price_new' => '$49,99',
                'cta_text'  => 'Shop now',
                'cta_url'   => '/shop',
                'image'     => '/images/slider/slider-13.jpg',
                'sort_order'=> 1,
                'is_active' => 1
            ],
            [
                'title'     => 'Luxe Silk Mist',
                'price_old' => '$99,99',
                'price_new' => '$69,99',
                'cta_text'  => 'Shop now',
                'cta_url'   => '/shop',
                'image'     => '/images/slider/slider-14.jpg',
                'sort_order'=> 2,
                'is_active' => 1
            ],
            [
                'title'     => 'Fresh Bloom Skin',
                'price_old' => '$69,99',
                'price_new' => '$59,99',
                'cta_text'  => 'Shop now',
                'cta_url'   => '/shop',
                'image'     => '/images/slider/slider-15.jpg',
                'sort_order'=> 3,
                'is_active' => 1
            ],
            [
                'title'     => 'Velvet Glow Luxe',
                'price_old' => '$99,99',
                'price_new' => '$69,99',
                'cta_text'  => 'Shop now',
                'cta_url'   => '/shop',
                'image'     => '/images/slider/slider-14.jpg',
                'sort_order'=> 4,
                'is_active' => 1
            ],
        ];

        foreach ($slides as &$s) {
            if (!$s['image']) {
                $s['image'] = $placeholder;
            }
        }

        $this->db->table(website_presentation_table('website_sliders'))->insertBatch($slides);
    }
}
