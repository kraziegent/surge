<?php

namespace Tests\Feature;

use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function registration_page_contains_livewire_component() {
        $this->get('/register')->assertSeeLivewire('auth.register');
    }

    /** @test */
    function can_register() {

        Livewire::test('auth.register')
        ->set('name','Peter')
        ->set('email','olumolu@gmail.com')
        ->set('password','secret')
        ->set('passwordConfirmation','secret')
        ->call('register')
        ->assertRedirect('/dashboard');

        $this->assertTrue(User::whereEmail('olumolu@gmail.com')->exists());
        $this->assertEquals('olumolu@gmail.com', auth()->user()->email);
    }

    /** @test */
    function email_is_required() {

        Livewire::test('auth.register')
        ->set('name','Peter')
        ->set('email','')
        ->set('password','secret')
        ->set('passwordConfirmation','secret')
        ->call('register')
        ->assertHasErrors(['email' => 'required']);
    }

    /** @test */
    function email_is_valid() {

        Livewire::test('auth.register')
        ->set('name','Peter')
        ->set('email','boom')
        ->set('password','secret')
        ->set('passwordConfirmation','secret')
        ->call('register')
        ->assertHasErrors(['email' => 'email']);
    }

    /** @test */
    function email_hasnt_been_taken() {

        User::Create([
            'name' => 'Peter O',
            'email' => 'olumolu@gmail.com',
            'password' => Hash::make('password')
        ]);

        Livewire::test('auth.register')
        ->set('name','Peter')
        ->set('email','olumolu@gmail.com')
        ->set('password','secret')
        ->set('passwordConfirmation','secret')
        ->call('register')
        ->assertHasErrors(['email' => 'unique']);
    }

    /** @test */
    function see_email_hasnt_been_taken_with_livewire() {

        User::Create([
            'name' => 'Peter O',
            'email' => 'olumolu@gmail.com',
            'password' => Hash::make('password')
        ]);

        Livewire::test('auth.register')
        ->set('email','olumol@gmail.com')
        ->assertHasNoErrors()
        ->set('email','olumolu@gmail.com')
        ->assertHasErrors(['email' => 'unique']);
    }

    /** @test */
    function password_is_required() {

        Livewire::test('auth.register')
        ->set('name','Peter')
        ->set('email','')
        ->set('password','')
        ->set('passwordConfirmation','secret')
        ->call('register')
        ->assertHasErrors(['password' => 'required']);
    }

    /** @test */
    function password_is_more_than_6_char() {

        Livewire::test('auth.register')
        ->set('name','Peter')
        ->set('email','')
        ->set('password','secre')
        ->set('passwordConfirmation','secret')
        ->call('register')
        ->assertHasErrors(['password' => 'min']);
    }

    /** @test */
    function password_is_same_as_confirmation() {

        Livewire::test('auth.register')
        ->set('name','Peter')
        ->set('email','')
        ->set('password','secret')
        ->set('passwordConfirmation','secret1')
        ->call('register')
        ->assertHasErrors(['password' => 'same']);
    }
}
