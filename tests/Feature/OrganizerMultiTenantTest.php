<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Tests\TestCase;

class OrganizerMultiTenantTest extends TestCase
{
    public function test_organizer_login_redirects_to_organizer_dashboard(): void
    {
        $organization = Organization::create([
            'name' => 'HIMA Teknik Informatika',
            'slug' => 'hima-teknik-informatika',
            'description' => 'Organisasi test',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'role' => 'organizer',
            'organization_id' => $organization->id,
        ]);

        $response = $this->post('/admin/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.organizer.dashboard'));
    }

    public function test_authenticated_user_can_register_as_organizer_and_create_organization(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->post('/organizer/register', [
            'name' => 'HIMA Sisteminformasi',
            'slug' => 'hima-sisteminformasi',
            'description' => 'Organisasi baru',
        ]);

        $response->assertRedirect(route('admin.organizer.dashboard'));
        $this->assertDatabaseHas('organizations', [
            'name' => 'HIMA Sisteminformasi',
            'slug' => 'hima-sisteminformasi',
            'owner_id' => $user->id,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'organizer',
        ]);
    }
}
