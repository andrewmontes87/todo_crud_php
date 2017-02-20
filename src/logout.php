<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php require_once("./includes/validation_functions.php"); ?>
<?php require_once("./includes/user.php"); ?>
<?php 
// Pass values to header
global $page_title;
$page_title = "Logout";

confirm_logged_in();

$user = User::find_by_id($_SESSION["user_id"]);

if (!$user) {
  // user ID was missing or invalid or 
  // user couldn't be found in database
  redirect_to("login.php");
}

if (isset($_POST['submit'])) {

  // validations
  $required_fields = array("logout");
  validate_presences($required_fields);

  if (empty($errors)) {
    // $user = User::find_by_id($_SESSION["user_id"]);
    // if (!$user) {
    //   // admin ID was missing or invalid or 
    //   // admin couldn't be found in database
    //   redirect_to("login.php");
    // }
    // Success
    $_SESSION["message"] = "User logged out.";
    $_SESSION["user_id"] = null;
    $_SESSION["username"] = null;
    redirect_to("login.php");
  } 
} else {
  // This is probably a GET request
}
?>
<?php include("./includes/layouts/header.php"); ?>
<div class="container-wrap main">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <?php echo message(); ?>
        <h2><i class="fa fa-sign-out"></i> Logout</h2>
        <p>Are you sure you want to logout, <strong><?php echo htmlentities($_SESSION["username"]); ?></strong>?</p>
        <form 
          action="logout.php" 
          method="post" 
          class="login-form"
        >
          <input 
            type="hidden" 
            value="true" 
            name="logout"
          />
          <input 
            class="btn btn-danger" 
            type="submit" 
            name="submit" 
            value="Logout" 
          />
        </form>
        <a href="dashboard.php" class="btn btn-primary"><i class="fa fa-arrow-right"></i> No, stay logged in</a>
      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?>
