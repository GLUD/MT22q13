<?php
class ErroresAdministrador {
	public function __construct($exception) {
		echo $exception->getMessage();
		die();
	}
}
?>