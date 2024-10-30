<?php
/**
 * CSV Importer.
 *
 * CSV Importer plugin file.
 *
 * @package   Smackcoders\CI
 * @copyright Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name:CSV Importer
 * Version:     2.0
 * Plugin URI:  https://www.smackcoders.com/wp-ultimate-csv-importer-pro.html
 * Description: A plugin that helps to import the data's from a CSV file.
 * Author:      Smackcoders
 * Author URI:  https://www.smackcoders.com/wordpress.html
 * Text Domain: csv-import
 * Domain Path: /languages
 * License:     GPL v3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

include_once('sm_main_control.php');
include_once('sm_ajax_class.php');

register_activation_hook( __FILE__, array('SmackCSVImportHelper','sm_activate_function' ));

function SmackCSVImportEnqueue()	{
	wp_enqueue_script('smack_csv_import', plugins_url('csvimport.js', __FILE__));
	wp_enqueue_script('smack_csv_import_bootstrap_min_js', plugins_url('js/bootstrap.min.js', __FILE__));
	wp_enqueue_style('smack_csv_import_bootstrap_min_css', plugins_url('css/bootstrap.min.css', __FILE__));
}

function SmackCSVImportAddEnqueue()	{
	add_action('admin_enqueue_scripts', 'SmackCSVImportEnqueue');
}

add_action('admin_menu','SmackCSVImportAddMenu');
function SmackCSVImportAddMenu() {
	$my_page = add_menu_page('CSV Import','CSV Import','manage_options','sm_csv_importer','SmackCSVImportNewUpload', WP_PLUGIN_URL . '/csv-import/images/csv-import.png');
	add_action('load-'.$my_page, 'SmackCSVImportAddEnqueue');
}

function SmackCSVImportNewUpload() {
	$obj = new SmackCSVImportHelper;
	$obj->sm_file_upload();
	unset($obj);
}

add_action('wp_ajax_smack_ci_select_post_type','smack_ci_select_post_type');
function smack_ci_select_post_type()	{
	$obj1 = new SmackCSVImportAjaxActions;
	$obj1->getallposttypefields();
	unset($obj1);
}

add_action('wp_ajax_smack_ci_import_records','smack_ci_import_records');
function smack_ci_import_records()	{
	$obj2 = new SmackCSVImportAjaxActions();
	$obj2->importallrecords();
	unset($obj2);
}

function SmackCSVImportCustomMenuOrder( $menu_order ) {
	return array(
		'index.php',
		'edit.php',
		'edit.php?post_type=page',
		'upload.php',
		'sm_csv_importer',
	);
}
add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', 'SmackCSVImportCustomMenuOrder' );
