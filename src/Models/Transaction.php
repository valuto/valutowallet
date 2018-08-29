<?php

namespace Models;

class Transaction
{
    /**
     * The database instance.
     * 
     * @var MySQLi
     */
	private $mysqli;

    /**
     * Instantiate model with dependencies.
     * 
     * @param MySQLi $mysqli  the database instance.
     * @return void
     */
	public function __construct($mysqli)
	{
		$this->mysqli = $mysqli;
    }
    
    /**
     * Create new object in model.
     * 
     * @param array $data
     */
    public function create($data)
    {
        $stmt = $this->mysqli->prepare("
            INSERT INTO 
                transactions
            (
                `sender_user_id`,
                `valuto_transaction_id`,
                `comment`,
                `amount`,
                `created_at`
            )
            VALUES(
                ?,
                ?,
                ?,
                ?,
                NOW()
            )");

        if (!$stmt) {
            dd($this->mysqli->error);
            return false;
        }

        $stmt->bind_param(
            'isss',
            $data['sender_user_id'],
            $data['valuto_transaction_id'],
            $data['comment'],
            $data['amount']
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

}