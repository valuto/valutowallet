<?php

namespace Controllers;

use Models\Client;
use Models\Flash;

class DashboardController extends Controller
{

    public function index()
    {
        global $mysqli;

        $admin        = false;
        if (!empty($_SESSION['user_admin']) && $_SESSION['user_admin'] == 1) {
            $admin = true;
        }
        $error        = array('type' => "none", 'message' => "");

        $noresbal   = $this->client->getBalance($_SESSION['user_session']);
        $resbalance = $this->client->getBalance($_SESSION['user_session']) - config('app', 'reserve');
        if ($resbalance < 0) {
            $balance = $noresbal; //Don't show the user a negitive balance if they have no coins with us
        } else {
            $balance = $resbalance;
        }

        $addressList     = $this->client->getAddressList($_SESSION['user_session']);
        $transactionList = $this->client->getTransactionList($_SESSION['user_session']);
        $twofactorenabled = isset($_SESSION['user_2fa']) && $_SESSION['user_2fa'];

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

        return json_encode(['success' => true]);
    }
}