<?php
/**
 * @file
 * Provides Copy Values from Previous Event feature.
 */

/**
 * Implements Copy Values from Previous Event feature.
 */
function auto_populate_fields_copy_values_from_previous_event($project_id) {
    print_r("hello");
    require_once "initial_conditions.php";
    global $Proj;
    if (!checkIfPageIsDataentryOrSurvey() || !checkIfRecordExists() || fieldOrFormHasData()) {
        return;
    }
    $fieldsArr = array();
    foreach ($Proj->metadata as $field_name => $field_info) {
        // Checking for action tags.
        if (empty($field_info['misc'])) {
            continue;
        }
        // Checking for action tag @DEFAULT_FROM_PREVIOUS_VALUE.
        if (strcmp($Proj->metadata[$field_name]['misc'], '@DEFAULT_FROM_PREVIOUS_VALUE') == 0) {
            $fieldsArr[] = $field_name;
        }
    }

    $event_id = $_GET['event_id'];
    $result = array();
    $custom_data = REDCap::getData($project_id, 'json');
    $encoded_data = json_decode($custom_data);
    $max_event_id = 0;
    foreach ($encoded_data as $item) {
        $unique_event_name = $item->redcap_event_name;
        $unique_event_id = REDCap::getEventIdFromUniqueEvent($unique_event_name);
        $settings = array();
        foreach ($fieldsArr as $field) {
            // Get names and values of the fields that need to be autofilled.
            if (!empty($item->$field)) {
                $latest_data_obj = new stdClass();
                $latest_data_obj->value = $item->$field;
                $max_event_id = $unique_event_id;
                $latest_data_obj->field_name = $field;
                $latest_data_obj->event_id = $unique_event_id;
                $settings[] = $latest_data_obj;
            }
        }
        // Keep track of maximum event id so that fields are autofilled only for
        // newly opened instrument.
        if ($unique_event_id > $max_event_id) {
            $max_event_id = $unique_event_id;
        }

        // Keep track of values of fields that need to be autofilled from latest
        // event.
        if (!empty($settings)) {
            $result = $settings;
        }
    }
    $encoded_result = json_encode($result);
    prettyPrint($encoded_result);
    $returnVal = array();
    $subReturnVal = array();
    $subReturnVal['eventId'] = $event_id;
    $subReturnVal['maxEventId'] = $maxEventId;
    $subReturnVal['encoded_result'] = $encoded_result;
    $returnVal['copy_values_from_previous_event'] = $subReturnVal;
    return $returnVal;
}  
?>