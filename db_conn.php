<?php

$host = "localhost";
$dbuser = "root";
$dbpass = "root";
$db_name = "dfe";
$db_port = "8889";
$db_socket = '/Applications/MAMP/tmp/mysql/mysql.sock';

$conn = @new mysqli($host, $dbuser, $dbpass, $db_name, $db_port, $db_socket);

if (!$conn) {
  echo "Connection Failed";
}
