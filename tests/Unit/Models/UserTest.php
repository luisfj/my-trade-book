<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;// RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use  DatabaseTransactions;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateUser()
    {
        $user = factory(User::class)->create([
            'name'  => 'testeMock',
            'email' => 'teste_mock@mockteste.com.xy'
        ]);
        $this->assertNotNull($user);
        $this->assertEquals($user->name, 'testeMock');
        $this->assertEquals($user->email, 'teste_mock@mockteste.com.xy');
        $this->assertDatabaseHas('users', [
            'name' => 'testeMock',
            'email' => 'teste_mock@mockteste.com.xy'
        ]);
    }
}
