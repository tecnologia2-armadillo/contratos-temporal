<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_personal', function (Blueprint $table) {
            $table->string('contrato_src')->nullable()->after('ip_firma')
                  ->comment('URL del PDF del contrato firmado en Google Drive');
        });

        Schema::table('contrato_personal_no_vinculado', function (Blueprint $table) {
            $table->string('contrato_src')->nullable()->after('ip_firma')
                  ->comment('URL del PDF del contrato firmado en Google Drive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrato_personal', function (Blueprint $table) {
            $table->dropColumn('contrato_src');
        });

        Schema::table('contrato_personal_no_vinculado', function (Blueprint $table) {
            $table->dropColumn('contrato_src');
        });
    }
};
