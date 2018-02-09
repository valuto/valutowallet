<?php

namespace Controllers;

use Models\User;

class AdminController extends Controller
{

    /**
     * @var User
     */
    protected $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        global $mysqli;

        $this->user = new User($mysqli);

        parent::__construct();
    }

    /**
     * Show admin dashboard
     */
    public function index()
    {
        $userList = $this->user->adminGetUserList();

        include __DIR__ . "/../../view/header.php";
        include __DIR__ . "/../../view/admin_home.php";
        include __DIR__ . "/../../view/footer.php";
    }

    /**
     * Grant adminright to user
     * 
     * @return void
     */
    public function store()
    {
        $this->user->adminPrivilegeAccount($_GET['i']);
        redirect('/admin');
    }

    /**
     * Remove admin rights to user
     * 
     * @return void
     */
    public function destroy()
    {
        $this->user->adminDeprivilegeAccount($_GET['i']);
    }

    /**
     * Unlock account
     * 
     * @return void
     */
    public function unlock()
    {
        $this->user->adminUnlockAccount($_GET['i']);
    }

    /**
     * Lock account
     * 
     * @return void
     */
    public function lock()
    {
        $this->user->adminLockAccount($_GET['i']);
    }

    /**
     * Delete user
     * 
     * @return void
     */
    public function deleteUser()
    {
        $this->user->adminDeleteAccount($_GET['i']);
    }

    /**
     * Get user info
     * 
     * @return void
     */
    public function info()
    {
        if (!empty($_GET['i'])) {
            $info = $this->user->adminGetUserInfo($_GET['i']);
            if (!empty($info)) {
                $info['balance'] = $this->client->getBalance($info['username']);
                if (!empty($_POST['jsaction'])) {
                    $json = array();
                    switch ($_POST['jsaction']) {
                        case "new_address":
                            $this->client->getnewaddress($info['username']);
                            $json['success']         = true;
                            $json['message']         = "A new address was added to your wallet";
                            $json['balance']         = $this->client->getBalance($info['username']);
                            $json['addressList']     = $this->client->getAddressList($info['username']);
                            $json['transactionList'] = $this->client->getTransactionList($info['username']);
                            echo json_encode($json);exit;
                            break;
                        case "withdraw":
                            $json['success'] = false;
                            if (!config('app', 'withdrawals_enabled')) {
                                $json['message'] = "Withdrawals are temporarily disabled";
                            } elseif (empty($_POST['address']) || empty($_POST['amount']) || !is_numeric($_POST['amount'])) {
                                $json['message'] = "You have to fill all the fields";
                            } elseif ($_POST['amount'] > $info['balance']) {
                                $json['message'] = "Withdrawal amount exceeds your wallet balance";
                            } else {
                                $withdraw_message        = $this->client->withdraw($info['username'], $_POST['address'], (float) $_POST['amount']);
                                $_SESSION['token']       = sha1('@s%a$l£t#' . rand(0, 10000));
                                $json['success']         = true;
                                $json['message']         = "Withdrawal successful";
                                $json['balance']         = $this->client->getBalance($info['username']);
                                $json['addressList']     = $this->client->getAddressList($info['username']);
                                $json['transactionList'] = $this->client->getTransactionList($info['username']);
                            }
                            echo json_encode($json);exit;
                            break;
                        case "password":
                            $json['success'] = false;
                            if ((is_numeric($_GET['i'])) && (!empty($_POST['password']))) {
                                $result = $this->user->adminUpdatePassword($_GET['i'], $_POST['password']);
                                if ($result === true) {
                                    $json['success'] = true;
                                    $json['message'] = "Password changed successfully.";
                                } else {
                                    $json['message'] = $result;
                                }
                            } else {
                                $json['message'] = "Something went wrong (at least one field is empty).";
                            }
                            echo json_encode($json);exit;
                            break;
                    }
                }
                $addressList     = $this->client->getAddressList($info['username']);
                $transactionList = $this->client->getTransactionList($info['username']);
                unset($info['password']);
            }
        }
        include __DIR__ . "/../../view/header.php";
        include __DIR__ . "/../../view/admin_info.php";
        include __DIR__ . "/../../view/footer.php";
    }
}