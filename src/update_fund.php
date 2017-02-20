<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php require_once("./includes/validation_functions.php"); ?>
<?php require_once("./includes/fund.php"); ?>
<?php 
// Pass values to header
global $page_title;
$page_title = "Update Fund";

confirm_logged_in();

$user = User::find_by_id($_SESSION["user_id"]);

if (!$user) {
  // user ID was missing or invalid or 
  // user couldn't be found in database
  redirect_to("login.php");
}

// if it's a get with a name or id paramater, 
// we came from new fund or edit fund buttons
if ( isset($_GET["id"]) || isset($_GET["name"]) ) {
  // use the id or name to find the fund
  if (isset($_GET["id"])) {
    $fund = Fund::find_by_id_and_user_id($_GET["id"], $_SESSION["user_id"]);
  } elseif (isset($_GET["name"])) {
    $fund = Fund::find_by_name_and_user_id($_GET["name"], $_SESSION["user_id"]);
  }
  // get name for form and page title
  $page_title .= ": " . $fund["name"];

  // update form if fund already has values
  $form_id =                        (isset($fund["id"])                  ? $fund["id"]                  : null);
  $form_asset_manager =             (isset($fund["asset_manager"])       ? $fund["asset_manager"]       : null);
  $form_other_asset_manager =       (isset($fund["asset_manager"])       ? $fund["asset_manager"]       : null);
  $form_name =                      (isset($fund["name"])                ? $fund["name"]                : null);
  $form_active_passive =            (isset($fund["active_passive"])      ? $fund["active_passive"]      : null);
  $form_investment_strategy =       (isset($fund["investment_strategy"]) ? $fund["investment_strategy"] : null);
  $form_other_investment_strategy = (isset($fund["investment_strategy"]) ? $fund["investment_strategy"] : null);
  $form_description =               (isset($fund["description"])         ? $fund["description"]         : null);

  if (!$fund) {
    // fund ID was missing or invalid or 
    // fund couldn't be found in database
    redirect_to("dashboard.php");
  }
}

if (isset($_POST['submit'])) {
  // validations
  $required_fields = array("asset_manager", "name", "id");
  validate_presences($required_fields);
  
  $fields_with_max_lengths = array("description" => 255);
  validate_max_lengths($fields_with_max_lengths);

  // update form if $_POST already has values
  // so user input is still shown in case of errors
  $form_id =                           (isset($_POST["id"])                        ? $_POST["id"]                        : null);
  $form_asset_manager =                (isset($_POST["asset_manager"])             ? $_POST["asset_manager"]             : null);
  $form_other_asset_manager =          (isset($_POST["other_asset_manager"])       ? $_POST["other_asset_manager"]       : null);
  $form_name =                         (isset($_POST["name"])                      ? $_POST["name"]                      : null);
  $form_active_passive =               (isset($_POST["active_passive"])            ? $_POST["active_passive"]            : null);
  $form_investment_strategy =          (isset($_POST["investment_strategy"])       ? $_POST["investment_strategy"]       : null);
  $form_other_investment_strategy =    (isset($_POST["other_investment_strategy"]) ? $_POST["other_investment_strategy"] : null);
  $form_description =                  (isset($_POST["description"])               ? $_POST["description"]               : null);

  // use id to look the character up in the database
  $fund = Fund::find_by_id_and_user_id($_SESSION["user_id"], $form_id);

  // if there's error because it's species=other with no other_species...
  // use what the character has in the db instead of the POST
  if (validate_asset_manager_required($form_asset_manager, $form_other_asset_manager)) {
    $form_asset_manager =              (isset($fund["asset_manager"])              ? $fund["asset_manager"]              : null);
    $form_other_asset_manager =        (isset($fund["asset_manager"])              ? $fund["asset_manager"]              : null);
  }
  // same with workplace
  if ($form_investment_strategy == "Other" && !$form_other_investment_strategy) {
    $form_investment_strategy =       (isset($fund["investment_strategy"])         ? $fund["investment_strategy"]        : null);
    $form_other_investment_strategy = (isset($fund["investment_strategy"])         ? $fund["investment_strategy"]        : null);
  }

  // keep page_title reading from db instead of POST
  $page_title .= ": " . $fund["name"];

  if (empty($errors)) {
    // Perform Update
    Fund::update_fund($form_id, 
                      $_SESSION["user_id"], 
                      $form_name,
                      $form_asset_manager, 
                      $form_other_asset_manager, 
                      $form_active_passive,
                      $form_investment_strategy,
                      $form_other_investment_strategy,
                      $form_other_investment_strategy,
                      $form_description);
  }
} else {
  // This is probably a GET request
} // end: if (isset($_POST['submit']))

