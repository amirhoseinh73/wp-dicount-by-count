<?php

require_once AMH_NJ_DBC_DIR_PATH . "helper.php";
require_once AMH_NJ_DBC_DIR_PATH . "config.php";

date_default_timezone_set('Asia/Tehran');

require_once AMH_NJ_DBC_DIR_PATH . 'single-product-config.php';

if (is_admin()) require_once AMH_NJ_DBC_DIR_PATH_ADMIN . 'admin.php';

loadAssets();
