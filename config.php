<?php

function loadAssets()
{
  function AMH_NJ_enqueue_css_js()
  {
    wp_enqueue_style(AMH_NJ_DBC_PREFIX . AMH_NJ_DBC_PLUGIN_NAME, AMH_NJ_DBC_CSS_URL . 'style.css', array(), '1.5.0');
  }

  add_action('wp_enqueue_scripts', 'AMH_NJ_enqueue_css_js');
}
