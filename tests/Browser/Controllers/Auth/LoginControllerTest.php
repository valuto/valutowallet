<?php

namespace Tests\Browser\Controllers\Auth;

use Tests\DuskTestCase;

/**
 * LoginController test class.
 * 
 * @author Valuto <tech@valuto.io>
 */
class LoginControllerTest extends DuskTestCase
{
    /**
     * Test login works as expected.
     * 
     * @return void
     */
    public function testLogin()
    {
        $this->login();

        $this->browser
            ->assertPathIs('/')
            ->assertSee('Your Wallet')
            ->assertSee('Last 10 transactions');
    }

    /**
     * Test logout works as expected.
     * 
     * @return void
     */
    public function testLogout()
    {
        $this->login();

        $this->browser
            ->click('#logoutBtn')
            ->assertSee('Create Free Account')
            ->assertSee('Log In')
            ->assertPresent('.navbar-nav > li.active > a[href="/#loginSection"]');
    }
}