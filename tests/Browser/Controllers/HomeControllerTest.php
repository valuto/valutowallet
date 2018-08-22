<?php

namespace Tests\Browser\Controllers;

use Tests\DuskTestCase;

/**
 * HomeController test class.
 * 
 * @author Valuto <tech@valuto.io>
 */
class HomeControllerTest extends DuskTestCase
{

    /**
     * Test frontpage elements.
     *
     * @return void
     */
    public function testElements()
    {
        $this->browser
             ->visit('')
             ->assertSee('Log In')
             ->assertSee('Create Free Account');
    }
}