<?php

namespace Database\Seeders;

use App\Models\Term;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create standard Lao college terms
        $terms = [
            'ພາກເຊົ້າ',    
            'ພາກບ່າຍ',    
            'ພາກຄ່ຳ',    
        ];
        
        foreach ($terms as $termName) {
            Term::create(['name' => $termName]);
        }
    }
}
