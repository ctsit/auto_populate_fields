<?php
/**
 * @file
 * Provides "Force Data Entry Constraints" feature.
 */

/**
 * Handles @DEFAULT-FROM-FIELD action tag.
 */
function auto_populate_fields_default_from_field() {
    require_once "initial_conditions.php";
    global $double_data_entry, $user_rights, $Proj;
    if (!checkIfPageIsDataentryOrSurvey() || !checkIfRecordExists()) {
        return false;
    }

    $record = $_GET['id'];
    if ($double_data_entry && $user_rights['double_data'] != 0) {
        $record = $record . '--' . $user_rights['double_data'];
    }

    if (fieldOrFormHasData()) {
        return false;
    }
    
    $mappings = array();
    foreach ($Proj->metadata as $target_field_name => $target_field_info) {
        // Checking for action tags.
        if (empty($target_field_info['misc'])) {
            continue;
        }

        // Checking for action tag @DEFAULT.
        if (Form::getValueInQuotesActionTag($Proj->metadata[$target_field_name]['misc'], '@DEFAULT')) {
            // We do not want to override @DEFAULT behavior.
            continue;
        }

        // Checking for action tag @DEFAULT-FROM-FIELD.
        $source_field_name = Form::getValueInQuotesActionTag($Proj->metadata[$target_field_name]['misc'], '@DEFAULT-FROM-FIELD');
        if (empty($source_field_name)) {
            continue;
        }

        // Checking if source field exists.
        if (empty($Proj->metadata[$source_field_name])) {
            continue;
        }

        // Handling checkbox case.
        if ($target_field_info['element_type'] == 'checkbox') {
            $target_field_name = '__chkn__' . $target_field_name;
        }

        // Aux function.
        $getFormElementSelector = function($element_type, $element_name) {
            return '#questiontable ' . ($element_type == 'select' ? 'select' : 'input') . '[name="' . $element_name . '"]';
        };

        // Setting up target info.
        $source_field_info = $Proj->metadata[$source_field_name];
        $mappings[$target_field_name] = array(
            'type' => $target_field_info['element_type'],
            'selector' => $getFormElementSelector($target_field_info['element_type'], $target_field_name),
            'source' => $getFormElementSelector($source_field_info['element_type'], $source_field_name),
        );
    }

    if (empty($mappings)) {
        // If no mappings, there is no reason to proceed.
        return;
    }
    $returnVal = array();
    $returnVal['mappings'] = $mappings;
    return $returnVal;
};
?>
