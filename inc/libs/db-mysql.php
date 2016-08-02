<?php

/*
 * Min PHP Version: 4
 */

class db
{
	//Init
	private $host;
	private $dbname;
	private $usr;
	private $pass;
	private $dbh;
	private $prefix;
	public $data;

	//Datenbankverbindung aufbauen
	function __construct($host, $dbname, $usr, $pass, $prefix = '')
	{
		$this->host = $host;
		$this->dbname = $dbname;
		$this->usr = $usr;
		$this->pass = $pass;
		$this->prefix = $prefix;

		try
		{
			$this->dbh = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $usr, $pass);
		}
		catch(PDOException $e) {
			echo $e->getMessage();
			exit;
		}

		//UTF-8
		$this->dbh->exec("SET NAMES 'utf8'");
		$this->dbh->exec("SET CHARACTER SET 'utf8'");
	}

	private $col = null;

	public function setCol($col)
	{
		$this->clear();
		$this->col = $col;
	}

	//Daten holen
	public function get($where = [], $link = 'AND')
	{
		if (isset($this->col))
		{
			//Entweder übergebene Daten oder in $this->data vorhandene nutzen
			if (empty($where))
			{
				if (empty($this->data))
				{
					$where = [];
				} else
				{
					$where = $this->data;
				}
			}

			//Where zusamenbauen
			$whereCl = '';
			$whereAr = [];
			if (!empty($where))
			{
				$i = 1;
				$whereCount = count($where);
				$whereCl = ' WHERE ';
				foreach ($where as $col => $val)
				{
					$whereCl .= $col . ' = ?';
					$whereAr[] = $val;
					if ($i < $whereCount) $whereCl .= ' ' . $link . ' ';
					$i++;
				}
			}

			//print_r($whereAr);

			$stmt = $this->dbh->prepare('SELECT * FROM ' . $this->prefix . $this->col . $whereCl);
			$stmt->execute($whereAr);

			$all = [];
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$all[] = $row;
			}

			$this->data = '';
			/*$cnt = count($all);

			if ($cnt == 1)
			{
				$this->data = $all[0];
				return $all[0];
			} else
			{*/
			$this->data = $all;
			return $all;
			//}
		}
	}

	//Daten einfügen
	public function insert($args = [])
	{
		if (isset($this->col))
		{
			//Entweder übergebene Daten oder in $this->data vorhandene nutzen
			if (empty($args))
			{
				if (empty($this->data))
				{
					$args = [];
				} else
				{
					$args = $this->data;
				}
			}

			if (!empty($args))
			{
				$stmt = 'INSERT INTO ' . $this->prefix . $this->col . ' (`';
				$i = 1;
				$vals = [];
				$valCnt = '';
				foreach ($args as $key => $val)
				{
					$stmt .= $key.'`';
					//$vals[] = utf8_encode($val);
					$vals[] = $val;
					$valCnt .= '?';
					if ($i < count($args))
					{
						$stmt .= ', `';
						$valCnt .= ', ';
					}
					$i++;
				}
				$stmt .= ') VALUES (' . $valCnt . ')';
				//echo $stmt;

				$insert = $this->dbh->prepare($stmt);
				return $insert->execute($vals);
			}
		}
	}

	public function lastID()
	{
		return $this->dbh->lastInsertId();
	}

	//Daten Updaten
	public function update($where = [], $dataToUpdate = [], $link = 'AND')
	{
		if (isset($this->col))
		{
			//Entweder übergebene Daten oder in $this->data vorhandene nutzen
			if (empty($dataToUpdate))
			{
				if (empty($this->data))
				{
					$dataToUpdate = [];
				} else
				{
					$dataToUpdate = $this->data;
				}
			}


			//echo mb_detect_encoding($dataToUpdate['alias']);
			//print_r($dataToUpdate);

			$stmt = 'UPDATE ' . $this->prefix . $this->col . ' SET ';
			$vals = [];
			$i = 1;
			foreach ($dataToUpdate as $key => $val)
			{
				$stmt .= $key . ' = ?';
				//$val = utf8_encode($val);
				//$vals[] = utf8_encode($val);
				$vals[] = $val;
				//echo mb_detect_encoding($val).' ->  '.$val;
				if ($i < count($dataToUpdate)) $stmt .= ', ';
				$i++;
			}

			//Where zusamenbauen
			$whereCl = '';
			$whereAr = [];
			if (!empty($where))
			{
				$i = 1;
				$whereCount = count($where);
				$whereCl = ' WHERE ';
				foreach ($where as $col => $val)
				{
					$whereCl .= $col . ' = ?';
					$vals[] = $val;
					if ($i < $whereCount) $whereCl .= ' ' . $link . ' ';
					$i++;
				}
			}
			$stmt .= $whereCl;

			//secho $stmt;
			$update = $this->dbh->prepare($stmt);
			return $update->execute($vals);
		}
		else
		{
			return false;
		}
	}

	//Daten Löschen
	public function delete($where = [], $link = 'AND')
	{
		if (isset($this->col))
		{
			//Entweder übergebene Daten oder in $this->data vorhandene nutzen
			if (empty($where))
			{
				if (empty($this->data))
				{
					$where = [];
				} else
				{
					$where = $this->data;
				}
			}

			$stmt = 'DELETE FROM ' . $this->prefix . $this->col;
			//Where zusamenbauen
			$whereCl = '';
			$whereAr = [];
			$vals = [];
			if (!empty($where))
			{
				$i = 1;
				$whereCount = count($where);
				$whereCl = ' WHERE ';
				foreach ($where as $col => $val)
				{
					$whereCl .= $col . ' = ?';
					$vals[] = $val;
					if ($i < $whereCount) $whereCl .= ' ' . $link . ' ';
					$i++;
				}
			}
			$stmt .= $whereCl;

			//echo $stmt;
			$delete = $this->dbh->prepare($stmt);
			return $delete->execute($vals);
		}
	}

	//Create Table
	public function createCol($name, $rows)
	{
		$dataTypes = ['int' => 'bigint(11) NOT NULL', 'string' => 'text CHARACTER SET utf8 NOT NULL', 'longstring' => 'longtext CHARACTER SET utf8 NOT NULL', 'boolean' => 'tinyint(1) NOT NULL'];
		$stmt = 'CREATE TABLE '.$name.'(';
		foreach ($rows as $colname => $coldata)
		{
			if(array_key_exists($coldata, $dataTypes))
			{
				$stmt .= $colname . ' ' . $dataTypes[$coldata] . ',';
			}
		}
		$stmt = substr($stmt, 0, strlen($stmt) - 1);
		$stmt .= ')';
		return $this->dbh->exec($stmt);
	}

	//Version
	public function version()
	{
		$STH = $this->dbh->query('SELECT VERSION( ) AS version');
		$STH->setFetchMode(PDO::FETCH_OBJ);
		if ($row = $STH->fetch())
		{
			return $row->version;
		}
	}

	//Aufräumen
	public function clear()
	{
		$this->col = null;
		$this->data = '';
	}

	//Query
	public function query($query)
	{
		$STH = $this->dbh->prepare($query);
		return $STH->execute();
	}
}