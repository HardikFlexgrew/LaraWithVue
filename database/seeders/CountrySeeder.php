<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Country::insert([
            ['country_name' => 'Afghanistan', 'country_iso3' => 'AFG',  'created_at' => now()],
            ['country_name' => 'Albania', 'country_iso3' => 'ALB',  'created_at' => now()],
            ['country_name' => 'Algeria', 'country_iso3' => 'DZA',  'created_at' => now()],
            ['country_name' => 'Andorra', 'country_iso3' => 'AND',  'created_at' => now()],
            ['country_name' => 'Angola', 'country_iso3' => 'AGO',  'created_at' => now()],
            ['country_name' => 'Argentina', 'country_iso3' => 'ARG',  'created_at' => now()],
            ['country_name' => 'Australia', 'country_iso3' => 'AUS',  'created_at' => now()],
            ['country_name' => 'Austria', 'country_iso3' => 'AUT',  'created_at' => now()],
            ['country_name' => 'Bangladesh', 'country_iso3' => 'BGD',  'created_at' => now()],
            ['country_name' => 'Belgium', 'country_iso3' => 'BEL',  'created_at' => now()],
            ['country_name' => 'Brazil', 'country_iso3' => 'BRA',  'created_at' => now()],
            ['country_name' => 'Canada', 'country_iso3' => 'CAN',  'created_at' => now()],
            ['country_name' => 'China', 'country_iso3' => 'CHN',  'created_at' => now()],
            ['country_name' => 'Denmark', 'country_iso3' => 'DNK',  'created_at' => now()],
            ['country_name' => 'Egypt', 'country_iso3' => 'EGY',  'created_at' => now()],
            ['country_name' => 'Finland', 'country_iso3' => 'FIN',  'created_at' => now()],
            ['country_name' => 'France', 'country_iso3' => 'FRA',  'created_at' => now()],
            ['country_name' => 'Germany', 'country_iso3' => 'DEU',  'created_at' => now()],
            ['country_name' => 'Greece', 'country_iso3' => 'GRC',  'created_at' => now()],
            ['country_name' => 'India', 'country_iso3' => 'IND',  'created_at' => now()],
            ['country_name' => 'Indonesia', 'country_iso3' => 'IDN',  'created_at' => now()],
            ['country_name' => 'Iran', 'country_iso3' => 'IRN',  'created_at' => now()],
            ['country_name' => 'Iraq', 'country_iso3' => 'IRQ',  'created_at' => now()],
            ['country_name' => 'Ireland', 'country_iso3' => 'IRL',  'created_at' => now()],
            ['country_name' => 'Israel', 'country_iso3' => 'ISR',  'created_at' => now()],
            ['country_name' => 'Italy', 'country_iso3' => 'ITA',  'created_at' => now()],
            ['country_name' => 'Japan', 'country_iso3' => 'JPN',  'created_at' => now()],
            ['country_name' => 'Kenya', 'country_iso3' => 'KEN',  'created_at' => now()],
            ['country_name' => 'Malaysia', 'country_iso3' => 'MYS',  'created_at' => now()],
            ['country_name' => 'Mexico', 'country_iso3' => 'MEX',  'created_at' => now()],
            ['country_name' => 'Netherlands', 'country_iso3' => 'NLD',  'created_at' => now()],
            ['country_name' => 'New Zealand', 'country_iso3' => 'NZL',  'created_at' => now()],
            ['country_name' => 'Nigeria', 'country_iso3' => 'NGA',  'created_at' => now()],
            ['country_name' => 'Norway', 'country_iso3' => 'NOR',  'created_at' => now()],
            ['country_name' => 'Pakistan', 'country_iso3' => 'PAK',  'created_at' => now()],
            ['country_name' => 'Philippines', 'country_iso3' => 'PHL',  'created_at' => now()],
            ['country_name' => 'Poland', 'country_iso3' => 'POL',  'created_at' => now()],
            ['country_name' => 'Portugal', 'country_iso3' => 'PRT',  'created_at' => now()],
            ['country_name' => 'Qatar', 'country_iso3' => 'QAT',  'created_at' => now()],
            ['country_name' => 'Romania', 'country_iso3' => 'ROU',  'created_at' => now()],
            ['country_name' => 'Russia', 'country_iso3' => 'RUS',  'created_at' => now()],
            ['country_name' => 'Saudi Arabia', 'country_iso3' => 'SAU',  'created_at' => now()],
            ['country_name' => 'Singapore', 'country_iso3' => 'SGP',  'created_at' => now()],
            ['country_name' => 'South Africa', 'country_iso3' => 'ZAF',  'created_at' => now()],
            ['country_name' => 'South Korea', 'country_iso3' => 'KOR',  'created_at' => now()],
            ['country_name' => 'Spain', 'country_iso3' => 'ESP',  'created_at' => now()],
            ['country_name' => 'Sri Lanka', 'country_iso3' => 'LKA',  'created_at' => now()],
            ['country_name' => 'Sweden', 'country_iso3' => 'SWE',  'created_at' => now()],
            ['country_name' => 'Switzerland', 'country_iso3' => 'CHE',  'created_at' => now()],
            ['country_name' => 'Thailand', 'country_iso3' => 'THA',  'created_at' => now()],
            ['country_name' => 'Turkey', 'country_iso3' => 'TUR',  'created_at' => now()],
            ['country_name' => 'United Arab Emirates', 'country_iso3' => 'ARE',  'created_at' => now()],
            ['country_name' => 'United Kingdom', 'country_iso3' => 'GBR',  'created_at' => now()],
            ['country_name' => 'United States', 'country_iso3' => 'USA',  'created_at' => now()],
            ['country_name' => 'Vietnam', 'country_iso3' => 'VNM',  'created_at' => now()],
            ['country_name' => 'Yemen', 'country_iso3' => 'YEM',  'created_at' => now()],
            ['country_name' => 'Zambia', 'country_iso3' => 'ZMB',  'created_at' => now()],
            ['country_name' => 'Zimbabwe', 'country_iso3' => 'ZWE',  'created_at' => now()],
        ]);
    }
}
