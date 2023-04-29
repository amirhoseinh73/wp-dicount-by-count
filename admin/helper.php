<?php

if (!function_exists("AMH_NJ_loadAssetsAdmin")) {
    function AMH_NJ_loadAssetsAdmin()
    {
        function AMH_NJ_enqueue_admin()
        {
            wp_enqueue_style('amh-nj-dbc-admin-style', AMH_NJ_DBC_DIR_URL_ADMIN . 'css/style.css', array(), '1.0.0');
        }

        add_action('admin_enqueue_scripts', 'AMH_NJ_enqueue_admin');
    }
}
