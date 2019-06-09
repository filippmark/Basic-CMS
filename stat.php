<?php

if (isset ($_POST['log'])) { 
	$data = date('d.m.Y H:i:s', strtotime('+3 hours'));
	$page = $_POST['url'];
	$mysqli = new mysqli("127.0.0.1", "fill", "123", "lr8");
	$query = "INSERT INTO `clicksPro` (date, page) VALUES ('".$data."','".$page."')";
	$mysqli->query($query);
}
