<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyApiKeys extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('CompanyApiKeys', function(Blueprint $table)
		{
			//
            $table->engine = "InnoDB";
			$table->increments('id');
            $table->integer('companyID')->unsigned();
            $table->integer('uploadID')->unsigned();
            $table->text('key'); 
            $table->string('name', 250); 
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
		Schema::drop('CompanyApiKeys');
	}

}
