<?php

namespace Controllers;

use Models\User;
use Models\Flash;
use Services\ValutoDaemon\Client;
use Services\Tiers\KycCheck;
use Services\Vlumarket\TrendingProducts;

class DashboardController extends Controller
{
    public function index()
    {
        global $mysqli;

        $user = (new User($mysqli))->getUserByUsername($_SESSION['user_session']);

        if ( ! KycCheck::isVerified($user) && ! KycCheck::reminderSkipped()) {
            redirect('/kyc');
        }

        $admin = (!empty($_SESSION['user_admin']) && $_SESSION['user_admin'] == 1);
        $error = array('type' => "none", 'message' => "");

        $noresbal   = $this->client->getBalance();
        $resbalance = $this->client->getBalance() - config('app', 'reserve');
        if ($resbalance < 0) {
            $balance = $noresbal; //Don't show the user a negitive balance if they have no coins with us
        } else {
            $balance = $resbalance;
        }

        $addressList     = $this->client->getAddressList();
        $transactionList = $this->client->getTransactionList();
        $twofactorenabled = isset($_SESSION['user_2fa']) && $_SESSION['user_2fa'];

        $selectedCountryCode = $user['country_code'];

        $vlumarketProducts = TrendingProducts::get();

        (new \Services\Bounty\Signup\User())->showBountyPending($user);

        if ( ! $twofactorenabled && ! Flash::has('showNotice')) {
            Flash::save('showNotice', lang('WALLET_NOTICE_ENABLE_2FA'));
        }

        include __DIR__ . "/../../view/header.php";
        include __DIR__ . "/../../view/wallet.php";
        include __DIR__ . "/../../view/footer.php";
    }
    
    /**
     * Accept disclaimer
     * 
     * @return string
     */
    public function acceptDisclaimer()
    {
        Flash::delete('showdisclaimer');

        return json('success', true);
    }
}