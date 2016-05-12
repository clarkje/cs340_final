<!DOCTYPE html>
<head>
  <meta author="Jeromie Clark, Andrew Kroes">
  <title>Add Albums and Tracks</title>
  <link rel="stylesheet" href="js/jquery/jquery-ui.min.css">
  <script src="/js/jquery/jquery-ui.min.js" />
  <script src="/js/jquery/jquery-1.12.3.min.js" />

  <script>
  $(function() {
      $(".auto_composer").autoComplete({
          source: "composer_search.php",
          minLength: 3
      });
  });
  </script



</head>

<body>
  <fieldset>
    <form>
      <label>
      <input type="text" id="tf_AlbumName">
      <p><label>Composer:</label><input type="text" name="composer" value="" class="auto_composer"></p>
    </form>
  </fieldset>
</body>
