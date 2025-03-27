<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lao names
        $laoFirstNames = [
            'ສົມພອນ', 'ວິໄລ', 'ຈັນສຸກ', 'ສຸລິຍາ', 'ອານຸພາບ', 
            'ມະນີວອນ', 'ນິພາພອນ', 'ສຸພາພອນ', 'ວິໄລພອນ', 'ດວງຈັນ'
        ];
        
        $laoLastNames = [
            'ແສງຈັນ', 'ສີວິໄລ', 'ວົງວິຈິດ', 'ສິດທິລາດ', 'ຄຳຫລ້າ', 
            'ພິລາວົງ', 'ສີອຳພອນ', 'ພິມມະສອນ', 'ສີສະຫວາດ', 'ວິໄລສັກ'
        ];

        // Get student-type users
        $users = User::where('email', 'like', 'student%@laovieng.edu.la')->get();
        
        foreach ($users as $index => $user) {
            // Generate a random Lao student
            $firstName = $laoFirstNames[array_rand($laoFirstNames)];
            $lastName = $laoLastNames[array_rand($laoLastNames)];
            $gender = rand(0, 1) ? 'ຊາຍ' : 'ຍິງ';  // Male or Female in Lao
            
            Student::create([
                'user_id' => $user->id,
                'name' => $firstName,
                'sername' => $lastName,
                'gender' => $gender,
                'birthday' => Carbon::now()->subYears(rand(18, 30))->format('Y-m-d'),
                'nationality' => 'ລາວ',  // Lao nationality
                'tell' => '020' . rand(10000000, 99999999), // Lao mobile format
                'address' => 'ບ້ານ ' . $laoFirstNames[array_rand($laoFirstNames)] . ', ເມືອງ ' . $laoFirstNames[array_rand($laoFirstNames)] . ', ນະຄອນຫລວງວຽງຈັນ',
                // Random villages and districts in Vientiane Capital
            ]);
        }
    }
}
