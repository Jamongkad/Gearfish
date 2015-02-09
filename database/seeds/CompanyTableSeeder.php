<?php
use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder {
    
	public function run()
	{
        DB::table('Company')->insert([
            ['name' => 'YidgetSoft', 'accountType' => 'free'],
            ['name' => 'Gearfish', 'accountType' => 'paid']
        ]);
	}
}
