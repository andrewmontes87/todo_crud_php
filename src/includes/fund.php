<?php
require_once('database.php');
require_once('functions.php');
require_once('validation_functions.php');

class Fund {

  //
  // form properties
  public static function get_asset_manager_options() {
    return ["Vanguard",
            "Blackrock",
            "Fidelity",
            "TIAA-CREF",
            "Dimensional Fund Advisors",
            "State Street"];
  }

  public static function get_investment_strategy_options() {
    return ["Large Value",
            "Large Blend",
            "Large Growth",
            "Mid-Cap Blend",
            "Emerging Markets",
            "Balanced"];
  }

  //
  // CRUD - database handlers
  //

  // C
  // create
  public static function insert_fund($user_id=0, $asset_manager="", $name="", $other_asset_manager="") {
    global $db;
    $safe_name = mysql_prep($name);
    // if asset_manager is other, override with other-asset-manager
    $asset_man = $asset_manager;
    if ($asset_manager == 'Other') {
      $asset_man = $other_asset_manager;
    }    
    $safe_asset_manager = mysql_prep($asset_man);
    $query  = "INSERT INTO fff_pap_funds (";
    $query .= "  user_id, asset_manager, name, investment_strategy, active_passive, description";
    $query .= ") VALUES (";
    $query .= "  {$user_id}, '{$safe_asset_manager}', '{$safe_name}', '', '', ''";
    $query .= ")";
    $result = $db->query($query);

    if ($result) {
      // Success
      $_SESSION["message"] = "Fund added!";
      $output_url  = "update_fund.php?name=";
      $output_url .= urlencode($name);
      redirect_to($output_url);   
    } else {
      // Failure
      $_SESSION["message"] = "Failed to add the fund.";
    }
  }


  // R
  // read
  public static function find_all() {
    return self::find_by_sql("SELECT * FROM fff_pap_funds");
  }
  
  public static function find_by_id_and_user_id($id=0, $user_id=0) {
    global $db;
    $result_set = self::find_by_sql("SELECT * FROM fff_pap_funds WHERE id={$id} AND user_id={$user_id} LIMIT 1");
    $found = $db->fetch_array($result_set);
    return $found;
  }
  
  public static function find_by_name_and_user_id($name="", $user_id=0) {
    global $db;
    $safe_name = $db->escape_value($name);
    $result_set = self::find_by_sql("SELECT * FROM fff_pap_funds WHERE name='{$safe_name}' AND user_id={$user_id} LIMIT 1");
    $found = $db->fetch_array($result_set);
    return $found;
  }

  public static function find_by_user_id($user_id=0) {
    global $db;
    $result_set = self::find_by_sql("SELECT * FROM fff_pap_funds WHERE user_id={$user_id}");
    return $result_set;
  }

  public static function find_by_sql($sql="") {
    global $db;
    $result_set = $db->query($sql);
    return $result_set;
  }

  // U
  // update
  public static function update_fund($id=0, 
                                     $user_id=0,
                                     $name="",
                                     $asset_manager="", 
                                     $other_asset_manager="", 
                                     $active_passive="",
                                     $investment_strategy="",
                                     $other_investment_strategy="",
                                     $other_investment_strategy="",
                                     $description="") {
    global $db;

    $safe_name = mysql_prep($name);
    $query .= "name = '{$safe_name}', ";

    $asset_man = $asset_manager;
    if ($asset_manager == 'Other') {
      $asset_man = $other_asset_manager;
    }
    $safe_asset_manager = mysql_prep($asset_man);

    $safe_active_passive = mysql_prep($active_passive);

    $investment_strat = $investment_strategy;
    if ($investment_strategy == 'Other') {
      $investment_strat = $other_investment_strategy;
    }
    $safe_investment_strategy = mysql_prep($investment_strat);

    $safe_description = mysql_prep($description);

    $query  = "UPDATE fff_pap_funds SET ";
    $query .= "asset_manager = '{$safe_asset_manager}', ";
    $query .= "active_passive = '{$safe_active_passive}', ";
    $query .= "investment_strategy = '{$safe_investment_strategy}', ";
    $query .= "description = '{$safe_description}' ";
    $query .= "WHERE id = {$id} ";
    $query .= "AND user_id = {$user_id} ";
    $query .= "LIMIT 1";
    $result = $db->query($query);

    if ($result && $db->affected_rows() == 1) {
      // Success
      $_SESSION["message"] = "Fund updated!";
      redirect_to("dashboard.php");
    } else {
      // Failure
      $_SESSION["message"] = "Nothing updated.";
    }

  }


  // D
  // delete
  public static function delete_fund($id, $user_id){
    global $db;
    $query = "DELETE FROM fff_pap_funds WHERE id = {$id} AND user_id = {$user_id} LIMIT 1";
    $result = $db->query($query);
    if ($result && $db->affected_rows() == 1) {
      // Success
      $_SESSION["message"] = "Fund deleted.";
      redirect_to("dashboard.php");
    } else {
      // Failure
      $_SESSION["message"] = "Fund deletion failed.";
      $output_url  = "update_fund.php?id=";
      $output_url .= $id;
      redirect_to($output_url);
    }
  }


  //
  // form helpers to show or hide the "other" text inputs
  public static function other_asset_manager_selected($other_asset_manager, $asset_manager){
    return isset($other_asset_manager) && $other_asset_manager && !self::asset_manager_in_preset_options($asset_manager);
  }
  private function asset_manager_in_preset_options($input) {
    return in_array($input, self::get_asset_manager_options());
  }

  public static function other_investment_strategy_selected($other_investment_strategy, $investment_strategy){
    return isset($other_investment_strategy) && $other_investment_strategy && !self::investment_strategy_in_preset_options($investment_strategy);
  }
  private function investment_strategy_in_preset_options($input) {
    return in_array($input, self::get_investment_strategy_options());
  }


}