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
		return $this->jsonrpc->getbalance("zelles(" . $user_session . ")", 6);
	}

       function getAddress($user_session)
        {
                return $this->jsonrpc->getaccountaddress("zelles(" . $user_session . ")");
	}

	function getAddressList($user_session)
	{
		return $this->jsonrpc->getaddressesbyaccount("zelles(" . $user_session . ")");
	}

	function getTransactionList($user_session)
	{
		return $this->jsonrpc->listtransactions("zelles(" . $user_session . ")", 10);
	}

	function getNewAddress($user_session)
	{
		return $this->jsonrpc->getnewaddress("zelles(" . $user_session . ")");
	}

	function withdraw($user_session, $address, $amount)
	{
		return $this->jsonrpc->sendfrom("zelles(" . $user_session . ")", $address, (float)$amount, 6);
	}
}
