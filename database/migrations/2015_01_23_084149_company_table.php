<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('Company', function(Blueprint $table)
		{
			//
            $table->engine = "InnoDB";
			$table->increments('id');
            $table->string('name', 250);
            $table->string('accountType', 20);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('Company');
	}

}
