<?php
/**
 * @file
 * Provides Field Note Display feature.
 */

require_once 'helper.php';

/**
 * Handles @FIELD-NOTE-DISPLAY action tag.
 */
function auto_populate_fields_field_note_display() {
    global $Proj;
    $returnVal = array();

    foreach (auto_populate_fields_get_fields_names() as $field_name) {
        $field_info = $Proj->metadata[$field_name];
        if (!$field_notes = $field_info['element_note']) {
            continue;
        }

        if (!$display_mode = Form::getValueInQuotesActionTag($field_info['misc'], '@FIELD-NOTE-DISPLAY')) {
            continue;
        }

        if (!isset($returnVal[$display_mode])) {
            $returnVal[$display_mode] = array();
        }

        switch ($display_mode) {
            case 'hover':
                $returnVal[$display_mode]['#' . $field_name . '-tr'] = $field_notes;
                break;
        }

    }

    if (empty($returnVal)) {
        return false;
    }

    return $returnVal;
}
