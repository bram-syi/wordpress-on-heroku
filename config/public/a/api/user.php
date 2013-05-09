<?php

require_once(__DIR__. '/api.php');

class UserApi extends Api {
  // Join user fields, using $field as the join variable and $on as blog_id
  public static function join(ApiQuery &$query, $on, $field="user", $group = "user") {
    $query->fields("
      $field.display_name as {$group}_display_name,
      $field.display_name as {$group}_name,
      fn_$field.meta_value as {$group}_first,
      ln_$field.meta_value as {$group}_last,
      $field.user_email as {$group}_email,
      $field.id as {$group}_id");

    $query->table("
      LEFT JOIN wp_users $field on $field.id=$on
      LEFT JOIN wp_usermeta fn_$field on fn_$field.user_id = $field.id AND fn_$field.meta_key='first_name'
      LEFT JOIN wp_usermeta ln_$field on ln_$field.user_id = $field.id AND ln_$field.meta_key='last_name'");

    return $field;
  }

}
