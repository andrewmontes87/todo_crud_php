<?php
require_once("user.php");
require_once("note.php");

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

function has_min_length($value, $min) {
  return strlen($value) >= $min;
}

function validate_min_lengths($fields_with_min_lengths) {
  global $errors;
  // Expects an assoc. array
  foreach($fields_with_min_lengths as $field => $min) {
    $value = trim($_POST[$field]);
    if (!has_min_length($value, $min)) {
      $errors[$field] = fieldname_as_text($field) . " is too short - must be at least " . str($min) . " characters long" ;
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

function validate_unique_email($email) {
  global $errors;
  $user = User::find_by_email($email);
  if ($user) {
    $errors["unique-email"] = "That email is already taken.";
  }
}


?>