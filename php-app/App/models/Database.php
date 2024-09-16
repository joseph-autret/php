<?php

require_once 'config.php';

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $dbh;
    private $error;
    private static $instance = null;

    private function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            // PDO::MYSQL_ATTR_SSL_CA => '/etc/mysql/ssl/ca.pem',
        );

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            error_log($this->error, 3, '/var/log/php_db_errors.log');
            var_dump($this->error);
        }
    }

  public function __destruct()
  {
    $this->dbh = null;
  }

  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public function getConnection()
  {
    return $this->dbh;
  }

  public function closeConnection()
  {
    $this->dbh = null;
  }

  private function __clone()
  {
  }

  public function __wakeup()
  {
    throw new Exception("Cannot unserialize a singleton.");
  }
}
