<?php
session_start();
function connect() {
$dbase = new PDO('mysql:host=localhost:8889;dbname=classes;','root','root');
[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION];
return $dbase;
}
