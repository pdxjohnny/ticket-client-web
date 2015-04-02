<?php
include 'Database.php';
include 'header.php';


if ( $_POST && $user = $database->user( $_POST ) )
{
	setcookie("username", $user['username'], time()+3600);
	setcookie("password", $user['password'], time()+3600);
}

if ( $_COOKIE )
{
	$user = $database->user( $_COOKIE );
}

?>



	<div data-role="header">
		<h1>Tickets</h1>
	</div><!-- /header -->

	<div data-role="content">

<?php
if ( !$user )
{
?>
<h1>View your schools students</h1>
<form action="/" method="POST" >
	Username: <input type="text" name="username" />
	<br>
	Password: <input type="password" name="password" />
	<br>
	<input type="submit" value="Login" name="submit">
</form>
<?php
}
?>

<a href="upload.php">Upload new data</a>
<!-- Filter: <input id="filter" pattern="\d*" placeholder="Student id" />  -->

<br>

<?php
if ( $user )
{
	$database->table( $user['school'] );
}
?>


</div><!-- /content -->


<?php

include 'footer.php';

?>