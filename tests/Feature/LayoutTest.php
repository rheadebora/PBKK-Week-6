<?php

namespace Tests\Feature;

use Tests\TestCase;

class LayoutTest extends TestCase
{
    public function test_shared_layout_loads_persistent_navigation_on_primary_pages(): void
    {
        foreach (['/', '/about', '/projects', '/kalkulator'] as $uri) {
            $this->get($uri)
                ->assertOk()
                ->assertSee('page-navigation.js', false)
                ->assertSee('id="main-content"', false);
        }
    }

    public function test_header_shows_name_and_nrp_instead_of_music_player(): void
    {
        foreach (['/', '/contact', '/mahasiswa/5025241089'] as $uri) {
            $this->get($uri)
                ->assertOk()
                ->assertSee('Rhea Debora Sianturi')
                ->assertSee('5025241089')
                ->assertDontSee('Everything Goes On')
                ->assertDontSee('data-music-player', false)
                ->assertDontSee('soundcloud.com', false);
        }
    }
}
