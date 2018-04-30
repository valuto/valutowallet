<?php

namespace Models;

class Client {
	private $uri;
	private $jsonrpc;

	function __construct($host, $port, $user, $pass)
	{
		$this->uri = "http://" . $user . ":" . $pass . "@" . $host . ":" . $port . "/";
		$this->jsonrpc = new \jsonRPCClient($this->uri);
	}

	function getBalance($user_session)
	{
		return $this->jsonrpc->getbalance("zelles(" . $this->getWalletId($user_session) . ")", 6);
	}

	function getAddress($user_session)
	{
        return $this->jsonrpc->getaccountaddress("zelles(" . $this->getWalletId($user_session) . ")");
	}

	function getAddressList($user_session)
	{
		return $this->jsonrpc->getaddressesbyaccount("zelles(" . $this->getWalletId($user_session) . ")");
	}

	function getTransactionList($user_session)
	{
		return $this->jsonrpc->listtransactions("zelles(" . $this->getWalletId($user_session) . ")", 10);
	}

	function getNewAddress($user_session)
	{
		return $this->jsonrpc->getnewaddress("zelles(" . $this->getWalletId($user_session) . ")");
	}

	function withdraw($user_session, $address, $amount)
	{
		return $this->jsonrpc->sendfrom("zelles(" . $this->getWalletId($user_session) . ")", $address, (float)$amount, 6);
	}

	protected function getWalletId($userSession)
	{
		if (!isset($_SESSION['user_uses_old_account_identifier']) || $_SESSION['user_uses_old_account_identifier']) {
			return $userSession;
		} else {
			return $_SESSION['user_id'];
		}
	}
}
