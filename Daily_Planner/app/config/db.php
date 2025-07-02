<?php

$pdo = new PDO('mysql:host=localhost;dbname=daily_planner', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);