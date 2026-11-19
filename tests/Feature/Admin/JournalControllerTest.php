<?php

use App\Models\Depense;
use App\Models\Operation;
use App\Models\Team;
use App\Models\User;

it('renders journal operations when an operation exists', function () {
    $user = User::factory()->create(['phone' => '+221770000001']);
    $team = Team::create(['name' => 'Journal test team']);

    Operation::create([
        'designation' => 'Journal test operation',
        'nombre' => 2,
        'prix' => 10,
        'user_id' => $user->id,
        'team_id' => $team->id,
    ]);

    $response = $this->actingAs($user)->get(route('admin.journal.index'));

    $response->assertOk()
        ->assertSee('Journal test operation');
});

it('paginates expenses independently from operations', function () {
    $user = User::factory()->create(['phone' => '+221770000002']);
    $team = Team::create(['name' => 'Journal pagination test team']);

    foreach (range(1, 6) as $number) {
        $createdAt = now()->addSeconds($number);

        $depense = Depense::create([
            'libelle' => "Journal test expense {$number}",
            'montant' => $number * 10,
            'user_id' => $user->id,
        ]);
        $depense->created_at = $createdAt;
        $depense->save();

        $operation = Operation::create([
            'designation' => "Journal test operation {$number}",
            'nombre' => 1,
            'prix' => 10,
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);
        $operation->created_at = $createdAt;
        $operation->save();
    }

    $response = $this->actingAs($user)->get(route('admin.journal.index', [
        'depenses_page' => 2,
    ]));

    $response->assertOk()
        ->assertSee('Journal test expense 1')
        ->assertDontSee('Journal test expense 2')
        ->assertSee('Journal test operation 6')
        ->assertDontSee('Journal test operation 1');
});
