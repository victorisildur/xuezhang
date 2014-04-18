<?php
session_start();

function checkLogin()
{
	if($_SESSION['admin_login'] == 1) return true;
	else return false;
}

if(!checkLogin())
{
	header("Location: login.php");
	die();
}