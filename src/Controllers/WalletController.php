<?php

namespace Controllers;

use Models\Client;
use Models\User;

class WalletController extends Controller
{
    /**
     * Contructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Request new address
     */
    public function newaddress()
    {
        $json = array();
        $this->client->getnewaddress($_SESSION['user_session']);
        $json['success'] = true;
        $json['message'] = "A new address was added to your wallet";
        $jsonbal         = $this->client->getBalance($_SESSION['user_session']);
        $jsonbalreserve  = $this->client->getBalance($_SESSION['user_session']) - config('app', 'reserve');
        if ($jsonbalreserve < 0) {
            $json['balance'] = $jsonbal;
        } else {
            $json['balance'] = $jsonbalreserve;}
        $json['balance']         = $jsonbal;
        $json['addressList']     = $this->client->getAddressList($_SESSION['user_session']);
        $json['transactionList'] = $this->client->getTransactionList($_SESSION['user_session']);

        return json_encode($json);
    }

    /**
     * Withdraw
     */
    public function withdraw()
    {
        $json = array();

        $noresbal   = $this->client->getBalance($_SESSION['user_session']);
        $resbalance = $this->client->getBalance($_SESSION['user_session']) - config('app', 'reserve');
        if ($resbalance < 0) {
            $balance = $noresbal; //Don't show the user a negitive balance if they have no coins with us
        } else {
            $balance = $resbalance;
        }

        $json['success'] = false;
        if (!config('app', 'withdrawals_enabled')) {
            $json['message'] = "Withdrawals are temporarily disabled";
        } elseif (empty($_POST['address']) || empty($_POST['amount']) || !is_numeric($_POST['amount'])) {
            $json['message'] = "You have to fill all the fields";
        } elseif ($_POST['token'] != $_SESSION['token']) {
            $json['message']   = "Tokens do not match";
            $_SESSION['token'] = sha1('@s%a$l£t#' . rand(0, 10000));
            $json['newtoken']  = $_SESSION['token'];
        } elseif ($_POST['amount'] > $balance) {
            $json['message'] = "Withdrawal amount exceeds your wallet balance. Please note the wallet owner has set a reserve fee of config('app', 'reserve') " . config('app', 'short');
        } else {
            $withdraw_message        = $this->client->withdraw($_SESSION['user_session'], $_POST['address'], (float) $_POST['amount']);
            $_SESSION['token']       = sha1('@s%a$l£t#' . rand(0, 10000));
            $json['newtoken']        = $_SESSION['token'];
            $json['success']         = true;
            $json['message']         = "Withdrawal successful";
            $json['balance']         = $this->client->getBalance($_SESSION['user_session']);
            $json['addressList']     = $this->client->getAddressList($_SESSION['user_session']);
            $json['transactionList'] = $this->client->getTransactionList($_SESSION['user_session']);
        }
        return json_encode($json);
    }

}