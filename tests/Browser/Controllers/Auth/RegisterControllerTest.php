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
            ->assertPathIs('/kyc')

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

            // Fill out KYC form.
            ->assertSee('Information about you')
            ->type('#first_name', 'First name')
            ->type('#last_name', 'Last name')
            ->type('#address_1', 'Address 1')
            ->type('#zip_code', '4200')
            ->type('#city', 'Slagelse')
            ->select('#country', 'DK')
            ->type('#email', 'test@valuto.io')
            ->type('#phone_number', '88888888')
            ->click('.btn-updateprofile')

            // User is now seeing account-tab.
            ->waitFor('#walletOverview')
            ->assertSee('Two-factor authentication is an extra layer of security for your wallet account')
            ->assertSee('Your Wallet')
            ->assertSee('Last 10 transactions');
    }
}