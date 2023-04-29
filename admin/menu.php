<?php

add_action('admin_menu', 'AMH_NJ_admin_menu');

function AMH_NJ_admin_menu()
{
	add_menu_page(
		__('discount dashboard'),
		__('تنظیمات تخفیف بر اساس تعداد'),
		'manage_options',
		'settings',
		'AMH_NJ_progress_global_settings',
		'dashicons-edit-large', //dashicons-schedule,dashicons-businessperson
		3
	);

	// add_submenu_page(
	// 	'settings',
	// 	__( 'progress dashboard' ),
	// 	__( 'Settings' ),
	// 	'manage_options',
	// 	'settings',
	// 	'progress_global_settings'
	// );
}
