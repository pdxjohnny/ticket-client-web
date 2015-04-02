<?php
include 'Database.php';

$response = new stdClass();
$response->OK = false;

if ( $_COOKIE && $user = $database->user( $_COOKIE ) )
{
	$table = $user['school'];

	$response->OK = $database->clear( $table );
}

echo json_encode( $response );

?>
