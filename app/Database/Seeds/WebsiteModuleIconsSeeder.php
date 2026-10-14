<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WebsiteModuleIconsSeeder extends Seeder
{
    public function run()
    {
        helper('website_schema');
        $this->db->table(website_presentation_table('website_module_icons'))->truncate();

        $data = [
            [
                'icon'       => 'icon-package',
                'title'      => '30 days return',
                'text'       => '30 day money back guarantee',
                'sort_order' => 1,
                'is_active'  => 1
            ],
            [
                'icon'       => 'icon-calender',
                'title'      => '3 year warranty',
                'text'       => 'Manufacturer\'s defect',
                'sort_order' => 2,
                'is_active'  => 1
            ],
            [
                'icon'       => 'icon-boat',
                'title'      => 'Free shipping',
                'text'       => 'Free Shipping for orders over $150',
                'sort_order' => 3,
                'is_active'  => 1
            ],
            [
                'icon'       => 'icon-headset',
                'title'      => 'Online support',
                'text'       => '24 hours a day, 7 days a week',
                'sort_order' => 4,
                'is_active'  => 1
            ],
        ];

        $this->db->table(website_presentation_table('website_module_icons'))->insertBatch($data);
    }
}
