<?php

use App\Models\Operation;
use App\Models\Produit;
use App\Models\Team;
use App\Models\Type;
use App\Models\User;

it('decrements the selected product stock on the first API submission', function () {
    $team = Team::create(['name' => 'Operation test team']);
    $user = User::factory()->create([
        'phone' => '+221770000002',
        'team_id' => $team->id,
    ]);

    $firstProduct = Produit::create([
        'designation' => 'First product',
        'nombre' => 10,
        'montant' => 100,
        'user_id' => $user->id,
    ]);
    $selectedProduct = Produit::create([
        'designation' => 'Selected product',
        'nombre' => 8,
        'montant' => 200,
        'user_id' => $user->id,
    ]);
    $type = Type::create(['name' => 'Vente']);

    $response = $this->actingAs($user)->postJson('/api/v1/operations', [
        'nombre' => 3,
        'prix' => 10,
        'produit_id' => $selectedProduct->id,
        'types' => $type->id,
    ]);

    $response->assertOk()
        ->assertJsonPath('success', 'L\'opération a été créée');

    expect($firstProduct->fresh()->nombre)->toBe(10);
    expect($selectedProduct->fresh()->nombre)->toBe(5);

    $operation = Operation::query()->sole();
    $this->assertModelExists($operation);
    expect($operation->produit_id)->toBe($selectedProduct->id);
});
