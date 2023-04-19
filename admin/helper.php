<?php

if (!function_exists("loadAssetsAdmin")) {
    function loadAssetsAdmin()
    {
        function enqueue_admin()
        {
            wp_enqueue_style('admin-style', AMH_NJ_DBC_DIR_URL_ADMIN . 'css/style.css', array(), '1.0.0');
        }

        add_action('admin_enqueue_scripts', 'enqueue_admin');
    }
}
