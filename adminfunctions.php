<?php

require('cred.php');

function init(){
	$query = 'CREATE TABLE IF NOT EXISTS configs (uid SMALLINT NOT NULL AUTO_INCREMENT KEY,' . 
		'numcars SMALLINT DEFAULT 1, make VARCHAR(20), model VARCHAR(20), year YEAR(4),' . 
		'color VARCHAR(10), licenseplate(9), state VARCHAR(2), damagetype VARCHAR(10), ' . 
		'other VARCHAR(20), descript VARCHAR(200), week DATE)';
	mysql_query($query, connect());
}

function getcar($week){
	$query = "SELECT numcars from configs where week = '%s'";
	echo mysql_result(mysql_query($query, $week));
}

function info(){
	$query = "UPDATE configs SET make = '%s', model = '%s', color = '%s', year = %d, licenseplate = '%s', state = '%s'";
	mysql_query($query, $_POST['make'], $_POST['model'], $_POST['color'], $_POST['year'], $_POST['lp', $_POST['state'], connect());
}

function getinfo($value){
	$query = "SELECT '%s' FROM config where week = '%s'";
	mysql_result(mysql,