$other_investment_strategy_selected = Fund::other_investment_strategy_selected($form_other_investment_strategy, $form_investment_strategy);
$other_asset_manager_selected = Fund::other_asset_manager_selected($form_other_asset_manager, $form_asset_manager);

?>
<?php include("./includes/layouts/header.php"); ?>
<div class="container-wrap main">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <?php echo message(); ?>
        <?php echo form_errors($errors); ?>
        <h2><i class="fa fa-pencil"></i> Update fund</h2>
        <form 
          action="update_fund.php" 
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
                <?php if ($other_asset_manager_selected)                            echo "selected"; ?> 
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
                value="<?php if ($other_asset_manager_selected) echo $form_other_asset_manager; ?>"
              />     
            </div>            
          </div>
          <div class="form-group">
            <label for="name">Fund name:</label>
            <input 
              type="text" 
              name="name"
              placeholder="Enter a fund name"
              class="form-control"
              required 
              value="<?php echo $form_name; ?>" 
            />
          </div>
          <div class="form-group">
            <label for="active_passive">Active or passive investing:</label>
            <label class="radio-inline">
              <input 
                type="radio" 
                name="active_passive" 
                id="active_passive1" 
                value="Active" 
                <?php if ($form_active_passive == "Active") echo "checked"; ?> 
              />
              Active
            </label>
            <label class="radio-inline">
              <input 
                type="radio" 
                name="active_passive" 
                id="active_passive2" 
                value="Passive"
                <?php if ($form_active_passive == "Passive") echo "checked"; ?> 
              />
              Passive
            </label>
          </div>
          <div class="form-group">
            <label for="investment_strategy">Fund investment strategy:</label>
            <select 
              name="investment_strategy" 
              class="form-control" 
              id="investment-strategy-select"
            >
              <option 
                disabled 
                selected>
              Select an investment strategy</option>
              <?php foreach(Fund::get_investment_strategy_options() as $option) { ?>
                <option 
                  value="<?php echo $option; ?>" 
                  <?php if ($form_investment_strategy == $option) echo "selected"; ?> 
                ><?php echo $option; ?></option>
              <?php } ?>
              <option 
                value="Other"      
                <?php if ($other_investment_strategy_selected)             echo "selected"; ?> 
              >Other</option>
            </select>
          </div>
          <div 
            class="well <?php if (!$other_investment_strategy_selected) echo 'hidden'; ?>"
            id="other-investment-strategy-select">
            <div class="form-group">
              <label for="other_investment_strategy">Other investment_strategy:</label>
              <input 
                type="text" 
                name="other_investment_strategy"
                placeholder="Enter an investment_strategy"
                class="form-control"
                value="<?php if ($other_investment_strategy_selected) echo $form_other_investment_strategy; ?>"
              />
            </div>
          </div>
          <div class="form-group">
            <label for="description">Fund description:</label>
            <textarea 
              class="form-control" 
              name="description" 
              rows="3"><?php echo $form_description; ?></textarea>
          </div>
          <input 
            type="hidden"
            name="id" 
            value="<?php echo $form_id; ?>" 
          />
          <input 
            class="btn btn-primary" 
            type="submit" 
            name="submit" 
            value="Save"
          />
        </form>
        <br />
        <a href="dashboard.php" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Dashboard</a><br />
        <br />
        <a href="delete_fund.php?id=<?php echo $form_id; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete fund from portfolio</a>  
      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?>
