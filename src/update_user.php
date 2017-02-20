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
$form_investor_type = (isset($user["investor_type"]) ? $user["investor_type"] : "");
$form_investor_assets = (isset($user["investor_assets"]) ? $user["investor_assets"] : "");

if (isset($_POST['submit'])) {
  // Process the form
  
  // validations
  $required_fields = array("investor_type", "investor_assets");
  validate_presences($required_fields);

  // update form variables if they've been posted
  // so if error, form still shows values that were just submitted
  $form_investor_type = (isset($_POST["investor_type"]) ? $_POST["investor_type"] : "");
  $form_investor_assets = (isset($_POST["investor_assets"]) ? $_POST["investor_assets"] : "");
  
  if (empty($errors)) {
    // User class handles db and session messages
    User::update_user($user["id"], $form_investor_type, $form_investor_assets); 
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
        <h2><i class="fa fa-user"></i> Update your user profile <small><?php echo htmlentities($user["username"]); ?></small></h2>
        <form 
          action="update_user.php?id=<?php echo urlencode($user["id"]); ?>" 
          method="post"
          >
          <div class="form-group">
            <label for="investor_type">What type of investor are you?</label>
            <select 
              name="investor_type" 
              class="form-control"
            >
              <option 
                disabled
                <?php if (!$form_investor_type)              echo "selected"; ?> 
              >Select an investor type</option>
              <?php foreach(User::get_investor_type_options() as $option) { ?>
                <option 
                  value="<?php echo $option; ?>" 
                  <?php if ($form_investor_type == $option) echo "selected"; ?> 
                ><?php echo $option; ?></option>
              <?php } ?>
            </select>            
          </div>
          <div class="form-group">
            <label for="investor_assets">What is your approximate assets under management?</label>
            <select 
              name="investor_assets" 
              class="form-control"
            >
              <option 
                disabled 
                <?php if (!$form_investor_assets)              echo "selected"; ?> 
              >Select an asset range</option>
              <?php foreach(User::get_investor_assets_options() as $option) { ?>
                <option 
                  value="<?php echo $option; ?>" 
                  <?php if ($form_investor_assets == $option) echo "selected"; ?> 
                ><?php echo $option; ?></option>
              <?php } ?>
            </select>
          </div>
          <input 
            class="btn btn-primary" 
            type="submit" 
            name="submit" 
            value="Save" 
          />
        </form>
        <br />
        <a  class="btn btn-warning" href="dashboard.php"><i class="fa fa-arrow-left"></i> Dashboard</a>
        <br />
        <a  class="btn btn-danger" href="delete_user.php"><i class="fa fa-trash-o"></i> Delete account</a>
      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?>
