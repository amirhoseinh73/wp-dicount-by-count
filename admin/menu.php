<?php

add_action( 'admin_menu', 'admin_menu' );

function admin_menu() {
	add_menu_page(
		__( 'progress dashboard' ),
		__( 'تنظیمات تخفیف ناوبری' ),
		'manage_options',
		'settings',
        'progress_global_settings',
		'dashicons-schedule',//dashicons-schedule,dashicons-businessperson
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

    // add_menu_page(
	// 	__( 'Vira dashborad', 'vira' ),
	// 	__( 'مخاطبین ویرا', 'vira' ),
	// 	'manage_options',
	// 	'vira-contacts',
	// 	'vira_admin_page_contacts_all',
	// 	'dashicons-schedule',//dashicons-schedule,dashicons-businessperson
	// 	3
	// );

	// add_submenu_page(
	// 	'vira-contacts',
	// 	__( 'Vira dashborad', 'vira' ),
	// 	__( 'مخاطبین لایسنس ویرا', 'vira' ),
	// 	'manage_options',
	// 	'vira-contacts-license',
	// 	'vira_admin_page_contacts_license'
	// );

	// add_submenu_page(
	// 	'vira-contacts',
	// 	__( 'Vira dashborad', 'vira' ),
	// 	__( 'مخاطبین دمو ویرا', 'vira' ),
	// 	'manage_options',
	// 	'vira-contacts-demo',
	// 	'vira_admin_page_contacts_demo'
	// );
}