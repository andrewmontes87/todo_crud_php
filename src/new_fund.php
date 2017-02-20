<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php require_once("./includes/validation_functions.php"); ?>
<?php 
// Pass values to header
global $page_title;
$page_title = "Add a new fund";

confirm_logged_in(); 

$user = User::find_by_id($_SESSION["user_id"]);


if (!$user) {
  // user ID was missing or invalid or 
  // user couldn't be found in database
  redirect_to("login.php");
}

// prep empty form variables
$form_asset_manager = '';
$form_other_asset_manager = '';
$form_name = '';

if (isset($_POST['submit'])) {
  // Process the form
  
  // validations
  $required_fields = array("asset_manager", "name");
  validate_presences($required_fields);
  
  $fields_with_max_lengths = array("name" => 30);
  validate_max_lengths($fields_with_max_lengths);

  validate_unique_fund_name($_POST["name"], $_SESSION["user_id"]);

  // update form variables if they've been posted
  // so if error, form still shows values that were just submitted
  $form_asset_manager = (isset($_POST["asset_manager"]) ? $_POST["asset_manager"] : null);
  $form_other_asset_manager = (isset($_POST["other_asset_manager"]) ? $_POST["other_asset_manager"] : null);
  $form_name = (isset($_POST["name"]) ? $_POST["name"] : "");

  validate_asset_manager_required($form_asset_manager, $form_other_asset_manager);

  if (empty($errors)) {
    // Fund class handles db and session messages
    Fund::insert_fund($_SESSION["user_id"], $_POST["asset_manager"], $_POST["name"], $_POST["other_asset_manager"]);
  }
} else {
  // This is probably a GET request
  
} // end: if (isset($_POST['submit']))

$other_asset_manager_selected = Fund::other_asset_manager_selected($form_other_asset_manager, $form_asset_manager);

?>
<?php include("./includes/layouts/header.php"); ?>
<div class="container-wrap main">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <?php echo message(); ?>
        <?php echo form_errors($errors); ?>
        <h2><i class="fa fa-plus"></i> Add a new fund</h2>
        <form 
          action="new_fund.php" 
          method="post"
        >
          <div class="form-group">
            <label for="asset_manager">What asset manager offers this fund?</label>
            <select 
              name="asset_manager" 
              class="form-control" 
              id="asset-manager-select"
            >
              <option 
                disabled 
                selected
              >Select an asset manager</option>
              <?php foreach(Fund::get_asset_manager_options() as $option) { ?>
                <option 
                  value="<?php echo $option; ?>" 
                  <?php if ($form_asset_manager == $option) echo "selected"; ?> 
                ><?php echo $option; ?></option>
              <?php } ?>
              <option 
                value="Other"      
                <?php if ($other_asset_manager_selected)    echo "selected"; ?> 
              >Other</option>
            </select>
          </div>
          <div 
            class="well <?php if (!$other_asset_manager_selected) echo 'hidden'; ?>" 
            id="other-asset-manager-select">
            <div class="form-group">
              <label for="other_asset_manager">Other asset manager:</label>
              <input 
                type="text" 
                name="other_asset_manager" 
                placeholder="Enter an asset manager" 
                class="form-control" 
                value="<?php if (!$other_asset_manager_selected) echo $form_other_asset_manager; ?>" 
              />     
            </div>            
          </div>
          <div class="form-group">
            <label for="name">Fund name:</label>
            <input 
              type="text" 
              class="form-control" 
              name="name" 
              value="<?php echo $form_name; ?>" 
              placeholder="Enter a fund name"
            />
          </div>
          <input 
            class="btn btn-primary" 
            type="submit" 
            name="submit" 
            value="Add fund" 
          />
        </form>
        <br/>
        <a class="btn btn-warning" href="dashboard.php"><i class="fa fa-arrow-left"></i> Dashboard</a>
      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?>
