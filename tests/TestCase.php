<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Base test case for the application.
 *
 * @method \Illuminate\Testing\TestResponse get(string $uri, array $headers = [])
 * @method \Illuminate\Testing\TestResponse getJson(string $uri, array $headers = [])
 * @method \Illuminate\Testing\TestResponse post(string $uri, array $data = [], array $headers = [])
 * @method \Illuminate\Testing\TestResponse postJson(string $uri, array $data = [], array $headers = [])
 * @method \Illuminate\Testing\TestResponse put(string $uri, array $data = [], array $headers = [])
 * @method \Illuminate\Testing\TestResponse putJson(string $uri, array $data = [], array $headers = [])
 * @method \Illuminate\Testing\TestResponse patch(string $uri, array $data = [], array $headers = [])
 * @method \Illuminate\Testing\TestResponse patchJson(string $uri, array $data = [], array $headers = [])
 * @method \Illuminate\Testing\TestResponse delete(string $uri, array $data = [], array $headers = [])
 * @method \Illuminate\Testing\TestResponse deleteJson(string $uri, array $data = [], array $headers = [])
 * @method $this actingAs(\Illuminate\Contracts\Auth\Authenticatable $user, string|null $guard = null)
 * @method void assertAuthenticated(string|null $guard = null)
 * @method void assertGuest(string|null $guard = null)
 * @method void assertAuthenticatedAs(\Illuminate\Contracts\Auth\Authenticatable $user, string|null $guard = null)
 * @method void assertDatabaseHas(string|\Illuminate\Database\Eloquent\Model $table, array $data, string|null $connection = null)
 * @method void assertDatabaseMissing(string|\Illuminate\Database\Eloquent\Model $table, array $data, string|null $connection = null)
 * @method void assertDatabaseCount(string|\Illuminate\Database\Eloquent\Model $table, int $count, string|null $connection = null)
 * @method void assertSoftDeleted(string|\Illuminate\Database\Eloquent\Model $table, array $data = [], string|null $connection = null)
 * @method void assertModelExists(\Illuminate\Database\Eloquent\Model $model)
 * @method void assertModelMissing(\Illuminate\Database\Eloquent\Model $model)
 */
abstract class TestCase extends BaseTestCase
{
    //
}
