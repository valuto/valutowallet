<?php 

namespace Models;

class Role {

	protected $mysqli;

	public function __construct()
	{
        global $mysqli;
        $this->mysqli = $mysqli;
    }
    
    public function get()
    {
        $roles = $this->mysqli->query("SELECT * FROM roles");

		$collection = [];

		while ($user = $roles->fetch_assoc()) {
            $collection[] = $user;
        }
        
        $roles->free();

        return $collection;
    }

    public function select($column)
    {
        $roles = $this->get();

        return array_column($roles, $column);
    }

}