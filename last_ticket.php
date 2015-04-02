<?php
include 'Database.php';

$response = new stdClass();
$response->OK = false;

if ( $_POST && $user = $database->user( $_POST ) )
{
	$table = $user['school'];

	if ( $last_ticket = $database->last_ticket( $table ) )
	{
		$response->OK = true;
		$response->last = $last_ticket;
	}
}

echo json_encode( $response );

?>
