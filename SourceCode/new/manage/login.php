<?php
require_once('../common.inc.php');
session_start();
if($_SESSION['admin_login'])
{
	header("Location: index.php");
	die();
}
if(isset($_POST['submit']))
{
	if($_POST['username'] == ADMIN_NAME && $_POST['password'] == ADMIN_PWD)
	{
		setLogin();
	}
}

require_once(TEMPLATES_PATH.'manage/login.htm');


function setLogin()
{
	$_SESSION['admin_login'] = 1;
	header("Location: index.php");
	die();
}