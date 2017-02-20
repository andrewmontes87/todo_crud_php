<?php require_once("./includes/session.php"); ?>
<?php require_once("./includes/database.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<?php require_once("./includes/validation_functions.php"); ?>
<?php require_once("./includes/fund.php"); ?>
<?php 
// Pass values to header
global $page_title;
$page_title = "Dashboard";

confirm_logged_in(); 

$user = User::find_by_id($_SESSION["user_id"]);

if (!$user) {
  // user ID was missing or invalid or 
  // user couldn't be found in database
  redirect_to("login.php");
}

$fund_set = Fund::find_by_user_id($_SESSION["user_id"]);

?>
<?php include("./includes/layouts/header.php"); ?>

<div class="container-wrap main">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <?php echo message(); ?>
        <h2>Dashboard</h2>
        <p>The dashboard is where you add mutual funds to your profile.</p>
        <p>Try adding a mutual fund now!</p>
        <hr>
        <h3><i class="fa fa-list"></i> Your Portfolio</h3>
        <a href="new_fund.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add a mutual fund</a>
        <?php if ($db->num_rows($fund_set)) { ?>
          <ul class="list-group">
          <?php while($fund = $db->fetch_assoc($fund_set)) { ?>
            <li class="list-group-item">
              <h4><?php echo htmlentities($fund["name"]); ?></h4> 
              <ul class="dashboard-character-details">
                <li>
                  Asset manager: 
                  <strong><?php echo ucfirst(htmlentities($fund["asset_manager"])); ?></strong>
                </li>
                <?php if ($fund["active_passive"]) { ?>
                  <li>
                    Active/passive investing: 
                    <strong><?php echo ucfirst(htmlentities($fund["active_passive"])); ?></strong>
                  </li>
                <?php } ?>
                <?php if ($fund["investment_strategy"]) { ?>
                  <li>
                    Investment strategy: 
                    <strong><?php echo ucfirst(htmlentities($fund["investment_strategy"])); ?></strong>
                  </li>
                <?php } ?>
                <?php if ($fund["description"]) { ?>
                  <li>
                    <blockquote><?php echo htmlentities($fund["description"]); ?></blockquote>
                  </li>
                <?php } ?>
                <li></li>
              </ul>
              <a href="update_fund.php?id=<?php echo $fund["id"]; ?>"><i class="fa fa-pencil"></i> Edit</a>
            </li>
          <?php } ?>
          </ul>
        <?php } else { ?>
          <h3>Your portfolio is empty - get started by adding a new fund to your portfolio!</h3>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<?php include("./includes/layouts/footer.php"); ?> 
