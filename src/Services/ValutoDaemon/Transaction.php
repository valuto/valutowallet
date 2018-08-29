<?php

namespace Services\ValutoDaemon;

use Models\Transaction as TransactionModel;

/**
 * Wrapper function for "Client"-service, that will save transaction details to database 
 * while doing the valuto daemon actions.
 */
class Transaction
{
    /**
     * RPC client instance.
     * 
     * @var Services\ValutoDaemon\Client
     */
    protected $client;

	/**
	 * User data.
	 * 
	 * @var array|null
	 */
	protected $user;

    /**
     * The database instance.
     * 
     * @var MySQLi
     */
	protected $mysqli;

    /**
     * The transaction model instance.
     * 
     * @var Models\Transaction
     */
	protected $transaction;

    /**
     * Construct service with dependencies.
     * 
     * @param MySQLi $mysqli  the database instance.
     * @return void
     */
    public function __construct($mysqli)
    {
		$this->mysqli = $mysqli;
        $this->transaction = new TransactionModel($this->mysqli);
    }

	/**
	 * Set the client object.
	 * 
	 * @param Services\ValutoDaemon\Client $client
	 */
	public function setClient($client)
	{
        $this->client = $client;
        
		return $this;
	}

    /**
     * Withdraw amount from wallet and save 
     * transaction details to database.
     * 
     * @param string $address
     * @param decimal $amount
     * @return string the transaction id.
     */
    public function withdraw($address, $amount)
    {        
        $user = $this->client->getUser();

        $valutoTransactionId = $this->client->withdraw($address, $amount);

        $user = $this->client->getUser();

        $this->transaction->create([
            'from_user_id' => $user['id'],
            'valuto_transaction_id' => $valutoTransactionId,
            'comment' => '',
            'amount' => $amount,
        ]);
        
        $transactionId = $this->mysqli->insert_id;

        return [
            $valutoTransactionId,
            $transactionId,
        ];
    }
}
