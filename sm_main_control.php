<?php
/**
 * CSV Importer plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once('SmackCSVParser.php');

class SmackCSVImportHelper {

	var $csv_headers = array();
	var $file;

	public function __construct() {
		$this->csv_headers = null;
	}

	public function sm_activate_function() {
	}

	public function sm_file_upload() {
		$this->sm_header();
		$step = empty($_GET['step'])? 0 : (int)$_GET['step'];
		switch($step) {
			case 0 :
				$this->sm_file_new_upload();
				break;
			case 2 :
				$this->file = $this->handle_file_upload();
				if($this->file != 2)
					update_option('file_location',$this->file);
				$this->show_all_post_type_fields();
				break;
		}
		$this->sm_body();
		$this->sm_ajax_loader();
		//		$this->sm_log();
		$this->sm_footer();
	}

	public function getCsvfiledetails($file) {
		$csv = new SmackCSVParser();
		$data = $csv->parseCSV($file['file'],null,null);
		$header = $csv->get_CSVheaders();
		$csv->parseCSV($file['file'] ,0, -1);
		$this->total_row_count = $csv->total_row_count - 1;
		$csv = null;
		foreach ($header as $key=> $hval) {
			foreach ($hval as $hk => $hv) {
				$headers[] = $hv;
			}
			break;
		}
		return $headers;
	}

	public function handle_file_upload() {
		$file = wp_import_handle_upload();
		if(isset($file['error']) && !empty($file['error']))
			return 2;
		$check_file_type = wp_check_filetype($file['file']);
		return $file;
	}

	public function sm_file_new_upload() {
		echo '<div class="narrow">';
		echo '<p>'.__( 'Choose a WXR (.csv) file to upload, then click Upload file and import.', 'csv-import' ).'</p>';
		wp_import_upload_form('admin.php?page=sm_csv_importer&amp;step=2'  );
		echo '</div>';
	}

	public function sm_header()	{
		echo '<div class="wrap">';
		screen_icon();
		echo '<h2>' . __( 'CSV IMPORTER', 'csv import' ) . '</h2>';

		echo '<p> To import Category and WooCommerce Product Check our Pro version </p>';
	}

	public function sm_body()	{
		echo '<div class="tables" style="width: 50%;">';
		echo '<div class="modal fade" id="your-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
			<h2> Field Name in your Wordpress and in your CSV File </h2>
			</div>
			<div class="modal-body csv-body">

			</div>
			<div class="modal-footer">
			
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			</div>
			</div>
			</div>';
		echo '<div style="padding-left: 25%; position: relative;"><button id="importnow" name="importnow" class="btn btn-primary" onclick="importrightnow()" style="display:none;">Import Now</button></div>';
		echo '<div style="padding-left: 25%; position: relative;"><button name="complete_button" class="complete_button btn btn-primary"  style="display:none;">Import Completed</button></div>';
		echo '<div class="modal fade"  id="log-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                        <h2> Log Section</h2>
                        </div>
                        <div class="modal-body log-body">

                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                        </div>
                        </div>';
		echo '<div id="log_link" style="display:none;">
			<button type="button" data-toggle="modal" data-target="#log-error" > Error</button>
			<button type="button" data-toggle="modal" data-target="#log-update" > Update</button>
			<button type="button" data-toggle="modal" data-target="#log-insert" > Insert</button>
			</div>';
		echo '<div class="modal fade"  id="log-error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                        <h2> Log Section</h2>
                        </div>
                        <div class="modal-body log-error">

                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                        </div>
                        </div>';
		echo '<div class="modal fade"  id="log-update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                        <h2> Log Section</h2>
                        </div>
                        <div class="modal-body log-update">

                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                        </div>
                        </div>';
		echo '<div class="modal fade"  id="log-insert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                        <h2> Log Section</h2>
                        </div>
                        <div class="modal-body log-insert">

                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                        </div>
                        </div>
                        </div>';
	}

	public function sm_log($var1,$var2,$var3,$var4,$var5) {
		echo "Record $var1 is $var2 with the post id $var3 in the csv file row number $var4";
	}

	public function sm_ajax_loader() {
		$img_path = WP_PLUGIN_URL . '/csv-import/images/ajax-loader-gif-image-211.gif';
		echo '<div class="ajax_loader" style="display:none;">
			<img src="'.$img_path.'" alt="ajax_loader" />
			</div>';
	}

	public function sm_footer()	{
		echo "</div>";
	}

	public function corefields($deletedId)	{
		$core_field = get_post($deletedId,ARRAY_A);
		foreach($core_field as $key=>$value)
			$this->core_fields[] = $key;
		return $this->core_fields;
	}

	public function dummypostid($posttype)	{
		global $wpdb;
		$dummyId = $wpdb->get_var($wpdb->prepare("select ID from $wpdb->posts where post_type = %s and post_status in ('publish','draft') and post_title = %s limit %d", $posttype, 'dummy', 1));
		$dummyId1 = $wpdb->get_var($wpdb->prepare("select ID from $wpdb->posts where post_type = %s and post_status in ('publish','draft')  limit %d", $posttype, 1));
		if(empty($dummyId))
			$dummyId = $dummyId1;
		else
			$dummyId = $dummyId;
		return $dummyId;
	}

	public function insert_dummy_post($post_type)	{
		global $wpdb;
		$arg = array('post_title'=>'dummy','post_content'=>'dummy_content','post_status'=>'publish','post_type'=>$post_type);
		$result = wp_insert_post($arg);
		return $result;
	}

	public function show_all_post_type_fields()	{
		$post_types = get_post_types('', 'objects');
		echo '<div style="float: left;">';
		echo '<select id="post_type_sm" name="post_type_sm" onchange="posttypes()">';
		echo "<option value='select'>--select--</option>";
		foreach($post_types as $post_key=>$post_val)	{
			$obj = $post_val->labels;
			if (!in_array($post_key, array('attachment', 'revision', 'nav_menu_item'))){
				echo "<option value='{$post_key}'>{$obj->name}</option>";
			}
		}
		echo "</select>";
		echo '</div>';echo '</div>';
	}

}