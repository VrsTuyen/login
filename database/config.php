<?php
define('SERVER_NAME', 'localhost');
define('DB_NAME', 'accounts');
define('USERNAME', 'root');
define('PASSWORD', '');
$connection = null;
try {
  $connection = new PDO("mysql:host=" . SERVER_NAME . ";dbname=" . DB_NAME, USERNAME, PASSWORD);
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "connection successfully";
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
  $connection = null;
}
?>