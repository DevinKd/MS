<?php
/**
 * Database Configuration
 * 数据库连接配置，其他 PHP 文件通过 require_once 引入
 *
 * 使用方式：
 *   require_once '../config/database.php';
 *   $conn = getDB();           // 获取数据库连接
 *   $conn->query("SELECT ..."); // 执行 SQL
 */

// ========== 数据库配置 ==========
define('DB_HOST',     'localhost');
define('DB_PORT',     3306);
define('DB_NAME',     'account');
define('DB_CHARSET',  'utf8mb4');
define('DB_USER',     'root');
define('DB_PASS',     'admin');

// ========== 获取数据库连接（单例模式） ==========
function getDB(string $databaseName = DB_NAME): PDO
{
    static $connections = [];
    $key = $databaseName;

    if (!isset($connections[$key])) {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            DB_HOST, DB_PORT, $databaseName, DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $connections[$key] = new PDO($dsn, DB_USER, DB_PASS, $options);
    }

    return $connections[$key];
}