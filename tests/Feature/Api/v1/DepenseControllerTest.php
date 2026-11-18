<?php

use App\Models\Depense;
use App\Models\User;
use Illuminate\Support\Carbon;

it('returns today expense total for the authenticated user only', function () {
    $this->travelTo(Carbon::parse('2025-01-15 12:00:00'));

    $user = User::factory()->create(['phone' => '+221770000010']);
    $otherUser = User::factory()->create(['phone' => '+221770000011']);

    Depense::create([
        'libelle' => 'Transport',
        'montant' => 150.25,
        'user_id' => $user->id,
    ]);
    Depense::create([
        'libelle' => 'Repas',
        'montant' => 25.75,
        'user_id' => $user->id,
    ]);

    $yesterdayExpense = Depense::create([
        'libelle' => 'Dépense précédente',
        'montant' => 1000,
        'user_id' => $user->id,
    ]);
    $yesterdayExpense->forceFill(['created_at' => now()->subDay()])->save();

    Depense::create([
        'libelle' => 'Dépense autre utilisateur',
        'montant' => 500,
        'user_id' => $otherUser->id,
    ]);

    $response = $this->actingAs($user)->getJson('/api/v1/depenses');

    $response->assertOk()
        ->assertJsonPath('totalDepense', 176)
        ->assertJsonCount(2, 'depenses');
});

it('returns a zero total when the user has no expenses today', function () {
    $user = User::factory()->create(['phone' => '+221770000012']);

    $response = $this->actingAs($user)->getJson('/api/v1/depenses');

    $response->assertOk()
        ->assertJsonPath('totalDepense', 0)
        ->assertJsonCount(0, 'depenses');
});
