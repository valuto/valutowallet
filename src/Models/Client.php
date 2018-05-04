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
		return $this->jsonrpc->getbalance($this->getWalletId($user_session), 6);
	}

	function getAddress($user_session)
	{
        return $this->jsonrpc->getaccountaddress($this->getWalletId($user_session));
	}

	function getAddressList($user_session)
	{
		return $this->jsonrpc->getaddressesbyaccount($this->getWalletId($user_session));
	}

	function getTransactionList($user_session)
	{
		return $this->jsonrpc->listtransactions($this->getWalletId($user_session), 10);
	}

	function getNewAddress($user_session)
	{
		return $this->jsonrpc->getnewaddress($this->getWalletId($user_session));
	}

	function withdraw($user_session, $address, $amount)
	{
		return $this->jsonrpc->sendfrom($this->getWalletId($user_session), $address, (float)$amount, 6);
	}

	/**
	 * Get wallet account ID for user.
	 * 
	 * @param  string $userSession  the username of the current logged in user.
	 * @return string the wallet account ID in the old or new format.
	 */
	protected function getWalletId($userSession)
	{
		if (!isset($_SESSION['user_uses_old_account_identifier']) || $_SESSION['user_uses_old_account_identifier']) {
			return "zelles(" . $userSession . ")";
		} else {
			return "valutowallet(" . $_SESSION['user_id'] . ")";
		}
	}
}
