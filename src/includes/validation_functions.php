<?php
require_once("user.php");
require_once("fund.php");

$errors = array();

function fieldname_as_text($fieldname) {
  $fieldname = str_replace("_", " ", $fieldname);
  $fieldname = ucfirst($fieldname);
  return $fieldname;
}

// * presence
// use trim() so empty spaces don't count
// use === to avoid false positives
// empty() would consider "0" to be empty
function has_presence($value) {
  return isset($value) && $value !== "";
}

function validate_presences($required_fields) {
  global $errors;
  foreach($required_fields as $field) {
    $value = trim($_POST[$field]);
    if (!has_presence($value)) {
      $errors[$field] = fieldname_as_text($field) . " can't be blank";
    }
  }
}

function has_max_length($value, $max) {
  return strlen($value) <= $max;
}

function validate_max_lengths($fields_with_max_lengths) {
  global $errors;
  // Expects an assoc. array
  foreach($fields_with_max_lengths as $field => $max) {
    $value = trim($_POST[$field]);
    if (!has_max_length($value, $max)) {
      $errors[$field] = fieldname_as_text($field) . " is too long";
    }
  }
}


function validate_password_repeated($password, $repeat) {
  global $errors;

  if ($password != $repeat) {
    $errors["password-repeat"] = "Passwords must match.";
  }

}

function validate_unique_username($username) {
  global $errors;
  $user = User::find_by_username($username);
  if ($user) {
    $errors["unique-username"] = "That username is already taken.";
  }


}


function validate_unique_fund_name($name, $user_id) {
  global $errors;
  $fund = Fund::find_by_name_and_user_id($name, $user_id);
  if ($fund) {
    $errors["unique-fund-name"] = "You're already using that name for another fund.";
  }
}


function validate_asset_manager_required($form_asset_manager, $form_other_asset_manager) {
  global $errors;
  if ($form_asset_manager == "Other" && !$form_other_asset_manager) {
    $errors["other-blank"] = "Choose a value for asset manager.";
    return true;
  } else {
    return false;
  }
}










?>