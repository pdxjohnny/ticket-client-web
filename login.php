<?php
include 'Database.php';

$response = new stdClass();
$response->OK = false;

if ( $_POST && $database->user( $_POST ) )
{
	$response->OK = true;
}

echo json_encode( $response );

?>