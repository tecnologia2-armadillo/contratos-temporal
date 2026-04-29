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
        Schema::create('contrato_personal_no_vinculado', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contrato_id');
            $table->unsignedBigInteger('personal_no_vinculado_id');
            $table->string('ip_firma')->nullable()->comment('IP desde donde se realizó la firma del contrato');
            $table->timestamps();

            $table->foreign('contrato_id')
                  ->references('id')
                  ->on('contratos')
                  ->onDelete('cascade');

            $table->foreign('personal_no_vinculado_id')
                  ->references('id')
                  ->on('personal_no_vinculado')
                  ->onDelete('cascade');

            $table->unique(['contrato_id', 'personal_no_vinculado_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contrato_personal_no_vinculado');
    }
};
