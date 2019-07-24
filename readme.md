#####1. Installing Redis server
The console application requires Redis server. Download latest stable version of redis from official website https://redis.io/download<br>
Unpack downloaded archive. Go to unpacked folder and execute following command
```
$ make install
```

If compiling process fails execute following command
```
$ sudo make install
```
In result in folder **_src_** should appear binary file **_redis-server_**. If compiling process still fails then refer Redis documentation.<br>
To start Redis server go to **_src_** folder and execute file **_redis-server_**. E.g.:
```
$ cd /home/user/Downloads/redis-5.0.5/src
$ redis-server
```

#####2. Installing Redis client
Follow the installation instructions https://github.com/phpredis/phpredis/blob/develop/INSTALL.markdown<br><br>
If you wish to compile Redis client php extension from sources then clone git-repository to any location on your computer from https://github.com/phpredis/phpredis.git<br>


#####3. Creating DB
Open your Database Management System and execute in it commands from script **_db.sql_**

#####4. Configuring app
Edit **_config.php_** to correspond your DB settings

#####5. Starting the generators
To start generator go to project's root folder and execute the following command
```
$ php GeneratorScriptName.php -c x -t y
```
where **GeneratorScriptName** replace with generator's filename, **x** - replace with count of generating numbers, **y** - replace with time in milliseconds between each iteration. E.g.:
```
$ php FibonacciGenerator.php -c 150 -t 1000
```
After the generating process has been ended the script will be ended automatically.

#####6. Starting the subscribers
To start subscriber go to project's root folder and execute the following command
```
$ php SubscriberScriptName.php -t y
```
where **SubscriberScriptName** replace with subscriber's filename, **y** - replace with time in milliseconds between each iteration. E.g.:
```
$ php FibonacciSubscriber.php -t 1000
```
After reading all numbers the script will continue working "listening" for new data(numbers). To interrupt script, press the key combination **Ctrl + C**.
