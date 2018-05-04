<?php

namespace Controllers;

use Models\User;
use Services\ValutoDaemon\Client;
use Models\Flash;

class DashboardController extends Controller
{
    public function index()
    {
        global $mysqli;

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

        $user = (new User($mysqli))->getUserByUsername($_SESSION['user_session']);

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