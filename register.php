<?php
include 'Database.php';

$response = new stdClass();
$response->OK = false;

if ( $_POST )
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
		}
	}
}
else
{
	?>
<form action="register.php" method="POST" >
	Username: <input type="text" name="username" />
	<br>
	Password: <input type="password" name="password" />
	<br>
	School Name: <input type="text" name="school" />
	<br>
	<input type="submit" value="Regirste" name="submit">
</form>
	<?php
}

echo json_encode( $response );

?>
