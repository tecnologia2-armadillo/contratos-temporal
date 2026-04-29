<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Eliminar contrato_firmado y contrato_src de personal (vinculado)
        Schema::table('personal', function (Blueprint $table) {
            $table->dropColumn(['contrato_firmado', 'contrato_src']);
        });

        // Eliminar contrato_src de personal_no_vinculado
        Schema::table('personal_no_vinculado', function (Blueprint $table) {
            $table->dropColumn('contrato_src');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('personal', function (Blueprint $table) {
            $table->boolean('contrato_firmado')->default(false);
            $table->string('contrato_src', 500)->nullable();
        });

        Schema::table('personal_no_vinculado', function (Blueprint $table) {
            $table->string('contrato_src', 500)->nullable();
        });
    }
};
