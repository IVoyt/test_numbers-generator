<?php

require_once 'Base.php';

class PrimesGenerator extends Base
{

    private $count;
    private $timeout;
    private $redis;
    private $primeNum5k = 0;
    private $result = 0;
    private $prev = 0;
    private $sum = 0;

    public function __construct(int $count, int $timeout)
    {
        parent::__construct();

        if ($count < 1) exit("parameter \"-c\" should be integer and greater than 0\n");
        $this->count = $count;
        if ($timeout < 1) exit("parameter \"-t\" should be integer and greater than 0\n");
        $this->timeout = $timeout;

        $this->redis = $this->getRedis();
        $this->primeNumber();
    }

    public function primeNumber()
    {
        echo "Prime numbers Generator started...\n";

        $number = 2;
        $loop = 1;

        while ($loop <= $this->count) {
            for ($i = 1; $i <= $number; $i++) {
                if ($i != 2 && $i > 1 && $number / $i != 1 && $number % $i == 0) {
                    break;
                }
                if ($number / $i == 1 && $number % $i == 0) {
                    $this->result = $number;
                    $this->sum = bcadd($this->result, $this->prev);

                    echo "loop num:\t{$loop}\n";
                    echo "result:\t\t{$this->result}\n\n";

                    $this->prev = $this->result;

                    $this->redis->rpush('prime', $number);
                    $loop++;
                }
            }

            if ($number < 3)    $number++;
            else                $number += 2;

            usleep($this->timeout * 1000);
        }

        echo "\n";
        echo 'count: '.$this->count."\n";
        echo 'timeout: '.$this->timeout."\n";
    }

    public function primeNumberEratosthenes()
    {
        echo "Prime numbers Generator started...\n";

        $numbers = range(2, $this->count);

        foreach ($numbers as $key => $number) {
            if ($number != 2 && $number % 2 == 0) unset($numbers[$key]);
        }

        $next_prime = 3;
        // Sieve of Eratosthenes
        while ($next_prime <= sqrt($this->count)) {
            foreach ($numbers as $key => $number) {
                if ($number != 2 && $number % $next_prime == 0 && $number / $next_prime != 1) unset($numbers[$key]);
            }
            $next_prime += 2;
        }

        $numbers = array_values($numbers);

        $i = 1;
        foreach ($numbers as $key => $number) {
            $this->result = $number;
            $this->sum = bcadd($this->result, $this->prev);

            echo "loop num:\t{$i}\n";
            echo "previous:\t{$this->prev}\n";
            echo "result:\t\t{$this->result}\n";
            echo "sum:\t\t{$this->sum}\n";
            echo "digits num:\t".strlen($number)."\n\n";
            $this->prev = $this->result;

            $this->redis->rpush('prime', $number);

            usleep($this->timeout * 1000);
            $i++;
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

$generator = new PrimesGenerator((int)$options['c'], (int)$options['t']);