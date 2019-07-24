<?php

require_once 'config.php';

class Base
{

	private static $db;
	private static $redis;
	private $available = ['count_fib', 'count_prime'];

	public function __construct()
	{
	    $this->getDb();
	    $this->getRedis();
	}

    /**
     * @return PDO
     */
	public function getDb()
    {
        if (!self::$db) {
            self::$db = new PDO(
                'mysql:dbname='.DB_MYSQL_DATABASE.';host='.DB_MYSQL_HOST.':'.DB_MYSQL_PORT,
                DB_MYSQL_USERNAME,
                DB_MYSQL_PASSWORD
            );
//            $this->dbInit();
        }
        return self::$db;
    }

    /**
     * @return \Redis
     */
	public function getRedis()
    {
        if (self::$redis == null) {
            try {
                self::$redis = new \Redis();
                self::$redis->connect(DB_REDIS_HOST, DB_REDIS_PORT) or die('Cannot connect Redis server!');
            } catch (\Exception $e) {
                die($e->getMessage());
            }
        }
        return self::$redis;
    }

    /**
     * @param int $value
     */
	public function updateSum($value)
    {
        $sql = 'UPDATE `test` SET `sum` = `sum` + ' . $value;
        return $this->update($sql);
    }

    /**
     * @param string $fieldName
     * @param int $value
     */
    public function updateCount($fieldName, $value)
    {
        if (in_array($fieldName, $this->available)) {
            $sql = 'UPDATE `test` SET `'.$fieldName.'` = '.$value;
            return $this->update($sql);
        } else {
            exit('Unknown field name!');
        }
    }

    /**
     * Inits table values
     */
    private function dbInit()
    {
        $sql = 'UPDATE `test` SET `sum` = 0, `count_fib` = 0, `count_prime` = 0';
        return $this->update($sql);
    }

    /**
     * @param string $sql
     */
	private function update($sql)
    {
        $query = self::$db->prepare($sql);
        return $query->execute();
    }

}