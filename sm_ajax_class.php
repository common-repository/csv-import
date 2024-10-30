<?php
/**
 * CSV Importer plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class SmackCSVImportAjaxActions {

	public $original_data_import = array();

	public $posttype_custom_fields = array();

	public function getallposttypefields()  {
		include_once('sm_main_control.php');
		$controler = new SmackCSVImportHelper;
		$post_type = sanitize_text_field($_REQUEST['type']);
		$this->needTodeleteId = $controler->dummypostid($post_type);
		if(empty($this->needTodeleteId))	{
			$content = " Kindly create one dummy post in your $post_type post type with the post title dummy with all your custom fields";
			wp_send_json($content);
		}
		$core_fields = $controler->corefields($this->needTodeleteId);
		$core_fields[] = 'post_tag'; // For tags support
		$all_custom_fields = get_post_custom($this->needTodeleteId);
		foreach ( $all_custom_fields as $field_key => $field_values ) {
			if(!isset($field_values[0])) continue;
			if(in_array($field_key,array("_edit_lock", "_edit_last"))) continue;
			$this->posttype_custom_fields[] = $field_key;
		}
		if(!empty($this->posttype_custom_fields))
			update_option('custom_fields', $this->posttype_custom_fields);
		if(!empty($core_fields))
			update_option('core_fields', $core_fields);
		//table view html for customer viewse
		$file = get_option('file_location');
		$csv_headers = $controler->getCsvfiledetails($file);
		$total_row_count = $controler->total_row_count;
		$count_csvheader = count($csv_headers);
		$core_custom = array_merge($core_fields, $this->posttype_custom_fields);
		$count_custom_field = count($this->posttype_custom_fields) + count($core_fields);
		if($count_custom_field > $count_custom_field)
			$orig_count = $count_custom_field;
		else
			$orig_count = $count_custom_field;
		if(!empty($csv_headers))
			update_option('csv_fields',$csv_headers);
		$content = '<input type="hidden" name="total_row_count" id="total_row_count" value="'.$total_row_count.'" />';
		$content .= '<input type="hidden" name="i" id="i" value="1" />';
		$content .= '<table>';
		$content .='<tr><th>Wordpress Fields</th><th>Csv file headers</th></tr>';
		for($i = 0; $i < $orig_count; $i++)	{
			$content .= '<tr>';
			$content .= '<td>'.$core_custom[$i] . '</td>';
			$content .= '<td>'.$csv_headers[$i] . '</td>';
			$content .= '</tr>';
		}

		$content .= '</table>';
		unset($controler);
		delete_option('total_row_count');
		wp_send_json($content);
	}

	public function importallrecords()	{
		$this->post_type = sanitize_text_field($_REQUEST['type']);
		$this->current_import = sanitize_text_field($_REQUEST['i']);
		$this->custom_fields = get_option('custom_fields');
		$this->core_fields = get_option('core_fields');
		$csv_headers = get_option('csv_fields');
		$file = get_option('file_location');
		$csv_file_data = $this->getcsvdatas($file);

		print_r(json_encode($this->responsedata));die;
	}

	public function getcsvdatas($file)	{
		include_once('SmackCSVParser.php');
		$csv = new SmackCSVParser();
		$total_row_count = get_option('total_row_count');
		if(empty($total_row_count))	{
			$csv->parseCSV($file['file'] ,0, -1);
			$total_row_count = $csv->total_row_count - 1;
		}
		update_option('total_row_count',$total_row_count);
		$data = $csv->parseCSV($file['file'],$this->current_import,1);
		$this->data_to_import = $data[$this->current_import];
		$this->record_no_in_csv = $this->current_import;
		$this->importRecord();
		$this->updatePostmeta();
		$this->responsedata['next_import'] = $this->current_import + 1;
		if(!empty($this->errors))	{
			$this->responsedata['log'] = "<p>Record not process due to $this->errors</p>";
			$this->responsedata['errors'][$this->record_no_in_csv] = "<p>Record not process due to $this->errors</p>";
		}
		elseif($this->mode == 'Inserted')	{
			$this->responsedata['log'] = "<p>Record is Inserted with the POST ID $this->post_id : Data in CSV ROW == $this->record_no_in_csv</p>"."\n";
			$this->responsedata['insert'][$this->record_no_in_csv] = "<p>Record is Inserted with the POST ID $this->post_id : Data in CSV ROW == $this->record_no_in_csv</p>";
		}
		else	{
			$this->responsedata['log'] ="<p>Record is Updated with the POST ID $this->post_id : Data in CSV ROW == $this->record_no_in_csv</p>"."\n";
			$this->responsedata['update'][$this->record_no_in_csv] = "<p>Record is Updated with the POST ID $this->post_id : Data in CSV ROW == $this->record_no_in_csv </p>";
		}
		unset($csv);
	}

	public function importRecord()	{
		global $wpdb;
		$sticky = false;
		$this->post_id = false;
		foreach($this->core_fields as $value)	{
			if(array_key_exists($value, $this->data_to_import))
				$this->original_data_import[$value] = $this->data_to_import[$value];
		}
		$this->original_data_import['post_type'] = $this->post_type;
		//check post is exists
		$this->update_id = $wpdb->get_var($wpdb->prepare("select ID from $wpdb->posts where post_title = %s and post_type = %s and post_status in ('publish','draft') limit %d", $this->original_data_import['post_title'], $this->post_type, 1));
		$this->validatePostStatus($this->original_data_import);
		unset($this->original_data_import['post_author']);
		unset($this->original_data_import['post_date']);

		if(empty($this->update_id)){
			$this->post_id = wp_insert_post($this->original_data_import);
			if($sticky)
				stick_post($this->post_id);
			$this->insert_count = $this->insert_count + 1;
			$this->mode = "Inserted";
		}
		else{
			$this->original_data_import['ID'] = $this->update_id;
			wp_update_post($this->original_data_import);
			if($sticky)
				stick_post($this->update_id);
			$this->post_id = $this->update_id;
			$this->update_count = $this->update_count + 1;
			$this->mode = "Updated";
		}

		// To assign attributes ( Category & Tags )
		if($this->post_id != false)
			$this->importAttributes($this->original_data_import, $this->post_id);

		// TO check the error
		if(is_wp_error($this->post_id))	{
			$this->errors = $this->post_id->get_error_messages();
		}

		$this->log[$this->record_no_in_csv] = array('ID'=>$this->post_id,'Mode'=>$this->mode,'Post Type'=>$this->post_type,'CSV Row No'=>$this->record_no_in_csv,'Error'=>$errors);
		//insert attachment
		if(!empty($this->data_to_import['featured_image']))
			update_option('smack_featured_'.$this->post_id,$this->data_to_import['featured_image']);
	}

	public function updatePostmeta()	{
		foreach($this->custom_fields as $value)	{
			if(array_key_exists($value,$this->data_to_import))
				$this->original_metadata_import[$value] = $this->data_to_import[$value];
		}
		$this->original_metadata_import['_thumbnail_id'] = $this->attachment_id;
		foreach($this->original_metadata_import as $key=>$value)
			update_post_meta($this->post_id,$key,$value);

	}

	public function validatePostStatus($data_array) {
		$sticky = false;
		switch ($data_array['post_status']) {
			case 1 :
				$data_array['post_status'] = 'publish';
				break;
			case 2 :
				if ($data_array['post_type'] == 'post') {
					$data_array['post_status'] = 'publish';
					$sticky = true;
				} else {
					$data_array['post_status'] = 'publish';
				}
				break;
			case 3 :
				$data_array['post_status'] = 'private';
				break;
			case 4 :
				$data_array['post_status'] = 'draft';
				break;
			case 5 :
				$data_array['post_status'] = 'pending';
				break;
			default :
				$poststatus_pwd = $data_array['post_status'];
				$poststatus = $data_array['post_status'] = strtolower($data_array['post_status']);
				if ($data_array['post_status'] == 'pending') {
					$data_array['post_status'] = 'pending';
				}
				if ($data_array['post_status'] == 'draft') {
					$data_array['post_status'] = 'draft';
				}
				if ($data_array['post_status'] == 'publish') {
					$data_array['post_status'] = 'publish';
				}
				if ($data_array['post_status'] == 'private') {
					$data_array['post_status'] = 'private';
				}

				if ($data_array['post_status'] != 'publish' && $data_array['post_status'] != 'private' && $data_array['post_status'] != 'draft' && $data_array['post_status'] != 'pending' && $data_array['post_status'] != 'sticky') {
					$stripPSF = strpos($data_array['post_status'], '{');
					if ($stripPSF === 0) {
						$poststatus = substr($poststatus_pwd, 1);
						$stripPSL = substr($poststatus, -1);
						if ($stripPSL == '}') {
							$postpwd = substr($poststatus, 0, -1);
							$data_array['post_status'] = 'publish';
							$data_array ['post_password'] = $postpwd;
						} else {
							$data_array['post_status'] = 'publish';
						}
					} else {
						$data_array['post_status'] = 'publish';
					}
				}
				if ($data_array['post_status'] == 'sticky') {
					if ($_SESSION['SMACK_MAPPING_SETTINGS_VALUES']['selectedImporter'] == 'post') {
						$data_array['post_status'] = 'publish';
						$sticky = true;
					} else {
						$data_array['post_status'] = 'publish';
					}
				}
			break;
		}
		$this->original_data_import['post_status'] = $data_array['post_status'];
		$this->original_data_import['post_password'] = $data_array['post_password'];
		$this->original_data_import['stick_post'] = $sticky;
	}

	public function importAttributes($data_array, $id) {
		if(array_key_exists('post_category', $data_array)) {
			if (strpos($data_array['post_category'], '|') !== false) {
				$split_cate = explode('|', $data_array['post_category']);
			} elseif (strpos($data_array['post_category'], ',') !== false) {
				$split_cate = explode(',', $data_array['post_category']);
			} else {
				$split_cate = $data_array['post_category'];
			}
			foreach ($split_cate as $key => $val) {
				if (is_numeric($val)) {
					$split_cate[$key] = 'uncategorized';
				}
			}
			wp_set_object_terms($id, $split_cate, 'category');
		}
		if(array_key_exists('post_tag', $data_array)) {
			wp_set_post_tags($id, $data_array['post_tag']);
		}
	}

}
