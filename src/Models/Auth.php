<?php 

class Auth {

	protected $mysqli;

	public function __construct($mysqli)
	{
		$this->mysqli = $mysqli;
	}

}