<?php

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
