<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShippingRates;


class ShippingRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $shippingDetails = [ 
            ['country_name' => 'Afghanistan', 'country_iso' => 'AFG','rate' => 25],
            ['country_name' => 'Albania', 'country_iso' => 'ALB','rate' => 30],
            ['country_name' => 'Algeria', 'country_iso' => 'DZA','rate' => 35],
            ['country_name' => 'Argentina', 'country_iso' => 'ARG','rate' => 40],
            ['country_name' => 'Australia', 'country_iso' => 'AUS','rate' => 45],
            ['country_name' => 'Austria', 'country_iso' => 'AUT','rate' => 50],
            ['country_name' => 'Bangladesh', 'country_iso' => 'BGD','rate' => 55],
            ['country_name' => 'Belgium', 'country_iso' => 'BEL','rate' => 60],
            ['country_name' => 'Brazil', 'country_iso' => 'BRA','rate' => 65],
            ['country_name' => 'Canada', 'country_iso' => 'CAN','rate' => 70],
            ['country_name' => 'China', 'country_iso' => 'CHN','rate' => 75],
            ['country_name' => 'Denmark', 'country_iso' => 'DNK','rate' => 80],
            ['country_name' => 'Egypt', 'country_iso' => 'EGY','rate' => 85],
            ['country_name' => 'France', 'country_iso' => 'FRA','rate' => 88],
            ['country_name' => 'Germany', 'country_iso' => 'DEU','rate' => 90],
            ['country_name' => 'India', 'country_iso' => 'IND','rate' => 55],
            ['country_name' => 'Indonesia', 'country_iso' => 'IDN','rate' => 10],
            ['country_name' => 'Ireland', 'country_iso' => 'IRL','rate' => 45],
            ['country_name' => 'Italy', 'country_iso' => 'ITA','rate' => 56],
            ['country_name' => 'Japan', 'country_iso' => 'JPN','rate' => 47],
            ['country_name' => 'Malaysia', 'country_iso' => 'MYS','rate' => 85],
            ['country_name' => 'Mexico', 'country_iso' => 'MEX','rate' => 95],
            ['country_name' => 'Netherlands', 'country_iso' => 'NLD','rate' => 100],
            ['country_name' => 'New Zealand', 'country_iso' => 'NZL','rate' => 45],
            ['country_name' => 'Norway', 'country_iso' => 'NOR','rate' => 41],
            ['country_name' => 'Pakistan', 'country_iso' => 'PAK','rate' => 56],
            ['country_name' => 'Philippines', 'country_iso' => 'PHL','rate' => 15],
            ['country_name' => 'Russia', 'country_iso' => 'RUS','rate' => 47],
            ['country_name' => 'Saudi Arabia', 'country_iso' => 'SAU','rate' => 50],
            ['country_name' => 'Singapore', 'country_iso' => 'SGP','rate' => 15],
            ['country_name' => 'South Africa', 'country_iso' => 'ZAF','rate' => 95],
            ['country_name' => 'South Korea', 'country_iso' => 'KOR','rate' => 56],
            ['country_name' => 'Spain', 'country_iso' => 'ESP','rate' => 46],
            ['country_name' => 'Sri Lanka', 'country_iso' => 'LKA','rate' => 42],
            ['country_name' => 'Sweden', 'country_iso' => 'SWE','rate' => 48],
            ['country_name' => 'Switzerland', 'country_iso' => 'CHE','rate' => 53],
            ['country_name' => 'Thailand', 'country_iso' => 'THA','rate' => 65],
            ['country_name' => 'Turkey', 'country_iso' => 'TUR','rate' => 47],
            ['country_name' => 'United Arab Emirates', 'country_iso' => 'ARE','rate' => 45],
            ['country_name' => 'United Kingdom', 'country_iso' => 'GBR','rate' => 65],
            ['country_name' => 'United States', 'country_iso' => 'USA','rate' => 45],
            ['country_name' => 'Vietnam', 'country_iso' => 'VNM','rate' => 42],
        ];

        ShippingRates::insert($shippingDetails);
    }
}
