<?php
/**
 * Plugin Name: Metabolics
 * Description: Provides a simple API for creating &amp; sanitizing custom meta boxes
 * Version:     2.1
 * Author:      Lion Associates
 * Author URI:  http://www.lionassociates.com
 * License:     GPL2
 */

if ( ! defined( 'METABOLICS_VER' ) ) define( 'METABOLICS_VER', 2.1 );
if ( ! defined( 'METABOLICS_DIR' ) ) define( 'METABOLICS_DIR', dirname( __FILE__ ) );

/* 
 * Classes
 * =============================================================== */
require_once METABOLICS_DIR . '/classes/class.Metabolics.php';
require_once METABOLICS_DIR . '/classes/class.MetabolicsSection.php';
require_once METABOLICS_DIR . '/classes/class.MetabolicsField.php';


/* 
 * Fields
 * =============================================================== */
require_once METABOLICS_DIR . '/classes/fields/field.init.php';

?>