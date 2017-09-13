<?php
/**
 * @file
 * Provides helper functions for Auto Populate Fields module.
 */

/**
 * Gets available features for the current page.
 *
 * @return bool|array
 *   The array of features, which can include:
 *   - default_from_field
 *   - default_when_visible
 *   - default_from_previous_event
 */
function auto_populate_fields_get_available_features() {
    $features = array();

    // Checking if we are in a data entry or survey page.
    if (!in_array(PAGE, array('DataEntry/index.php', 'surveys/index.php', 'Surveys/theme_view.php'))) {
        return $features;
    }

    if (empty($_GET['id'])) {
        return $features;
    }

    // Checking additional conditions for survey pages.
    if (PAGE == 'surveys/index.php' && !(isset($_GET['s']) && defined('NOAUTH'))) {
        return $features;
    }

    if (auto_populate_fields_form_has_data()) {
        return $features;
    }

    $features[] = 'default_from_field';
    $features[] = 'default_when_visible';
    $features[] = 'field_note_display';

    if (PAGE == 'DataEntry/index.php') {
        $features[] = 'default_from_previous_event';
    }

    return $features;
}

/**
 * Checks if the current form has data.
 *
 * @return bool
 *   TRUE if the current form contains data, FALSE otherwise.
 */
function auto_populate_fields_form_has_data() {
    global $double_data_entry, $user_rights, $quesion_by_section, $pageFields;

    $record = $_GET['id'];
    if ($double_data_entry && $user_rights['double_data'] != 0) {
        $record = $record . '--' . $user_rights['double_data'];
    }

    if (PAGE != 'DataEntry/index.php' && $question_by_section && Records::fieldsHaveData($record, $pageFields[$_GET['__page__']], $_GET['event_id'])) {
        // The survey has data.
        return true;
    }

    if (Records::formHasData($record, $_GET['page'], $_GET['event_id'], $_GET['instance'])) {
        // The data entry has data.
        return true;
    }

    return false;
}

/**
 * Checks if the given action tags exists and it does not conflict with any
 * other @DEFAULT tags.
 *
 * @param string $misc
 *   The action tags pool.
 * @param string $action_tag
 *   The action tag to verify, e.g. @DEFAULT-FROM-FIELD.
 * @param bool $return_value
 *   Flag to choose whether to return the action tag's value between quotes.
 *   Defaults to TRUE. 
 *
 * @return mixed
 *   If the action tag is present and does not conflict with any other tags:
 *   - Returns the action tag's input value if $return_value is TRUE,
 *   - Returns TRUE if $return_value is FALSE
 *
 *   Returns FALSE if the action tag does not exist or generates any conflicts.
 */
function auto_populate_fields_action_tag_semaphore($misc, $action_tag, $return_value = true) {
    // Checking if action tags exist.
    if (empty($misc) || strpos($misc, '@') === false) {
        return false;
    }

    // Establishing a priority queue for action tags.
    $priority_queue = auto_populate_fields_get_priority_queue();

    foreach ($priority_queue as $item) {
        $regex = '/(' . $item . ')($|[^(\-)])/';
        preg_match($regex, $misc, $match);

        $exists = !empty($match[1]);
        if ($item == $action_tag) {
            return $exists && $return_value ? Form::getValueInQuotesActionTag($misc, $item) : $exists;
        }

        if ($exists) {
            // A more priority action tag exists.
            break;
        }
    }

    return false;
}

/**
 * Gets the action tags that can conflict with each other.
 *
 * @return array
 *   List of action tags in priority order.
 */
function auto_populate_fields_get_priority_queue() {
    return array(
        '@DEFAULT',
        '@DEFAULT-ON-VISIBLE',
        '@DEFAULT-FROM-FIELD',
        '@DEFAULT-FROM-PREVIOUS-EVENT',
    );
}

/**
 * Gets fields names for the current event.
 *
 * @return array
 *   An array of fields names.
 */
function auto_populate_fields_get_fields_names() {
    global $Proj;
    $fields = empty($_GET['page']) ? $Proj->metadata : $Proj->forms[$_GET['page']]['fields'];
    return array_keys($fields);
}
