<?php
session_start(); 
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_sertifikasi';

try {
    $koneksi = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage()); 
}
?>