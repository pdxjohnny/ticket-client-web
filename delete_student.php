<?php
include 'Database.php';

$response = new stdClass();
$response->OK = false;

if ( $_POST && $user = $database->user( $_POST ) )
{
	$table = $user['school'];

	$query = 'DELETE FROM `' . $table . '` WHERE id=:id';
	$statement = $database->db->prepare( $query );
	$statement->bindValue( ":id", $_POST["id"], SQLITE3_INTEGER );
	
	$result = $statement->execute();

	if ( $result = $statement->execute() )
	{
		$response->OK = true;
	}
}

echo json_encode( $response );

?>