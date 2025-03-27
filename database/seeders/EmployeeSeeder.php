<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lao names
        $laoFirstNames = [
            'ພູວົງ', 'ຄຳຫຼ້າ', 'ສົມສັກ', 'ຄຳຫຼວງ', 'ປັນຍາ', 
            'ສີສົມພອນ', 'ສຸລິພອນ', 'ວັນນາ', 'ວາລີ', 'ມະລິວັນ'
        ];
        
        $laoLastNames = [
            'ວົງປະເສີດ', 'ພິລາວົງ', 'ໄຊລາດຊະວົງ', 'ສຸວັນນະຫົງ', 'ວັນມະນີ', 
            'ສີພົນເມືອງ', 'ສຸວັນນະພັກດີ', 'ແສງຈັນ', 'ຄຳປະສົງ', 'ສຸທຳມະວົງ'
        ];

        // Get admin user and employee-type users
        $adminUser = User::where('email', 'admin@laovieng.edu.la')->first();
        $employeeUsers = User::where('email', 'like', 'employee%@laovieng.edu.la')->get();
        
        // Create admin employee
        Employee::create([
            'user_id' => $adminUser->id,
            'name' => 'ບຸນມີ',  // Bunmi (admin first name)
            'sername' => 'ວົງປະເສີດ',  // Vongprasert (admin last name)
            'gender' => 'ຊາຍ',  // Male
            'birthday' => Carbon::now()->subYears(35)->format('Y-m-d'),
            'date' => Carbon::now()->subYears(5)->format('Y-m-d'), // Joined 5 years ago
            'tell' => '02055555555',
            'address' => 'ບ້ານ ທາດຫຼວງ, ເມືອງ ສີໂຄດຕະບອງ, ນະຄອນຫຼວງວຽງຈັນ',
        ]);
        
        // Create regular employees
        foreach ($employeeUsers as $index => $user) {
            $firstName = $laoFirstNames[array_rand($laoFirstNames)];
            $lastName = $laoLastNames[array_rand($laoLastNames)];
            $gender = rand(0, 1) ? 'ຊາຍ' : 'ຍິງ';  // Male or Female
            
            Employee::create([
                'user_id' => $user->id,
                'name' => $firstName,
                'sername' => $lastName,
                'gender' => $gender,
                'birthday' => Carbon::now()->subYears(rand(25, 50))->format('Y-m-d'),
                'date' => Carbon::now()->subYears(rand(1, 10))->format('Y-m-d'),
                'tell' => '020' . rand(10000000, 99999999),
                'address' => 'ບ້ານ ' . $laoFirstNames[array_rand($laoFirstNames)] . ', ເມືອງ ' . $laoFirstNames[array_rand($laoFirstNames)] . ', ນະຄອນຫລວງວຽງຈັນ',
            ]);
        }
    }
}
