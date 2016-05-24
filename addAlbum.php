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


?>

<!DOCTYPE html>
<head>
  <meta author="Jeromie Clark, Andrew Kroes">
  <title>Add Albums and Tracks</title>
  <link rel="stylesheet" href="js/jquery/jquery-ui.min.css">
  <script src="js/jquery/jquery-1.12.3.min.js"></script>
  <script src="js/jquery/jquery-ui.min.js"></script>
	<!--
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  -->

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
  <fieldset>
    <form method="post" action="">

      <input type="hidden" name="action" value="addAlbum">

			<label for="album_name">Album Name:</label>
      <input type="text" id="album_name">
      <p>
        <label for="composer">Composer: </label>
        <input name="composer" value="" id="composer">
				<input type="hidden" name="composer_id" value="" id="composer_id">
      </p>
      <p>
        <label for="artist">Artist: </label>
        <input name="artist" value="" id="artist">
				<input type="hidden" name="artist_id" value="" id="artist_id">
      </p>
    </form>
  </fieldset>


 <div class="ui-widget" style="margin-top:2em; font-family:Arial">
   Result:
   <div id="log" style="height: 200px; width: 300px; overflow: auto;" class="ui-widget-content"></div>
 </div>


</body>
