<?php

namespace Services\ValutoDaemon;

class Client {

	/**
	 * RPC URI.
	 * 
	 * @var string
	 */
	protected $uri;

	/**
	 * JSON RPC client.
	 * 
	 * @var jsonRPCClient
	 */
	protected $jsonrpc;

	/**
	 * User data.
	 * 
	 * @var array|null
	 */
	protected $user;

	/**
	 * Construct class with URI segments.
	 * 
	 * @param string $host
	 * @param string $port
	 * @param string $user
	 * @param string $pass
	 */
	public function __construct($host, $port, $user, $pass)
	{
		$this->uri = "http://" . $user . ":" . $pass . "@" . $host . ":" . $port . "/";
		$this->jsonrpc = new \jsonRPCClient($this->uri);
	}

	/**
	 * Set user, if you want to handle RPC calls for that account instead of 
	 * the one from the session.
	 * 
	 * @param array $user
	 */
	public function setUser($user)
	{
		$this->user = $user;

		return $this;
	}

	public function getBalance()
	{
		return $this->jsonrpc->getbalance($this->getWalletId(), 6);
	}

	public function getAddress()
	{
        return $this->jsonrpc->getaccountaddress($this->getWalletId());
	}

	public function getAddressList()
	{
		return $this->jsonrpc->getaddressesbyaccount($this->getWalletId());
	}

	public function getTransactionList()
	{
		return $this->jsonrpc->listtransactions($this->getWalletId(), 10);
	}

	public function getNewAddress()
	{
		return $this->jsonrpc->getnewaddress($this->getWalletId());
	}

	public function withdraw($address, $amount)
	{
		return $this->jsonrpc->sendfrom($this->getWalletId(), $address, (float)$amount, 6);
	}

	/**
	 * Determine if user uses old or new account identifier.
	 * 
	 * @return int
	 */
	protected function isOldAccountIdentifier()
	{
		if (isset($this->user)) {
			return $this->user['uses_old_account_identifier'];
		} else {
			return $_SESSION['user_uses_old_account_identifier'];
		}
	}

	/**
	 * Get username.
	 * 
	 * @return string
	 */
	protected function getUsername()
	{
		if (isset($this->user)) {
			return $this->user['username'];
		} else {
			return $_SESSION['user_session'];
		}
	}

	/**
	 * Get user id.
	 * 
	 * @return int
	 */
	protected function getUserId()
	{
		if (isset($this->user)) {
			return $this->user['id'];
		} else {
			return $_SESSION['user_id'];
		}
	}

	/**
	 * Get wallet account ID for user.
	 * 
	 * @return string the wallet account ID in the old or new format.
	 */
	protected function getWalletId()
	{
		if ( ! isset($this->user) && ! isset($_SESSION['user_session']) && ! isset($_SESSION['user_uses_old_account_identifier'])) {
			throw new \Exception('User data not set in RPC client.');
		}

		if ($this->isOldAccountIdentifier()) {
			return "zelles(" . $this->getUsername() . ")";
		} else {
			return "valutowallet(" . $this->getUserId() . ")";
		}
	}
}
