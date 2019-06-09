<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SetImagickDefault0 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		if (Schema::hasTable('configs')) {
			if (env('DISABLE_IMAGICK', false)) {
				DB::table('configs')
				->where('key', 'imagick')
				->update(['value' => '0']);
			}
		} else {
			echo "Table configs does not exists\n";
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
