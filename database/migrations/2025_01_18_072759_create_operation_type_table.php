<?php

use App\Models\Vente;
use App\Models\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add s or remove s to the table name
        Schema::create('operation_type', function (Blueprint $table) {
            $table->uuid('type_id');
            $table->foreign('type_id')
            ->references('id')
            ->on('types')
            ->onDelete('cascade');

            $table->uuid('operation_id');
            $table->foreign('operation_id')
            ->references('id')
            ->on('operations')
            ->onDelete('cascade');

            $table->primary(['type_id', 'operation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_type');
    }
};
