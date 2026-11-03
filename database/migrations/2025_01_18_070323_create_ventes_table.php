<?php

use App\Models\Produit;
use App\Models\User;
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
        Schema::create('ventes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('designation')->nullable();
            $table->integer('nombre');
            $table->integer('prix');
            //$table->boolean("statut")->default(true); // For deleteting update it to false
            $table->string('image')->nullable();
            $table->uuid('produit_id')->nullable();
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('cascade');
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
