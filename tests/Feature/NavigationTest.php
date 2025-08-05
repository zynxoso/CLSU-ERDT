<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NavigationTest extends TestCase
{
    /**
     * Test that the homepage loads with navigation component
     */
    public function test_homepage_loads_with_navigation()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        
        // Check for mobile menu button
        $response->assertSee('mobile-menu-button', false);
        
        // Check for mobile menu container
        $response->assertSee('mobile-menu', false);
        
        // Check for navigation links
        $response->assertSee('Home');
        $response->assertSee('How to Apply');
        $response->assertSee('About');
        $response->assertSee('History');
    }
    
    /**
     * Test that navigation component has proper accessibility attributes
     */
    public function test_navigation_has_accessibility_attributes()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        
        // Check for ARIA attributes
        $response->assertSee('aria-label="Toggle mobile menu"', false);
        $response->assertSee('aria-expanded="false"', false);
        $response->assertSee('aria-controls="mobile-menu"', false);
        $response->assertSee('role="menu"', false);
    }
    
    /**
     * Test that mobile menu has proper touch target sizes
     */
    public function test_mobile_menu_has_proper_touch_targets()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        
        // Check for minimum touch target classes
        $response->assertSee('min-h-[44px]', false);
        $response->assertSee('min-w-[44px]', false);
    }
    
    /**
     * Test that JavaScript API is available
     */
    public function test_mobile_menu_javascript_api_exists()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        
        // Check for JavaScript API
        $response->assertSee('window.mobileMenuAPI', false);
        $response->assertSee('reinitialize:', false);
        $response->assertSee('close:', false);
        $response->assertSee('isOpen:', false);
    }
}