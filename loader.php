<?php

require_once AMH_NJ_DBC_DIR_PATH . "helper.php";
require_once AMH_NJ_DBC_DIR_PATH . "config.php";

if (is_plugin_inactive("woocommerce/woocommerce.php")) {
  $error_message = "افزونه ووکامرس باید فعال باشد!";
  add_action('admin_notices', function () use ($error_message) {
    AMH_NJ_admin_notice__error($error_message);
  }, 10, 1);
}

date_default_timezone_set('Asia/Tehran');

require_once AMH_NJ_DBC_DIR_PATH . 'single-product-config.php';

if (is_admin()) require_once AMH_NJ_DBC_DIR_PATH_ADMIN . 'admin.php';

loadAssets();
