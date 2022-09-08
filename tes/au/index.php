<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>maribelajarcoding.com - Membuat Autocomplete JQuery Database Mysql PHP</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
 

  <form method="POST">
    <label >Nama Negara: </label>
    <input type="text" id="search_name">
  </form>


</body>
</html>


  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <script>
  $( function() {

    $( "#search_name" ).autocomplete({
      source: "../../process.php?type=search_name_auto"
    });
  });
  </script>