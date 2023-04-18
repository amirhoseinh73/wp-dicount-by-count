<?php

/**
 *
 * Plugin Name: wc discount by count
 * Plugin URI:  https://instagram.com/amirhoseinh73
 * Description: ایجاد تخفیف بر اساس تعداد خریداری شده از یک محصول مشخص
 * Version:     1.0
 * Author:      Amirhosein
 * Author URI:  http://instagram.com/amirhoseinh73
 * License:     
 * License URI:
 */

define("AMH_NJ_DBC_PLUGIN_NAME", "discountByCount");
define("AMH_NJ_DBC_PREFIX", "AMH_NJ");

define("AMH_NJ_DBC_FILE", __FILE__);
define("AMH_NJ_DBC_DIR_PATH", plugin_dir_path(__FILE__));
define("AMH_NJ_DBC_DIR_URL", plugin_dir_url(__FILE__));
define("AMH_NJ_DBC_JS_URL", AMH_NJ_DBC_DIR_URL . "asset/js/");
define("AMH_NJ_DBC_CSS_URL", AMH_NJ_DBC_DIR_URL . "asset/css/");
define("AMH_NJ_DBC_DIR_PATH_ADMIN", AMH_NJ_DBC_DIR_PATH . "admin/");
define("AMH_NJ_DBC_DIR_URL_ADMIN", AMH_NJ_DBC_DIR_URL . "admin/");

require_once AMH_NJ_DBC_DIR_PATH . "loader.php";
