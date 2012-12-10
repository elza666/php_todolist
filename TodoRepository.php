<?php

class TodoRepository
{
	private $connection;

	// konstruktor
	public function __construct($connection)
	{
		$this->connection = $connection;


	// cestina
	$this->connection->query('
		SET CHARACTER SET utf8'

		);
	}

	// metoda vypise seznam vsech ukolu
	public function getAllTodos()
	{
		return $this->connection->query('
			SELECT *
			FROM `todos`
		');
	}

	// metoda oznaci ukol jako hotovy
	public function getDone($id)
	{
		$statement = $this->connection->prepare('
			UPDATE `todos`
			SET `done` = 1
			WHERE `id` = ?
			');
		$statement->execute(array($id));

	}

	// metoda odznaci ukol, bude opet jako nehotovy
	public function getUnDone($id)
	{
		$statement = $this->connection->prepare('
			UPDATE `todos`
			SET `done` = 0
			WHERE `id` = ?
			');
		$statement->execute(array($id));

	}
}