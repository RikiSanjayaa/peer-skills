<?php

/**
 * @mixin \Tests\TestCase
 * @mixin \Illuminate\Foundation\Testing\TestCase
 */

use App\Models\Category;
use App\Models\Seller;

/** @var \Tests\TestCase $this */

describe('Admin Dashboard', function () {

    it('requires admin authentication', function () {
        $user = createUser();

        $response = $this->actingAs($user)->get('/admin/dashboard');

        // Middleware redirects non-admins
        $response->assertRedirect();
    });

    it('allows admin to access dashboard', function () {
        $admin = createAdmin();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    });
});

describe('Admin Category Management', function () {

    it('shows category list', function () {
        $admin = createAdmin();
        $category = createCategory();

        $response = $this->actingAs($admin)->get('/admin/categories');

        $response->assertStatus(200);
        $response->assertSee($category->name);
    });

    it('allows admin to create category', function () {
        $admin = createAdmin();

        $response = $this->actingAs($admin)->post('/admin/categories', [
            'name' => 'New Category',
            'slug' => 'new-category',
            'description' => 'A new category description',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'New Category']);
    });

    it('allows admin to update category', function () {
        $admin = createAdmin();
        $category = createCategory();

        $response = $this->actingAs($admin)->put('/admin/categories/' . $category->id, [
            'name' => 'Updated Category',
            'slug' => $category->slug,
            'description' => 'Updated description',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
        ]);
    });

    it('allows admin to delete category', function () {
        $admin = createAdmin();
        $category = createCategory();
        $categoryId = $category->id;

        $response = $this->actingAs($admin)->delete('/admin/categories/' . $category->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $categoryId]);
    });
});

describe('Admin User Management', function () {

    it('shows user list', function () {
        $admin = createAdmin();
        $user = createUser();

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee($user->name);
    });

    it('allows admin to delete user', function () {
        $admin = createAdmin();
        $user = createUser();
        $userId = $user->id;

        $response = $this->actingAs($admin)->delete('/admin/users/' . $user->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    });
});

describe('Admin Seller Requests', function () {

    it('shows seller request list', function () {
        $admin = createAdmin();
        $user = createBuyer();
        $seller = Seller::create([
            'user_id' => $user->id,
            'business_name' => 'Pending Seller ' . uniqid(),
            'description' => 'Description',
            'major' => 'CS',
            'university' => 'Test Uni',
            'skills' => ['PHP'],
            'status' => Seller::STATUS_PENDING,
        ]);

        $response = $this->actingAs($admin)->get('/admin/seller-requests');

        $response->assertStatus(200);
        $response->assertSee($seller->business_name);
    });

    it('allows admin to approve seller', function () {
        $admin = createAdmin();
        $user = createBuyer();
        $seller = Seller::create([
            'user_id' => $user->id,
            'business_name' => 'Pending Seller ' . uniqid(),
            'description' => 'Description',
            'major' => 'CS',
            'university' => 'Test Uni',
            'skills' => ['PHP'],
            'status' => Seller::STATUS_PENDING,
        ]);

        $response = $this->actingAs($admin)->patch('/admin/seller-requests/' . $seller->id . '/approve');

        $response->assertRedirect();
        $seller->refresh();
        expect($seller->status)->toBe(Seller::STATUS_APPROVED);
    });

    it('allows admin to reject seller', function () {
        $admin = createAdmin();
        $user = createBuyer();
        $seller = Seller::create([
            'user_id' => $user->id,
            'business_name' => 'Pending Seller ' . uniqid(),
            'description' => 'Description',
            'major' => 'CS',
            'university' => 'Test Uni',
            'skills' => ['PHP'],
            'status' => Seller::STATUS_PENDING,
        ]);

        $response = $this->actingAs($admin)->post('/admin/seller-requests/' . $seller->id . '/reject', [
            'rejection_reason_type' => 'incomplete_description',
        ]);

        $response->assertRedirect();
        $seller->refresh();
        expect($seller->status)->toBe(Seller::STATUS_REJECTED);
    });

    it('allows admin to suspend seller', function () {
        $admin = createAdmin();
        ['seller' => $seller] = createSeller();

        $response = $this->actingAs($admin)->post('/admin/seller-requests/' . $seller->id . '/suspend', [
            'suspension_reason' => 'Inappropriate content detected',
        ]);

        $response->assertRedirect();
        $seller->refresh();
        expect($seller->status)->toBe(Seller::STATUS_SUSPENDED);
    });

    it('allows admin to reactivate seller', function () {
        $admin = createAdmin();
        $user = createBuyer();
        $seller = Seller::create([
            'user_id' => $user->id,
            'business_name' => 'Suspended Seller ' . uniqid(),
            'description' => 'Description',
            'major' => 'CS',
            'university' => 'Test Uni',
            'skills' => ['PHP'],
            'status' => Seller::STATUS_SUSPENDED,
        ]);

        $response = $this->actingAs($admin)->patch('/admin/seller-requests/' . $seller->id . '/reactivate');

        $response->assertRedirect();
        $seller->refresh();
        expect($seller->status)->toBe(Seller::STATUS_APPROVED);
    });
});
