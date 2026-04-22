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
    public function up(): void
    {
        // En PostgreSQL necesitamos activar la extensión pgcrypto para generar UUIDs
        DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto"');

        // Establecer el valor por defecto como un UUID aleatorio
        DB::statement('ALTER TABLE personal ALTER COLUMN signature_token SET DEFAULT gen_random_uuid()');

        // Poblar los registros que actualmente tengan el token nulo o vacío
        DB::statement("UPDATE personal SET signature_token = gen_random_uuid() WHERE signature_token IS NULL OR signature_token = ''");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE personal ALTER COLUMN signature_token DROP DEFAULT');
    }
};
