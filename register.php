<?php
include 'Database.php';

$response = new stdClass();
$response->OK = false;

if ( $_POST )
{
	# If the user already exists and password is correct
	if ( $user = $database->user( $_POST )  )
	{
		$response->OK = true;
		$response->message = "Logged in for " . $user["school"];
	}
	# If the username is not in use, create them and their school's table
	else if ( !$database->check_user( $_POST ) &&
		isset( $_POST['username'] ) && strlen( $_POST['username'] ) > 0 &&
                isset( $_POST['passowrd'] ) && strlen( $_POST['password'] ) > 0 &&
                isset( $_POST['school'] ) && strlen( $_POST['school'] ) > 0 )
	{
		$statement = $database->db->prepare('INSERT INTO USERS ( username, password, school ) VALUES ( :username, :password, :school )');
		$statement->bindValue( ':username', $_POST['username'], SQLITE3_TEXT );
		$statement->bindValue( ':password', $_POST['password'], SQLITE3_TEXT );
		$statement->bindValue( ':school', $_POST['school'], SQLITE3_TEXT );

		if ( $result = $statement->execute() )
		{
			if ( $database->db->exec('CREATE TABLE IF NOT EXISTS `' . $_POST['school'] . '` (id INTEGER PRIMARY KEY, first TEXT DEFAULT "", last TEXT DEFAULT "", grade INTEGER DEFAULT 0, paid TEXT DEFAULT "N", ticket INTEGER DEFAULT 0, guest_paid TEXT DEFAULT "N", guest_ticket INTEGER DEFAULT 0, guest_name TEXT DEFAULT "", guest_school TEXT DEFAULT "")') )
			{
				$response->OK = true;
				$response->message = "Created a new account, data will be synced across signed in devices";
			}
		}
	}
	else
	{
		$response->message = "Username or school name already in use";
	}
}

echo json_encode( $response );

?>
