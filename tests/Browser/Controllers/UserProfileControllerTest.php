<?php

namespace Tests\Browser\Controllers;

use Tests\DuskTestCase;

/**
 * UserProfileController test class.
 * 
 * @author Valuto <tech@valuto.io>
 */
class UserProfileControllerTest extends DuskTestCase
{

    /**
     * Test profile fields existence.
     *
     * @return void
     */
    public function testProfileFieldsExistence()
    {
        $this->login();

        $this->browser
             ->click('#menuAccountItem')
             ->assertVisible('#walletAccount')
             ->assertVisible('#walletPassword')
             ->assertVisible('#walletParticulars')
             ->assertVisible('#first_name')
             ->assertVisible('#last_name')
             ->assertVisible('#address_1')
             ->assertVisible('#address_2')
             ->assertVisible('#zip_code')
             ->assertVisible('#city')
             ->assertVisible('#state')
             ->assertVisible('select#country')
             ->assertVisible('#email');
    }

    /**
     * Assert that fields are actually updated.
     * 
     * @return void
     */
    public function testProfileFieldsUpdate()
    {
        $this->login();

        $newName = base64_encode(random_bytes(10));

        $this->browser
             ->click('#menuAccountItem')
             ->type('#first_name', $newName)
             ->click('.btn-updateprofile')
             ->waitForText('Your profile was updated successfully.')

             // Refresh page.
             ->visit('/')
             ->click('#menuAccountItem')
             ->assertValue('#first_name', $newName);
    }
    
    /**
     * Assert that it fails on invalid e-mails.
     * 
     * @return void
     */
    public function testProfileFieldsUpdateFail()
    {
        $this->login();

        // Create random invalid e-mail.
        $newEmail = str_replace('@', '', base64_encode(random_bytes(10)));

        $this->browser
             ->click('#menuAccountItem')
             ->type('#email', $newEmail)
             ->click('.btn-updateprofile')
             ->pause(2000)
             ->assertDontSee('Your profile was updated successfully.');
    }
}