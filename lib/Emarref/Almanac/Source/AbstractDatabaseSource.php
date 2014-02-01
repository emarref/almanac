<?php

namespace Emarref\Almanac\Source;

use PDO;

abstract class AbstractDatabaseSource implements SourceInterface
{
    /**
     * @var PDO
     */
    private $conn;

    /**
     * @var string
     */
    private $dsn;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @param $dsn
     * @param $username
     * @param $password
     */
    public function __construct($dsn, $username, $password)
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;

        register_shutdown_function(array($this, 'shutdown'));
    }

    /**
     * Connect to the data source
     */
    protected function connect()
    {
        $this->conn = new PDO($this->dsn, $this->username, $this->password, $this->getConnectionOptions());
    }

    /**
     * Close connection to the data source
     */
    protected function disconnect()
    {
        $this->conn = null;
    }

    /**
     * Tasks to perform when this class is no longer needed.
     */
    public function shutdown()
    {
        $this->disconnect();
    }

    /**
     * @return array
     */
    protected function getConnectionOptions()
    {
        // Noop. Override me.
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function query($query)
    {
        $statement = $this->conn->prepare($query);

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($seed)
    {
        $this->connect();

        $results = $this->query($seed);

        $this->disconnect();

        return $results;
    }
}