<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReferenceCodeRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_uses_personal_reference_code_only(): void
    {
        $referenceUser = User::factory()->create([
            'username' => 'parent',
            'name' => 'Parent',
            'reference_code' => 'REF123',
            'status' => 'active',
        ]);

        $response = $this->post('/user-registeration', [
            'username' => 'registered-user',
            'email' => 'registered@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'wallet-password' => 'wallet-password',
            'refrence-code' => $referenceUser->reference_code,
        ]);

        $response->assertRedirect('/user-login');
        $this->assertDatabaseHas('users', [
            'username' => 'registered-user',
            'name' => 'registered-user',
            'parent_id' => $referenceUser->id,
        ]);
        $this->assertDatabaseHas('funds', [
            'user_id' => User::where('username', 'registered-user')->value('id'),
            'amount' => 15,
            'type' => 'deposit',
        ]);
        $this->assertNotSame($referenceUser->reference_code, User::where('username', 'registered-user')->value('reference_code'));
    }

    public function test_registration_rejects_missing_reference_code(): void
    {
        $response = $this->from('/user-register')->post('/user-registeration', [
            'name' => 'Blocked Registration',
            'password' => 'password',
            'password_confirmation' => 'password',
            'wallet-password' => 'wallet-password',
        ]);

        $response->assertSessionHasErrors('refrence-code');
        $this->assertDatabaseMissing('users', ['username' => 'Blocked Registration']);
    }
}
