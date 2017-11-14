<?php
/**
 * @file
 * Provides helper functions for Auto Populate Fields module.
 */

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

/**
 * Looks for multiple action tags in a given string.
 *
 * Example: @DEFAULT_<N> (e.g. @DEFAULT_1, @DEFAULT_2, etc).
 *
 * @param string $action_tag
 *   The action tag (e.g. @DEFAULT).
 * @param string $subject
 *   The string to search in.
 *
 * @return array
 *   The list of default action tags found.
 */
function auto_populate_fields_get_multiple_action_tags($action_tag, $subject) {
    preg_match_all('/(' . $action_tag . '_\d+)/', $subject, $matches);

    $action_tags = $matches[1];
    sort($action_tags);

    if (strpos($subject, $action_tag) !== false) {
        array_unshift($action_tags, $action_tag);
    }

    return $action_tags;
}

/**
 * Overrides an action tag.
 *
 * @param string $key
 *   The action tag name (e.g. @DEFAULT).
 * @param string $value
 *   The value to replace with.
 * @param string $subject
 *   The subject string to override.
 * @param bool $append_if_not_exists
 *   Append the action tag value if there is no target to override.
 *
 * @return string
 *   The overriden subject string.
 */
function auto_populate_fields_override_action_tag($key, $value, $subject, $append_if_not_exists = true) {
    if (strpos($subject, $key . '=') !== false) {
        // Override action tag if exists.
        $regex = '/(' . $key . '\s*=\s*)((\"[^\"]+\")|(\'[^\']+\'))/';
        $subject = preg_replace($regex, $key . '="' . $value . '"', $subject);
    }
    elseif ($append_if_not_exists) {
        $subject .= ' ' . $key . '="' . $value . '"';
    }

    return $subject;
}
