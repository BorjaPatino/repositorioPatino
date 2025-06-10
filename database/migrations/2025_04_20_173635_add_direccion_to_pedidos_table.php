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
    Schema::table('pedidos', function (Blueprint $table) {
        $table->string('direccion')->nullable();
        $table->string('ciudad')->nullable();
        $table->string('provincia')->nullable();
        $table->string('codigo_postal')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['direccion', 'ciudad', 'provincia', 'codigo_postal']);
        });
    }
};
