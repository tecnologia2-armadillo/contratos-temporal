<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('personal_no_vinculado', function (Blueprint $table) {
            // Intentamos eliminar índices únicos que podrían estar bloqueando duplicados.
            // Los nombres de los índices suelen ser el nombre de la tabla + nombre de columna + '_unique'
            
            try {
                $table->dropUnique('personal_no_vinculado_identificacion_unique');
            } catch (\Exception $e) {}

            try {
                $table->dropUnique(['tipo_identificacion', 'identificacion']);
            } catch (\Exception $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // No es necesario restaurar si queremos permitir duplicados permanentemente
    }
};
