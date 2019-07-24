<?php

require_once 'Base.php';

class FibonacciSubscriber extends Base
{
    private $timeout;
    private $redis;

    public function __construct($timeout = 1000)
    {
        parent::__construct();
        $this->timeout = $timeout;
        $this->redis = $this->getRedis();
        $this->subscriber();
    }

    public function subscriber()
    {
        echo "Fibonacci numbers Subscriber started...\n";

        if ($this->redis) {
            $i = 0;

            echo "Reading from channel \"Fibonacci\"\n";

            while (true) {
                $curNumber = $this->redis->lpop('fib');
                if ($curNumber || $curNumber === '0') {
                    var_dump($curNumber);
                    ++$i;
                    $this->updateCount('count_fib', $i);
                    $this->updateSum($curNumber);
                }
                usleep($this->timeout * 1000);
            }
            echo "\n";
        }
    }

}

// "t" stands for Timeout
$options = getopt('t:');
if (!isset($options['t'])) {
    exit("Usage: php <filename> -t [int]\n");
}

$generator = new FibonacciSubscriber((int)$options['t']);