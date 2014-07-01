<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (Schema::hasTable('modules')) {
			return false;
		}
		
		Schema::create('modules', function($table) {
			$table->increments('id');
			$table->string('code', 255);
			$table->string('name', 255);
			$table->float('price');
			$table->text('image');
			$table->text('download_path');
			$table->string('category', 255);
			$table->text('pay_description');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('modules');
	}

}
