<?php

namespace Tests\Browser\Controllers;

use Tests\DuskTestCase;

/**
 * WalletController test class.
 * 
 * @author Valuto <tech@valuto.io>
 */
class WalletControllerTest extends DuskTestCase
{

    /**
     * Test withdraw failure.
     *
     * @return void
     */
    public function testWithdrawInsufficientFundsFail()
    {
        $this->login();

        $address = 'VQDRLn64CG3GGbSeoJuyD8qgADpnyomr94';
        $amount = '10';

        $this->browser
             ->type('#address', $address)
             ->type('#amount', $amount)
             ->click('#withdrawBtn')
             ->assertDialogOpened("Are you sure, you want to send " . $amount . " VLU to \"" . $address . "\"?\n\nThis action cannot be undone.")
             ->acceptDialog()
             ->waitForText('Withdrawal amount exceeds your wallet balance. Please note the wallet owner has set a reserve fee of ' . config('app', 'reserve') . ' VLU');
    }
}