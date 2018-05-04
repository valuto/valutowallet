<?php

namespace Controllers;

use Services\ValutoDaemon\Client;
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
        $this->client->getnewaddress();
        $json['success'] = true;
        $json['message'] = "A new address was added to your wallet";
        $jsonbal         = $this->client->getBalance();
        $jsonbalreserve  = $this->client->getBalance() - config('app', 'reserve');
        if ($jsonbalreserve < 0) {
            $json['balance'] = $jsonbal;
        } else {
            $json['balance'] = $jsonbalreserve;}
        $json['balance']         = $jsonbal;
        $json['addressList']     = $this->client->getAddressList();
        $json['transactionList'] = $this->client->getTransactionList();

        return json_encode($json);
    }

    /**
     * Withdraw
     */
    public function withdraw()
    {
        $json = array();

        $noresbal   = $this->client->getBalance();
        $resbalance = $this->client->getBalance() - config('app', 'reserve');
        if ($resbalance < 0) {
            $balance = $noresbal; //Don't show the user a negitive balance if they have no coins with us
        } else {
            $balance = $resbalance;
        }

        $json['success'] = false;
        if (!config('app', 'withdrawals_enabled')) {
            $json['message'] = lang('WALLET_WITHDRAW_TEMP_DISABLED');
        } elseif (empty($_POST['address']) || empty($_POST['amount']) || !is_numeric($_POST['amount'])) {
            $json['message'] = "You have to fill all the fields";
        } elseif ($_POST['token'] != $_SESSION['token']) {
            $json['message']   = lang('WALLET_TOKENS_DO_NOT_MATCH');
            $_SESSION['token'] = sha1('@s%a$l£t#' . rand(0, 10000));
            $json['newtoken']  = $_SESSION['token'];
        } elseif ($_POST['amount'] > $balance) {
            $json['message'] = lang('WALLET_WITHDRAW_BALANCE') . config('app', 'reserve') . ' ' . config('app', 'short');
        } else {
            $withdraw_message        = $this->client->withdraw($_POST['address'], (float) $_POST['amount']);
            $_SESSION['token']       = sha1('@s%a$l£t#' . rand(0, 10000));
            $json['newtoken']        = $_SESSION['token'];
            $json['success']         = true;
            $json['message']         = lang('WALLET_WITHDRAW_SUCCESSFUL');
            $json['balance']         = $this->client->getBalance();
            $json['addressList']     = $this->client->getAddressList();
            $json['transactionList'] = $this->client->getTransactionList();
        }
        return json_encode($json);
    }

}