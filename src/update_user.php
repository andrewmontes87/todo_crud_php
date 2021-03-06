<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php require_once("./includes/validation_functions.php"); ?>
<?php 
// Pass values to header
global $page_title;
$page_title = "Update User";

confirm_logged_in(); 

$user = User::find_by_id($_SESSION["user_id"]);

if (!$user) {
  // user ID was missing or invalid or 
  // user couldn't be found in database
  redirect_to("login.php");
}

// prep empty form variables
$form_email = (isset($user["email"]) ? $user["email"] : "");

if (isset($_POST['submit'])) {
  // Process the form
  
  // validations
  $required_fields = array("email");
  validate_presences($required_fields);

  // validate unique email
  validate_unique_email($_POST["email"]);

  // update form variables if they've been posted
  // so if error, form still shows values that were just submitted
  $form_email = (isset($_POST["email"]) ? $_POST["email"] : "");
  
  if (empty($errors)) {
    // User class handles db and session messages
    User::update_user($user["id"], $form_email); 
  }
} else {
  // This is probably a GET request
  
} // end: if (isset($_POST['submit']))

?>
<?php include("./includes/layouts/header.php"); ?>
<div class="container-wrap main">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <?php echo message(); ?>
        <?php echo form_errors($errors); ?>
        <h2><i class="fa fa-user"></i> Update your user profile</h2>

        <p><strong>Username: <?php echo htmlentities($user["username"]); ?></strong> <small><i>(can't be changed)</i></small></p>

        <p><strong>Date joined: </strong> <?php echo date('Y-m-d g:i:s', strtotime($user['created_at'])) ; ?> </p>
        <p><strong>Last updated: </strong> <?php echo date('Y-m-d g:i:s', strtotime($user['updated_at'])) ; ?> </p>

        <form 
          action="update_user.php?id=<?php echo urlencode($user["id"]); ?>" 
          method="post"
          >
          <div class="form-group">

            <label for="email">Email</label>
            <input 
              type="email" 
              name="email"
              placeholder="Enter your email"
              class="form-control"
              value="<?php echo htmlentities($form_email); ?>" />
          </div>

          <div class="form-group">
            <input 
              class="btn btn-primary" 
              type="submit" 
              name="submit" 
              value="Save" 
            />           
          </div>
        </form>
        <div class="form-group">
          <a  class="btn btn-warning" href="dashboard.php"><i class="fa fa-arrow-left"></i> Dashboard</a>
        </div>
        <div class="form-group">
          <a  class="btn btn-danger" href="delete_user.php"><i class="fa fa-trash-o"></i> Delete account</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?>
