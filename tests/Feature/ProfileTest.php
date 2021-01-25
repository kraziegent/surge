<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_see_livewire_profile_component_on_profile_page() {

        $user = \App\Models\User::factory(1)->create();

        $this->actingAs($user->first())
        ->get('/profile')
        ->assertSuccessful()
        ->assertSeeLivewire('profile');
    }
}
