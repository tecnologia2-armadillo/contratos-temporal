<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->string('drive_folder_id')->nullable()->after('fecha_fin');
        });
    }

    public function down()
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->dropColumn('drive_folder_id');
        });
    }
};
