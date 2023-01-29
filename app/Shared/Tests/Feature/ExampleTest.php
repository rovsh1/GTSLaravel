<?php

namespace GTS\Shared\Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use GTS\Shared\Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
