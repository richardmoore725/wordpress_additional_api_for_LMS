<?php

include_once ABSPATH . 'wp-admin/includes/plugin.php';
$plugin_info = get_plugin_data( QUIRE_API_PLUGIN_FILE );

define( 'QUIRE_API_PLUGIN_VERSION', $plugin_info['Version'] );
define( 'QUIRE_API_PLUGIN_DIR', untrailingslashit( plugin_dir_path( QUIRE_API_PLUGIN_FILE ) ) );
define( 'QUIRE_API_PLUGIN_CLASSES_DIR', QUIRE_API_PLUGIN_DIR . '/classes' );

//custom post type
define( 'AGENCY_CPT', 'agency' );
define( 'ASSIGNMENT_CPT', 'assignment' );
define( 'COURSE_CPT', 'course' );
define( 'GROUP_CPT', 'group' );

//user role
define( 'CAREGIVER_ROLE', 'caregiver' );
