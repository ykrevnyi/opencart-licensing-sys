<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('keys', function($table) {
			$table->increments('id');

			$table->string('key', 255)->unique()->nullable();
			$table->text('domain');
			$table->string('module_code', 255);
			$table->integer('module_type')->unsigned();
			$table->integer('transaction_id')->unsigned()->nullable();

			$table->tinyInteger('active');

			$table->timestamp('expired_at');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('keys');
	}

}
