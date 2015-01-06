<?php
include 'Database.php';
include 'header.php';

function shutdown()
{ 
	posix_kill(posix_getpid(), SIGHUP); 
}

if ( $_POST && $user = $database->user( $_POST ) )
{
	$table = $user['school'];

	$upload_dir = dirname(__FILE__) . '/csv/';
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);

	if ( 0 == strcmp( $extension, "csv" ) && $_FILES["file"]["size"] < 200000 )
		{
		if ( $_FILES["file"]["error"] > 0 )
		{
			if ( $_FILES["file"]["error"] == 4  )
			{
				echo "<script type='text/javascript'>alert('No file selected');</script>";
			}
			else
			{
				echo "<script type='text/javascript'>alert('ERROR: " . $_FILES["file"]["error"] . "');</script>";
			}
		}
		else
		{
			echo "<script type='text/javascript'>alert('File uploaded');</script>";
			move_uploaded_file( $_FILES["file"]["tmp_name"], $upload_dir . $_FILES["file"]["name"] );
			$import = 'python csv_to_sql.py "csv/' .  $_FILES["file"]["name"] . '" "' . $table . '"';
			shell_exec( $import );
			header( 'Location: http://tickets.codingclubpdx.org/' );
		}
	}
	else
	{
		echo "<script type='text/javascript'>alert('Invalid file');</script>";
	}
}
?>

<h1>Upload csv file to database</h1>
<form action="upload.php" method="POST" enctype="multipart/form-data" >
	Username: <input type="text" name="username" />
	<br>
	Password: <input type="password" name="password" />
	<br>
	csv file: <input type="file" name="file" id="file" />
	<br>
	<input type="submit" value="Upload" name="submit">
</form>
<?php
if ( $_POST && $user = $database->user( $_POST ) )
{
	$database->table( $user['school'] );
}
?>
