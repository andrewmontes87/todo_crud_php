<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php require_once("./includes/validation_functions.php"); ?>
<?php require_once("./includes/fund.php"); ?>
<?php 
// Pass values to header
global $page_title;
$page_title = "Delete Fund";

confirm_logged_in();

$user = User::find_by_id($_SESSION["user_id"]);

if (!$user) {
  // user ID was missing or invalid or 
  // user couldn't be found in database
  redirect_to("login.php");
}

$form_name = '';

// if it's a get request with an id,
// we came from update page, not POST
if (isset($_GET["id"])) {
  // use the id to find the fund
  $form_id = $_GET["id"];
  $fund = Fund::find_by_id_and_user_id($form_id, $_SESSION["user_id"]);
  // grab name for the form and title
  $form_name = (isset($fund["name"]) ? $fund["name"] : null);
  $page_title .= ": " . $form_name;

  if (!$fund) {
    // fund ID was missing or invalid or 
    // fund couldn't be found in database
    redirect_to("dashboard.php");
  }
}

if (isset($_POST['submit'])) {
  // get the fund by post id
  $fund = Fund::find_by_id_and_user_id($_POST["id"], $_SESSION["user_id"]);

  // grab name for the form and title in case error posting
  $form_id =   (isset($fund["id"])   ? $fund["id"]   : null);
  $form_name = (isset($fund["name"]) ? $fund["name"] : null);
  $page_title .= ": " . $form_name;

  // validations
  $required_fields = array("delete");
  validate_presences($required_fields);

  if (empty($errors)) {
    // Fund class handles db and session messages
    Fund::delete_fund($_POST["id"], $_SESSION["user_id"]);
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
        <h2><i class="fa fa-trash-o"></i> Delete fund</h2>
        <p>Are you sure you want to delete this fund from your portfolio?</p>
        <p>User: <strong><?php echo htmlentities($_SESSION["username"]); ?></strong></p>
        <p>Fund: <strong><?php echo htmlentities($form_name); ?></strong></p>
        <form 
          action="delete_fund.php" 
          method="post" 
          class="login-form"
        >
          <input 
            type="hidden" 
            name="delete"
            value="true" 
          />
          <input 
            type="hidden" 
            name="name"
            value="<?php echo $form_name; ?>"  
          />
          <input 
            type="hidden" 
            name="id"
            value="<?php echo $form_id; ?>" 
          />
          <input 
            class="btn btn-danger" 
            type="submit" 
            name="submit" 
            value="Delete" 
          />
        </form>
        <a href="update_fund.php?id=<?php echo $form_id;?>" class="btn btn-primary"><i class="fa fa-ban"></i> Cancel - don't delete this fund</a>
      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?>
