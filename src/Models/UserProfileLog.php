<?php

namespace Models;

class UserProfileLog
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
                users_profile_log
            (
                `user_id`,
                `payload`,
                `created_at`
            )
            VALUES(
                ?,
                ?,
                NOW()
            )");

        if (!$stmt) {
            dd($this->mysqli->error);
            return false;
        }

        $stmt->bind_param(
            'is',
            $data['user_id'],
            $data['payload']
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

}