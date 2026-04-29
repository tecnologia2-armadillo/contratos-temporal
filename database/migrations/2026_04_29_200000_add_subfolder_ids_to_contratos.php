<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->string('drive_personal_folder_id')->nullable()->after('drive_folder_id');
            $table->string('drive_nv_folder_id')->nullable()->after('drive_personal_folder_id');
        });
    }

    public function down()
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->dropColumn(['drive_personal_folder_id', 'drive_nv_folder_id']);
        });
    }
};
