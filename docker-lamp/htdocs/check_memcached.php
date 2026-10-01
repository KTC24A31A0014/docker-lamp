<?php
$host = 'mysql';
$username = 'data_user';
$password = 'data';
$database = 'data_master';

// キャッシュへの接続情報
$memcachedHost = 'memcached';
$memcachedPort = 11211;

$cacheKey = 'all_students_data';

// キャッシュの有効秒数
$expireSeconds = 60;

// Memcachedへ接続
$memcached = new Memcached();
$memcached->addServer($memcachedHost, $memcachedPort);

echo "<h1>MySQL Data Caching with Memcached</h1>";

// Memcachedからデータを取得
$cacheData = $memcached->get($cacheKey);

if($cacheData)
    {
        echo "<p>Data retrieved from Memcached(cache hit!).</p>";
        echo "<pre>";
        print_r(json_decode($cacheData, true));
        echo "</pre>";
    }else{
        echo "<p>Data not found in Memcached(cache miss).Retrieving from MySQL...</p>";

        try {
        // PDOでMySQLに接続
        $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // データの取得
        $stmt = $pdo->query("SELECT * FROM students");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // データをMemcachedに保存
        $memcached->set($cacheKey, json_encode($results), $expireSeconds);

        echo "<p>Data retrieved from MySQL and saved to Memcached.</p>";
        echo "<pre>";
        print_r($results);
        echo "</pre>";
        }catch(PDOException $e){
            echo "<p>Error connecting to MySQL or fecthing data:".$e->getMessage()."</p>";
        }
    }

echo "<p>Current time:".date('Y-m-d H:i:s')."</p>";
echo "<p><a href='?clear_cache=1'>Clear Cache(and reload to see cache miss)</a></p>";

// キャッシュ削除機能
if (isset($_GET['clear_cache']) && $_GET['clear_cache'] == 1)
    {
        if ($memcached->delete($cacheKey))
            {
                echo "<p>Cache for '$cacheKey' cleared!</p>";
            }else{
                echo "<p>Failed to clear cache for '$cacheKey'.</p>";
            }
    }
?>