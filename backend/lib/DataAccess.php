<?php
/**
 * DataAccess -- Clase encargada de administrar el acceso a una base de datos MySql.
 *
 * @author Harold D Duque C
 * @version 1.0
 * @since Oct of 2016
 */
class DataAccess {
	private $link;

	public function __construct($host, $usuario, $clave, $bd) {
    $this->link = mysqli_connect($host,$usuario,$clave,$bd);
		//mysql_query ("SET NAMES 'utf8'");
		if ($this->link === false) {
			throw new Exception("No se pudo conectar con MySql.");
		} elseif (mysqli_select_db($this->link, $bd) === false) {
			throw new Exception("No se pudo conectar con la base de datos.");
		}
	}

	public function query($sql) {
		//mysql_query ("SET NAMES 'utf8'");
    $resource = mysqli_query($this->link, $sql);
		if ($resource === false) {
			throw new Exception("No se pudo ejecutar la sentencia:<br><br>".$sql."<br><br>".  mysqli_errno($this->link) . ": " . mysqli_errno($this->link));
		} else {
			return $resource;
		}
	}

	public function insert_id() {
		return mysqli_insert_id($this->link);
	}

	public function fetch_object($result) {
    return mysqli_fetch_object($result);
	}

	public function fetch_array($result) {
    return mysqli_fetch_array($result);
	}

	public function data_seek($result, $row_number) {
		if ($row_number >= 0 and $row_number < mysqli_num_rows($result)) {
			$resultado = mysqli_data_seek($result, $row_number);
			if ($resultado === false) {
				throw new Exception("No se pudo mover el puntero.");
			}else{
        return $resultado;
      }
		}
	}

	public function num_rows($result) {
		return mysqli_num_rows($result);
	}
}
