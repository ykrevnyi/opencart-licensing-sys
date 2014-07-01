<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function($table) {
			$table->increments('id');
			
			$table->string('ik_co_id', 255);
			$table->string('ik_co_prs_id', 255);
			$table->string('ik_inv_id', 255);
			$table->string('ik_inv_st', 255);
			$table->string('ik_inv_crt', 255);
			$table->string('ik_inv_prc', 255);
			$table->string('ik_trn_id', 255);
			$table->string('ik_pm_no', 255);
			$table->string('ik_desc', 255);
			$table->string('ik_pw_via', 255);
			$table->string('ik_am', 255);
			$table->string('ik_cur', 255);
			$table->string('ik_co_rfn', 255);
			$table->string('ik_ps_price', 255);
			$table->string('ik_sign', 255);

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
		Schema::drop('transactions');
	}

}
