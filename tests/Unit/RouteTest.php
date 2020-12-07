<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class RouteTest extends TestCase
{
	use WithoutMiddleware, HasFactory;

    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function testLogin()
    {
    	
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');


    }

    public function testHome()
    {
    	
        $response = $this->get('/home');

        $response->assertStatus(200);
        $response->assertViewIs('home');


    }

    public function testPosts()
    {
	    $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/posts');

        $response->assertStatus(200);
        $response->assertViewIs('posts');


    }

    

    public function testProfile()
    {
	    $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertViewIs('profile');


    }

    
    public function testFollowing()
    {
	    $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/following');

        $response->assertStatus(200);
        $response->assertViewIs('follows');


    }

    public function testFollowers()
    {
	    $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/followers');

        $response->assertStatus(200);
        $response->assertViewIs('follows');


    }

    public function testUsers()
    {
	    $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
        $response->assertViewIs('users');
    	
    }

}
