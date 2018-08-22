<?php

namespace Tests\Browser\Controllers\Kyc;

use Tests\DuskTestCase;

/**
 * Kyc\FormController test class.
 * 
 * @author Valuto <tech@valuto.io>
 */
class FormControllerTest extends DuskTestCase
{

    /**
     * Test KYC form is visible.
     *
     * @return void
     */
    public function testFormVisible()
    {
        $this->login(false);

        $this->browser
             ->visit('')
             ->assertSee('Information about you')
             ->assertVisible('.skip-kyc-btn');
    }
}