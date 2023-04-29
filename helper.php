<?php

if (!function_exists("AMH_NJ_exists")) {
  function AMH_NJ_exists($item)
  {
    return (isset($item) && !empty($item));
  }
}

if (!function_exists("AMH_NJ_admin_notice__success")) {
  function AMH_NJ_admin_notice__success($error_message)
  {
?>
    <div class="notice notice-success is-dismissible">
      <p><?php _e($error_message, 'amhnj'); ?></p>
    </div>
  <?php
  }
}

if (!function_exists("AMH_NJ_admin_notice__error")) {
  function AMH_NJ_admin_notice__error($error_message)
  {
  ?>
    <div class="notice notice-warning is-dismissible">
      <p><?php _e($error_message, 'amhnj'); ?></p>
    </div>
<?php
  }
}
