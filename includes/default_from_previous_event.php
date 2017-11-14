<?php
/**
 * @file
 * Provides Default from Previous Event feature.
 */

require_once 'helper.php';

/**
 * Handles @DEFAULT-FROM-PREVIOUS-EVENT action tag.
 */
function auto_populate_fields_default_from_previous_event() {
    if (PAGE != 'DataEntry/index.php' || empty($_GET['id']) || auto_populate_fields_form_has_data()) {
        return;
    }

    global $Proj;

    $data = REDCap::getData($Proj->project['project_id'], 'array', $_GET['id']);
    if (empty($data)) {
        return;
    }

    $data = $data[$_GET['id']];
    $arm = $Proj->eventInfo[$_GET['event_id']]['arm_num'];
    $events = array_keys($Proj->events[$arm]['events']);

    foreach (auto_populate_fields_get_fields_names() as $field_name) {
        $misc = $Proj->metadata[$field_name]['misc'];
        $action_tags = auto_populate_fields_get_multiple_action_tags('@DEFAULT-FROM-PREVIOUS-EVENT', $misc);
        if (empty($action_tags)) {
            continue;
        }

        // Looping over @DEFAULT-FROM-PREVIOUS-EVENT_<N> action tags.
        foreach ($action_tags as $action_tag) {
            if (!$source_field = Form::getValueInActionTag($misc, $action_tag)) {
                // If no value is provided on the action tag, set the same
                // field as source by default.
                $source_field = $field_name;
            }
            elseif (!isset($Proj->metadata[$source_field])) {
                // Invalid field.
                continue;
            }

            $default_value = '';
            foreach ($events as $event) {
                if ($event == $_GET['event_id']) {
                    break;
                }

                if (!empty($data[$event]) && isset($data[$event][$source_field])) {
                    $default_value = $data[$event][$source_field];
                }
            }

            if (empty($default_value) && !is_numeric($default_value)) {
                continue;
            }

            $misc = auto_populate_fields_override_action_tag('@DEFAULT', $default_value, $misc);
            $Proj->metadata[$field_name]['misc'] = $misc;

            break;
        }
    }
}
