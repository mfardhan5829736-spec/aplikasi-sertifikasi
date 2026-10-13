<?php
session_start();
session_destroy(); // Menghancurkan memori login
header("Location: index.php"); // Melempar user kembali ke halaman login
?>