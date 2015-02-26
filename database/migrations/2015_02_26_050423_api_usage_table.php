<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiUsageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('APIUsage', function(Blueprint $table)
		{
            $table->engine = "InnoDB";
			$table->increments('id');
            $table->integer('uploadID')->unsigned();
            $table->integer('companyID')->unsigned();
			$table->integer('processed')->unsigned();	
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('APIUsage');
	}

}
