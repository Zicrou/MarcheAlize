<?php

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
        Schema::create('team_invitations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('team_id');
            $table->foreign('team_id')
            ->references('id')
            ->on('teams')
            ->onDelete('cascade');
            $table->uuid('invited_by');
            $table->foreign('invited_by')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
            $table->uuid('user_id');
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
            $table->enum('status', ['en attente', 'accepter', 'refuser'])->default('en attente'); // pending, accepted, declined
// pending, accepted, declined
            $table->timestamp('expires_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['team_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_invitations');
    }
};
