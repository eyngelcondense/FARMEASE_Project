<?php
$conn = new mysqli('db', 'root', '<password>', 'farmease_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
