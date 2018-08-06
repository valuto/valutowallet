<?php

namespace Tests\Browser\Controllers;

use Tests\DuskTestCase;
use Models\User;

/**
 * UserProfileController test class.
 * 
 * @author Valuto <tech@valuto.io>
 */
class HomeControllerTest extends DuskTestCase
{

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testLoginBox()
    {
        $this->browser->visit('');

        $this->assertTrue($this->browser->element("#loginSection > h1")->getText() === 'Log In');
    }
}