<?php
ini_set('display_errors', 'On');

// $mysqli = new mysqli("oniddb.cws.oregonstate.edu","clarkje-db","9mbj026jOGfRusf4","clarkje-db");
$mysqli = new mysqli("localhost","root","root","clarkje-db");

if($mysqli->connect_errno){
	echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

switch(isset($_POST['action'])) {
  case "addAlbum":
		$query = "INSERT INTO album
								(album_id, artist_id, genre_id, name, release_date, total_tracks)
								VALUES
								(?,?,?,?,?,?)";

		if(!($stmt = $mysqli->prepare($query))){
			echo "Prepare failed: "  . $mysqli->errno . " " . $mysqli->error;
		}

		$rdate = $_POST['release_date'].'-01-01';

		$stmt->bind_param("iiissi",$_POST['album_id'],$_POST['artist_id'],
							$_POST['genre'],$_POST['name'],$rdate,
							$_POST['total_tracks']);

		$stmt->execute();

		if($stmt->error) {
				var_dump($stmt->error);
				$addAlbumSuccess = false;
		} else {
				$addAlbumSuccess = true;
		}

		$stmt->close();

  break;
}
?>

<!DOCTYPE html>
<head>
  <meta author="Jeromie Clark, Andrew Kroes">
  <title>Add Albums and Tracks</title>
  <link rel="stylesheet" href="js/jquery/jquery-ui.min.css">
	<link rel="stylesheet" href="js/bootstrap/css/bootstrap.min.css">
  <script src="js/jquery/jquery-1.12.3.min.js"></script>
  <script src="js/jquery/jquery-ui.min.js"></script>
	<script src="js/bootstrap/js/bootstrap.min.js"></script>

  <script>
  $(function() {

      $("#artist").autocomplete({
        source: "artistSearch.php",
        minLength: 2,
        select: function( event, ui ) {
					// Use preventDefault to prevent the value from showing in the textfield
					// http://stackoverflow.com/questions/7642855/autocomplete-applying-value-not-label-to-textbox
					event.preventDefault();
      		$("#artist").val(ui.item.label);
					$("#artist_id").val(ui.item.value);
        },
				focus: function(event, ui) {
					event.preventDefault();
					$("#artist").val(ui.item.label);
				}

      });

			$("#composer").autocomplete({
        source: "composerSearch.php",
        minLength: 2,
        select: function( event, ui ) {
          $("#composer").val(ui.item.label);
					$("#composer_id").val(ui.item.value);
					return false;
        },
				focus: function(event, ui) {
					event.preventDefault();
					$("#composer").val(ui.item.label);
				}
      });
    });
  </script>
</head>

<body>
		<div class="container">
		  <fieldset>

				<?php
					if (isset($addAlbumSuccess) && $addAlbumSuccess == true) {
						echo "<div class='alert alert-success' role='alert'>Album Added Successfully</div>";
					} else if (isset($addAlbumSuccess) && $addAlbumSuccess == false) {
						echo "<div class='alert alert-danger' role='alert'>Album Add Failed</div>";
					}
				?>
				<legend>Add Album</legend>
		    <form method="post" action="addAlbum.php">
		      <input type="hidden" name="action" value="addAlbum">
					<div class="form-group">
						<label for="album_name">Album Name:</label>
			      <input type="text" class="form-control" id="name" name="name">
					</div>
					<!--
		      <p>
						<div class="form-group">
			        <label for="composer">Composer: </label>
			        <input name="composer" class="form-control" value="" id="composer" placeholder="Composer Name">
						</div>
						<input type="hidden" name="composer_id" value="" id="composer_id">
		      </p>
					-->
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
		      </p>
					<p>
						<div class="form-group">

							<!-- TODO: I can't figure out hot to restrict input to 2 or 4 digits
							on these form fields.  -->

						  <label for="release_date">Release Year:</label>
			        <input type="number" min="1900" max="2016" name="release_date" class="form-control" value="" id="release_date">
				      <label for="total_tracks">Total Tracks:</label>
			        <input type="number" min="1" max="99" name="total_tracks" class="form-control" value="" id="total_tracks">
						</div>
		      </p>
					  <input type="submit" class="btn btn-primary btn-lg" value="Add Album">
		    </form>
		  </fieldset>
		</div>
</body>
