<!-- from HTMLexample.php -->
<?php
ini_set('display_errors', 'On');

//Connects to the database
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","clarkje-db","9mbj026jOGfRusf4","clarkje-db");
if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

switch($_POST['action']) {
  case "addAlbum":

  break;
}

$success_addAlbum = true;

?>

<!DOCTYPE html>
<head>
  <meta author="Jeromie Clark, Andrew Kroes">
  <title>Add Albums and Tracks</title>
  <link rel="stylesheet" href="js/jquery/jquery-ui.min.css">
	<link href="js/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script src="js/jquery/jquery-1.12.3.min.js"></script>
  <script src="js/jquery/jquery-ui.min.js"></script>
	<script src="js/bootstrap/js/bootstrap.min.js"></script>

  <script>
  $(function() {
      function log( message ) {
        $( "<div>" ).text( message ).prependTo( "#log" );
        $( "#log" ).scrollTop( 0 );
      }

      $("#tf_artist").autocomplete({
        source: "artistSearch.php",
        minLength: 2,
        select: function( event, ui ) {
      		$("#artist").val(ui.item.label);
					$("#artist_id").val(ui.item.value);
        }
      });

			$("#composer").autocomplete({
        source: "composerSearch.php",
        minLength: 2,
        select: function( event, ui ) {
          $("#composer").val(ui.item.label);
					$("#composer_id").val(ui.item.value);
					return false;
        }
      });
    });
  </script>
</head>

<body>
		<div class="container">
		  <fieldset>

				<?php
					if ($success_addAlbum) {
						echo "<div class='alert alert-success' role='alert'>Album Added Successfully</div>";
					} else if ($fail_addAlbum) {
						echo "<div class='alert alert-danger' role='alert'>Album Add Failed</div>";
					}
				?>
				<legend>Add Album</legend>
		    <form method="post" action="">
		      <input type="hidden" name="action" value="addAlbum">
					<div class="form-group">
						<label for="album_name">Album Name:</label>
			      <input type="text" class="form-control" id="album_name">
					</div>
		      <p>
						<div class="form-group">
			        <label for="composer">Composer: </label>
			        <input name="composer" class="form-control" value="" id="composer" placeholder="Composer Name">
						</div>
						<input type="hidden" name="composer_id" value="" id="composer_id">
		      </p>
		      <p>
						<div class="form-group">
			        <label for="artist">Artist: </label>
			        <input name="artist" class="form-control" value="" id="artist" placeholder="Artist Name">
					</div>
					<input type="hidden" name="artist_id" value="" id="artist_id">
		      </p>
					<p>
						<div class="form-group">
			        <label for="genre">Genre: </label>
							<select class="form-control" name="genre">

							<?php
								// Show the available genres in a dropdown

								$query = "SELECT genre_id, description FROM genre";
								if(!($stmt = $mysqli->prepare($query))){
									echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
								}

								$stmt->execute();
								$stmt->bind_result($genre_id, $description);

								while($stmt->fetch()) {
									echo "<option value='".$genre_id."'>".$description."</option>";
								}
							?>

							</select>
					</div>
						<input type="hidden" name="genre_id" value="" id="genre_id">
		      </p>
					<p>
						<div class="form-group">
			        <label for="release_date">Release Year:</label>
			        <input type="number" max=2100 name="release_date" class="form-control" value="" id="release_date" maxlength=4 numeric>
				      <label for="total_tracks">Total Tracks:</label>
			        <input type="number" max=99 name="total_tracks" class="form-control" value="" id="total_tracks" maxlength=4>
						</div>
						<input type="hidden" name="total_tracks" value="" id="total_tracks" size="3">
		      </p>
					  <button type="button" class="btn btn-primary btn-lg">Add Album</button>
		    </form>
		  </fieldset>
		</div>
</body>
