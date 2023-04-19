<?php

add_action('admin_menu', 'admin_menu');

function admin_menu()
{
	add_menu_page(
		__('discount dashboard'),
		__('تنظیمات تخفیف بر اساس تعداد'),
		'manage_options',
		'settings',
		'progress_global_settings',
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
