<?php

use App\Configs;
use Illuminate\Database\Migrations\Migration;

class SetPhpScriptNoLimitDefault extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Configs::where('key', 'php_script_no_limit')->update([
			'value' => '1'
		]);
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Configs::where('key', 'php_script_no_limit')->update([
			'value' => '0'
		]);
	}
}
