<?php
include 'Database.php';

$response = new stdClass();
$response->OK = false;

if ( $_POST && isset( $_POST['id'] ) && $user = $database->user( $_POST ) )
{
	$table = $user['school'];

	$query = 'SELECT * FROM `' . $table . '` WHERE id=:id';
	$statement = $database->db->prepare( $query );
	$statement->bindValue( ":id", $_POST["id"], SQLITE3_INTEGER );
	
	$result = $statement->execute();

	if ( $response = $result->fetchArray(SQLITE3_ASSOC) )
	{
		$response['OK'] = true;
	}
	else
	{
		$response = new stdClass();
		$response->OK = false;
	}
}

echo json_encode( $response );

?>