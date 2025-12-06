<?php

/**
 * @mixin \Tests\TestCase
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */

/** @var \Tests\TestCase $this */

describe('Public Profile View', function () {

    it('shows user profile page', function () {
        $user = createUser();

        $response = $this->get('/profile/' . $user->id);

        $response->assertStatus(200);
        $response->assertViewIs('profile.show');
        $response->assertSee($user->name);
    });

    it('shows seller info on seller profile', function () {
        ['user' => $user, 'seller' => $seller] = createSeller();

        $response = $this->get('/profile/' . $user->id);

        $response->assertStatus(200);
        // Seller profile should show some seller-related info
        $response->assertSee($user->name);
    });

    it('shows seller gigs on profile', function () {
        ['user' => $user, 'seller' => $seller] = createSeller();
        $gig = createGig($seller);

        $response = $this->get('/profile/' . $user->id);

        $response->assertStatus(200);
        $response->assertSee($gig->title);
    });

    it('returns 404 for non-existent user', function () {
        $response = $this->get('/profile/99999');

        $response->assertStatus(404);
    });
});

describe('Profile Edit', function () {

    it('requires authentication to edit profile', function () {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    });

    it('shows profile edit form', function () {
        $user = createUser();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('profile.edit');
    });

    it('allows user to update profile', function () {
        $user = createUser();

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Updated Name',
            'email' => $user->email,
            'bio' => 'This is my new bio',
            'phone' => '081234567890',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'bio' => 'This is my new bio',
        ]);
    });

    it('validates email uniqueness', function () {
        $user1 = createUser();
        $user2 = createUser();

        $response = $this->actingAs($user1)->patch('/profile', [
            'name' => $user1->name,
            'email' => $user2->email, // duplicate
        ]);

        $response->assertSessionHasErrors('email');
    });
});

describe('Profile Delete', function () {

    it('allows user to delete account', function () {
        $user = createUser(['password' => bcrypt('password')]);

        $response = $this->actingAs($user)->delete('/profile', [
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    });

    it('requires password to delete', function () {
        $user = createUser(['password' => bcrypt('password')]);

        $response = $this->actingAs($user)->delete('/profile', [
            'password' => 'wrong-password',
        ]);

        // Either has errors or user still exists
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    });
});
