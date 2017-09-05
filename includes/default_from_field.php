<?php
/**
 * @file
 * Provides Default From Field feature.
 */

require_once 'helper.php';

/**
 * Handles @DEFAULT-FROM-FIELD action tag.
 */
function auto_populate_fields_default_from_field() {
    global $Proj;

    $mappings = array();
    foreach (auto_populate_fields_get_fields_names() as $target_field_name) {
        $target_field_info = $Proj->metadata[$target_field_name];
        if (!$source_field_name = auto_populate_fields_action_tag_semaphore($target_field_info['misc'], '@DEFAULT-FROM-FIELD')) {
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

    return array('mappings' => $mappings);
};
