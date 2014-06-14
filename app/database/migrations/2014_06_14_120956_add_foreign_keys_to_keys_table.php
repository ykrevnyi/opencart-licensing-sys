<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToKeysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('keys', function($table) {
			$table->foreign('module_code')
				->references('code')->on('modules');

			$table->foreign('module_type')
				->references('id')->on('module_type');

			$table->foreign('transaction_id')
				->references('id')->on('transactions');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('keys', function($table) {
			$table->dropForeign('modules_module_code_foreign');

			$table->dropForeign('module_type_module_type_foreign');

			$table->dropForeign('transactions_transaction_id_foreign');
		});
	}

}
