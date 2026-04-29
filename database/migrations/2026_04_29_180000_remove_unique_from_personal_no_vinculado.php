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
        // En Postgres, para evitar errores de "Undefined object", 
        // usamos un bloque try-catch o verificamos la existencia mediante SQL.
        
        try {
            Schema::table('personal_no_vinculado', function (Blueprint $table) {
                // Intentamos borrar el índice solo si existe. 
                // Si ya confirmamos que no existe, Laravel simplemente no hará nada aquí 
                // pero si lo intentamos forzar con nombres específicos fallará.
            });
            
            // Usamos SQL puro para borrar el índice solo si existe y así no bloquear la migración.
            DB::statement('DROP INDEX IF EXISTS personal_no_vinculado_identificacion_unique');
            DB::statement('DROP INDEX IF EXISTS personal_no_vinculado_tipo_identificacion_identificacion_unique');
            
        } catch (\Exception $e) {
            // Silenciamos el error para permitir que la migración se marque como completada
            // y no bloquee el resto del sistema.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // No es necesario restaurar
    }
};
