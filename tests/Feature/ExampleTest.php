<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_guest_is_sent_to_the_login_page(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_login_page_loads(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_register_page_loads(): void
    {
        $this->get('/register')->assertOk();
    }

    public function test_health_check_passes(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_version_endpoint_reports_a_release(): void
    {
        $this->getJson('/version')
            ->assertOk()
            ->assertJsonStructure(['version', 'commit']);
    }

    public function test_private_pages_require_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/entries')->assertRedirect('/login');
        $this->get('/requests')->assertRedirect('/login');
    }
}