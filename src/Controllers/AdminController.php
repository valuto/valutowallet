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
                $info['balance'] = $this->client->setUser($info)->getBalance();
                if (!empty($_POST['jsaction'])) {
                    $json = array();
                    switch ($_POST['jsaction']) {
                        case "new_address":
                            $this->client->setUser($info)->getnewaddress();
                            $json['success']         = true;
                            $json['message']         = "A new address was added to your wallet";
                            $json['balance']         = $this->client->setUser($info)->getBalance();
                            $json['addressList']     = $this->client->setUser($info)->getAddressList();
                            $json['transactionList'] = $this->client->setUser($info)->getTransactionList();
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
                $addressList     = $this->client->setUser($info)->getAddressList();
                $transactionList = $this->client->setUser($info)->getTransactionList();
                unset($info['password']);
            }
        }
        include __DIR__ . "/../../view/header.php";
        include __DIR__ . "/../../view/admin_info.php";
        include __DIR__ . "/../../view/footer.php";
    }
}