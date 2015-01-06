<?php
include 'header.php';

?>

<h1>View your schools students</h1>
<form action="index.php" method="POST" >
	Username: <input type="text" name="username" />
	<br>
	Password: <input type="password" name="password" />
	<br>
	<input type="submit" value="Login" name="submit">
</form>

<a href="upload.php">Upload new data</a>

<br>

<!-- Filter: <input id="filter" pattern="\d*" placeholder="Student id" />  -->

<br>

<?php
include 'Database.php';
if ( $_POST && $user = $database->user( $_POST ) )
{
	$database->table( $user['school'] );
}
?>