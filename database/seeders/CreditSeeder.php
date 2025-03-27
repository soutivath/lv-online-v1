<?php

namespace Database\Seeders;

use App\Models\Credit;
use Illuminate\Database\Seeder;

class CreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create standard credit options with prices
        $credits = [
            ['qty' => 1, 'price' => 100000],   // 1 credit - 100,000 LAK
            ['qty' => 2, 'price' => 200000],   // 2 credits - 200,000 LAK
            ['qty' => 3, 'price' => 300000],   // 3 credits - 300,000 LAK
            ['qty' => 4, 'price' => 400000],   // 4 credits - 400,000 LAK
            ['qty' => 5, 'price' => 500000],   // 5 credits - 500,000 LAK
        ];
        
        foreach ($credits as $credit) {
            Credit::create($credit);
        }
    }
}
