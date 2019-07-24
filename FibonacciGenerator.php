<?php

require_once 'Base.php';

class FibonacciGenerator extends Base
{

    private $count;
    private $timeout;
    private $cur = '0';
    private $prev = '0';
    private $result;
    private $fibonacciNum2k;
    private $redis;

    public function __construct(int $count, int $timeout)
    {
        parent::__construct();

        if ($count < 1) exit("parameter \"-c\" should be integer and greater than 0\n");
        $this->count = $count;
        if ($timeout < 1) exit("parameter \"-t\" should be integer and greater than 0\n");
        $this->timeout = $timeout;

        $this->redis = $this->getRedis();
        $this->fibonacciNumber();
    }

    public function fibonacciNumber()
    {
        echo "Fibonacci numbers Generator started...\n";

        for ($i = 0; $i < $this->count; $i++) {
            if ($i == 0) {
                $this->result = '0';
            } elseif ($i == '1' || $i == '2') {
                $this->cur = '1';
                $this->prev = '0';
            } else {
                $this->prev = $this->cur;
                $this->cur = $this->result;
            }
            $this->result = bcadd($this->cur, $this->prev);

            echo "loop num:\t".($i + 1)."\n";
            echo "current:\t{$this->cur}\n";
            echo "previous:\t{$this->prev}\n";
            echo "result:\t\t{$this->result}\n\n";

            $this->redis->rpush('fib', $this->result);

            usleep($this->timeout * 1000);
        }

        echo "\n";
        echo 'count: '.$this->count."\n";
        echo 'timeout: '.$this->timeout."\n";
    }

}


// "c" stands for Count
// "t" stands for Timeout
$options = getopt('c:t:');
if (!isset($options['c']) || !isset($options['t'])) {
    exit("Usage: php <filename> -c [int] -t [int]\n");
}

$generator = new FibonacciGenerator((int)$options['c'], (int)$options['t']);