<?php
$host = 'db.kgzhkvsdhbhobnltrctf.supabase.co';
$port = 5432;
$dbname = 'postgres';
$user = 'postgres';
$password = '5*7$JF.xC3KiVFd';

$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password sslmode=require";

$conn = pg_connect($conn_string);

if ($conn) {
    echo "Connected to Supabase PostgreSQL successfully.";
} else {
    echo "Failed to connect to Supabase PostgreSQL.";
}