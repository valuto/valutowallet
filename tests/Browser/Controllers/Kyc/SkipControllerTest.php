<?php

namespace Tests\Browser\Controllers\Kyc;

use Tests\DuskTestCase;

/**
 * Kyc\SkipController test class.
 * 
 * @author Valuto <tech@valuto.io>
 */
class SkipControllerTest extends DuskTestCase
{

    /**
     * Test skip button is visible.
     *
     * @return void
     */
    public function testSkipButton()
    {
        $this->login(false);

        $this->browser
             ->visit('')
             ->assertSee('Information about you')
             ->assertVisible('.skip-kyc-btn')
             ->click('.skip-kyc-btn')
             ->waitFor('#walletOverview');
    }
    

    /**
     * Test skip button is only available 2 times.
     *
     * @return void
     */
    public function testSkipButtonAvailability()
    {
        list($user, $username, $password) = $this->login(false);

        $this->browser
             ->visit('')
             ->assertSee('Information about you')
             ->assertVisible('.skip-kyc-btn')
             ->click('.skip-kyc-btn')
             ->waitFor('#walletOverview')
             ->click('#logoutBtn')

             // Relogin
             ->waitForText('Create Free Account')
             ->type('#loginUsername', $username)
             ->type('#loginPassword', $password)
             ->press('Log In')

             // See KYC second time.
             ->assertSee('Information about you')
             ->assertVisible('.skip-kyc-btn')
             ->click('.skip-kyc-btn')
             ->waitFor('#walletOverview')
             ->click('#logoutBtn')
             
             // Relogin
             ->waitForText('Create Free Account')
             ->type('#loginUsername', $username)
             ->type('#loginPassword', $password)
             ->press('Log In')
             
             // See KYC third time. Assert skip button is no longer available.
             ->assertSee('Information about you')
             ->assertSee(lang('FORM_KYC_USER_BLOCKED'))
             ->assertMissing('.skip-kyc-btn');
             


    }
}