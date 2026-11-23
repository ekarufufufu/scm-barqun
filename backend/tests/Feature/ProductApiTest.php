<?php

namespace Tests\Feature;

use Database\Seeders\AdminSeeder;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionSeeder::class,
            AdminSeeder::class,
        ]);
    }

    public function test_admin_can_manage_products()
    {
        $login = $this->postJson('/api/login', [
            'email' => env('ADMIN_EMAIL'),
            'password' => env('ADMIN_PASSWORD'),
        ]);

        $login->assertOk();

        $headers = [
            'Authorization' => 'Bearer ' . $login->json('access_token'),
        ];

        $create = $this->postJson('/api/product', [
            'nama_barang' => 'Test Product',
            'harga' => 150000,
            'jumlah_stock_tersedia' => 10,
            'deskripsi' => 'Created by test',
        ], $headers);

        $create->assertCreated();

        $productId = $create->json('data.id');

        $this->getJson('/api/product/' . $productId, $headers)
            ->assertOk()
            ->assertJsonPath('data.nama_barang', 'Test Product');

        $this->putJson('/api/product/' . $productId, [
            'nama_barang' => 'Updated Product',
            'harga' => 175000,
            'jumlah_stock_tersedia' => 8,
            'deskripsi' => 'Updated by test',
        ], $headers)->assertOk();

        $this->deleteJson('/api/product/' . $productId, [], $headers)
            ->assertOk();

        $this->assertDatabaseMissing('products', [
            'id' => $productId,
        ]);
    }

    public function test_user_without_permission_cannot_list_products()
    {
        $register = $this->postJson('/api/register', [
            'name' => 'No Role User',
            'email' => 'no.role@example.com',
            'password' => 'password123',
        ]);

        $this->withHeader('Authorization', 'Bearer ' . $register->json('access_token'))
            ->getJson('/api/product')
            ->assertForbidden();
    }
}
