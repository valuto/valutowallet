<?php

namespace Models;

class Reservation
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
    
    public function find($id)
    {
        $stmt = $this->mysqli->prepare('SELECT * FROM reservations WHERE id=?');

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result->fetch_assoc();
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
                reservations
            (
                `user_id`,
                `amount`,
                `origin`,
                `reference_id`,
                `state`,
                `created_at`,
                `updated_at`
            )
            VALUES(
                ?,
                ?,
                ?,
                ?,
                ?,
                NOW(),
                NOW()
            )");

        if (!$stmt) {
            dd($this->mysqli->error);
            return false;
        }

        $stmt->bind_param(
            'issss',
            $data['user_id'],
            $data['amount'],
            $data['origin'],
            $data['reference_id'],
            $data['state']
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * Update state in model.
     * 
     * @param array $data
     */
    public function updateState($reservationId, $state)
    {
        $stmt = $this->mysqli->prepare("
            UPDATE
                reservations
            SET
                `state` = ?,
                `updated_at` = NOW()
            WHERE
                id = ?");

        if (!$stmt) {
            dd($this->mysqli->error);
            return false;
        }

        $stmt->bind_param(
            'si',
            $state,
            $reservationId
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
}