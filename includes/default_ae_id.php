<?php
/**
 * Generates a custom instance id for Adverse Events instrument.
*/

require_once 'helper.php';

/**
 * Handle @AE_ID tag.
*/
function auto_populate_fields_default_ae_id() {
	global $Proj;

	// Get required parameters from query.
	parse_str($_SERVER['QUERY_STRING'], $qs_params);
	$record_id = $qs_params['id'];
	$event_id = $qs_params['event_id'];
	$instance_id = $qs_params['instance'];

	// Create custom AE_ID.
	$ae_id = $record_id."-".$event_id."-".$instance_id;
	$tag_name = '@AE_ID';
	$fields_arr = array();
	foreach (auto_populate_fields_get_fields_names() as $field_name) {
        $target_field_info = $Proj->metadata[$field_name];

        if(strpos($target_field_info['misc'], $tag_name) !== false) {
        	$fields_arr[] = $target_field_info['field_name'];
        }
    }
    return array('id' => $ae_id, 'fields' => $fields_arr);
}
?>