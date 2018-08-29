<?php

namespace Models;

class ReservationTransaction
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
                reservation_transactions
            (
                `reservation_id`,
                `transaction_id`,
                `action`,
                `created_at`
            )
            VALUES(
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
            'iis',
            $data['reservation_id'],
            $data['transaction_id'],
            $data['action']
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

}