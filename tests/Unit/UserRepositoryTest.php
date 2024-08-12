<?php

namespace Tests\Unit;

use App\Models\User;
use App\Http\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase; // Use this trait to reset the database between tests

    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
    }

    public function test_all_method()
    {
        $user = User::factory()->create();

        $filters = ['id' => $user->id];
        $result = $this->userRepository->all($filters);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals($user->id, $result->items()[0]->id);
    }

    public function test_find_method()
    {
        $user = User::factory()->create();

        $result = $this->userRepository->find($user->id);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->id, $result->id);
    }

    public function test_findWhere_method()
    {
        $user = User::factory()->create();

        $result = $this->userRepository->findWhere('email', $user->email);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($user->email, $result->email);
    }

    public function test_create_method()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ];

        $result = $this->userRepository->create($data);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($data['email'], $result->email);
    }

    public function test_update_method()
    {
        $user = User::factory()->create();

        $data = ['name' => 'Jane Doe'];
        $result = $this->userRepository->update($data, $user->id);

        $this->assertEquals('Jane Doe', $result->name);
    }

    public function test_delete_method()
    {
        $user = User::factory()->create();

        $result = $this->userRepository->delete($user->id);

        $this->assertEquals(1, $result);
        $this->assertNull(User::find($user->id));
    }

    public function test_check_method()
    {
        $user = User::factory()->create();

        $result = $this->userRepository->check('email', $user->email);

        $this->assertTrue($result);
    }

    public function test_getMe_method()
    {
        $user = User::factory()->create();
        auth()->login($user);

        $result = $this->userRepository->getMe();

        $this->assertEquals($user->id, $result->id);
    }
}
