<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call('CompanyTableSeeder');
        $this->command->info('Company Table Seeded!');
		$this->call('UploadsTableSeeder');
        $this->command->info('Uploads Table Seeded!');
	}

}
