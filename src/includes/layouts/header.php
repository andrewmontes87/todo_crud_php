<?php 
global $page_title;
if ($page_title) {
  $page_title = "todo_crud_php: " . $page_title;
} else {
  $page_title = "todo_crud_php";
}
?>
<!doctype html>
<html lang="en">
  <head>
    <title><?php echo $page_title; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="css/style.css" media="all" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <div class="login-nav">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <?php include("./includes/layouts/login_nav.php"); ?>
          </div>
        </div>
      </div>
    </div>
