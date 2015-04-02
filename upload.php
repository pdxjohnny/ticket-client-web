<?php
include 'Database.php';
include 'header.php';


if ( $_COOKIE )
{
	$user = $database->user( $_COOKIE );
}
else if ( $_POST )
{
	$user = $database->user( $_POST );
}

if ( $_FILES && $user )
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


	<div data-role="header">
		<h1>Upload</h1>
	</div><!-- /header -->

	<div data-role="content">

<h1>Upload csv file to database</h1>
<form id="upload_form" action="upload.php" method="POST" enctype="multipart/form-data" data-ajax="false" >
	<?php
	if ( !$user )
	{
		?>
		Username: <input type="text" name="username" />
		<br>
		Password: <input type="password" name="password" />
		<br>
		<?php
	}
	?>
	csv file: <input type="file" name="file" id="file" />
	<br>
	<input type="submit" value="Upload" name="submit">
</form>

<script type="text/javascript">
var form = document.getElementById('upload_form');

form.addEventListener("submit", function ( event ) {
	if ( !confirm("Are you sure you want to delete old data and replace with this file?") )
	{
		event.preventDefault();
	}
});
</script>

<?php
if ( $user )
{
	$database->table( $user['school'] );
}
?>

</div><!-- /content -->