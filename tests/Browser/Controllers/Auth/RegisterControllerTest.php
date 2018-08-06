<?php

namespace Tests\Browser\Controllers\Auth;

use Tests\DuskTestCase;
use Models\User;

/**
 * RegisterController test class.
 * 
 * @author Valuto <tech@valuto.io>
 */
class RegisterControllerTest extends DuskTestCase
{
    /**
     * Test login works as expected.
     * 
     * @return void
     */
    public function testRegister()
    {
        $username = base64_encode(random_bytes(10));
        $password = base64_encode(random_bytes(10));

        $this->browser->visit('')

            // Sign up on frontpage.
            ->type('#signupUsername', $username)
            ->type('#signupPassword', $password)
            ->type('#signupPasswordConf', $password)
            ->press('Sign Up')
            ->assertPathIs('/')

            // Disclaimer.
            ->assertVisible('.disclaimer-popup')
            ->assertSee('What is ValutoWallet?')
            ->click('.disclaimer-popup .step1 .footer button')
            ->waitFor('.disclaimer-popup .step2 .footer button')
            ->click('.disclaimer-popup .step2 .footer button')
            ->waitFor('.disclaimer-popup .step3 .footer button')
            ->click('.disclaimer-popup .step3 .footer button')
            ->waitFor('.disclaimer-popup .step4 .footer button')
            ->click('.disclaimer-popup .step4 .footer button')
            ->waitFor('.disclaimer-popup .step5 .footer button')
            ->click('.disclaimer-popup .step5 .footer button')
            ->waitFor('.disclaimer-popup .step6 .footer button')
            ->click('.disclaimer-popup .step6 .footer button')
            ->waitUntilMissing('.disclaimer-popup')

            // User is now seeing account-tab.
            ->assertSee('Two-factor authentication is an extra layer of security for your wallet account')
            ->assertSee('Your Wallet')
            ->assertSee('Last 10 transactions');
    }
}