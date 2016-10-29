<?php
class Sql {
	
	function Sql() {}

	function Insert($table, $info) {
		$count		= count($info);
		$info_keys	= array_keys($info);

		$sql = "INSERT INTO	{$table}(";

		for ($i = 0; $i < $count; $i++) {
			if ($info_keys[$i][0] == '"') {
				$sql .= substr($info_keys[$i], 1);
			} else {
				$sql .= $info_keys[$i];
			}

			if ($i != $count - 1) {
				$sql .= ", ";
			}
		}

		$sql .= ") VALUES(";

		for ($i = 0; $i < $count; $i++) {
			if ($info_keys[$i][0] == '"') {
				$sql .= "'{$info[$info_keys[$i]]}'";
			} else {
				$sql .= $info[$info_keys[$i]];
			}

			if ($i != $count - 1) {
				$sql .= ", ";
			}
		}

		$sql .= ")";

		return $sql;
	}

	function Update($table, $info, $id_key, $id_value) {
		$count		= count($info);
		$info_keys	= array_keys($info);

		$sql = "UPDATE {$table} SET ";

		for ($i = 0; $i < $count; $i++) {
			if ($info_keys[$i][0] == '"') {
				$sql .= substr($info_keys[$i], 1)." = '{$info[$info_keys[$i]]}'";
			} else {
				$sql .= "{$info_keys[$i]} = {$info[$info_keys[$i]]}";
			}

			if ($i != $count - 1) {
				$sql .= ", ";
			}
		}

		$sql .= " WHERE {$id_key} = {$id_value}";

		return $sql;
	}

	function Delete($table, $info) {
		$count		= count($info);
		$info_keys	= array_keys($info);

		$sql = "DELETE FROM {$table} WHERE ";

		for ($i = 0; $i < $count; $i++) {
			if ($info_keys[$i][0] == '"') {
				$sql .= substr($info_keys[$i], 1)." = '{$info[$info_keys[$i]]}'";
			} else {
				$sql .= "{$info_keys[$i]} = {$info[$info_keys[$i]]}";
			}

			if ($i != $count - 1) {
				$sql .= " and ";
			}
		}

		return $sql;
	}
}


?>