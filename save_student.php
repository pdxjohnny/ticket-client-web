<?php
include 'Database.php';

$response = new stdClass();
$response->OK = false;

if ( $_POST && $user = $database->user( $_POST ) )
{
	$student = json_decode($_POST['student'], true);

	$table = $user['school'];
	
	$checked_in = 0;
	if ( 0 == strcmp($student['checked_in'], "Y") )
	{
		$checked_in = 1;
	}

	$query = 'INSERT OR REPLACE INTO `' . $table . '` VALUES ( :id, :first, :last, :grade, :paid, :ticket, :guest_paid, :guest_ticket, :guest_name, :guest_school, :checked_in )';

	$statement = $database->db->prepare( $query );
	$statement->bindValue( ":id",				$student["id"],				SQLITE3_INTEGER );
	$statement->bindValue( ":first",			$student["first"],			SQLITE3_TEXT );
	$statement->bindValue( ":last",				$student["last"],			SQLITE3_TEXT );
	$statement->bindValue( ":grade",			$student["grade"],			SQLITE3_INTEGER );
	$statement->bindValue( ":paid",				$student["paid"],			SQLITE3_TEXT );
	$statement->bindValue( ":ticket",			$student["ticket"],			SQLITE3_INTEGER );
	$statement->bindValue( ":guest_paid",			$student["guest_paid"],			SQLITE3_TEXT );
	$statement->bindValue( ":guest_ticket",			$student["guest_ticket"],		SQLITE3_INTEGER );
	$statement->bindValue( ":guest_name",			$student["guest_name"],			SQLITE3_TEXT );
	$statement->bindValue( ":guest_school",			$student["guest_school"],		SQLITE3_TEXT );
	$statement->bindValue( ":checked_in",			$checked_in,				SQLITE3_INTEGER );
	
	if ( $result = $statement->execute() )
	{
		$response->OK = true;
	}
}

echo json_encode( $response );

?>
