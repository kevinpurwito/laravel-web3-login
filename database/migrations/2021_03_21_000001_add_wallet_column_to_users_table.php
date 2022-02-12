<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWalletColumnToUsersTable extends Migration
{
	protected function getWalletColumn(): string
	{
		return strval(config('web3.wallet_address_column', 'wallet'));
	}

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (! Schema::hasColumn('users', $this->getWalletColumn())) {
			Schema::table('users', function (Blueprint $table) {
				$table->string($this->getWalletColumn(), 42)->nullable()->index()->after('id');
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if (Schema::hasColumn('users', $this->getWalletColumn())) {
			Schema::table('users', function (Blueprint $table) {
				$table->dropColumn($this->getWalletColumn());
			});
		}
	}
}
