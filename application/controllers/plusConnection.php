<?php
class plusConnectionX extends mysqli {
	private $DB_HOST 		= "103.228.117.98";
	private $DB_DATABASE 	= "ori_instalasi";
	private $DB_USER 		= "root";
	private $DB_PASSWORD 	= "Annabell2018";
	
	protected $conN;

	public function __construct() {
		$this->conN = mysqli_connect($this->DB_HOST, $this->DB_USER, $this->DB_PASSWORD);
		if(!$this->conN) {
			echo "Connection failed!<br>";
		}
	}

	public function connect() {
		if(!mysqli_select_db($this->conN, $this->DB_DATABASE)) {
			die("Cannot connect database..<br>");
		}

		return $this->conN;
	}
}

?>