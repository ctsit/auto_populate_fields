<?php
/**
 * @file
 * Provides Default When Visible feature.
 */

require_once 'helper.php';

/**
 * Handles @DEFAULT-WHEN-VISIBLE action tag.
 */
function auto_populate_fields_field_note_display() {
    global $Proj;
    $returnVal = array();
    foreach (auto_populate_fields_get_fields_names() as $field_name) {
        $field_info = $Proj->metadata[$field_name];
        if (!$default_value = auto_populate_fields_action_tag_semaphore($field_info['misc'], '@FIELD-NOTE-DISPLAY')) {
            continue;
        }
        switch ($default_value) {
            case "hover" :
                $field_map = auto_populate_fields_on_hover($field_name);
                if ($field_map) {
                    $subval = array();
                    $subval[$field_name] = $field_map;
                    if (!array_key_exists("hover", $returnVal)) {
                        $returnVal["hover"] = $subval;
                    } else {
                        $subRet = $returnVal["hover"];
                        $subRet[$field_name] = $field_map;
                        $returnVal["hover"] = $subRet;
                    }
                }
                break;
            default:
                break;
        }

    }
    if (count($returnVal) > 0) {
        return $returnVal;
    }
    return false;
}
/**
* Get field names and field notes for action tag containing fields.
* 
* @return array
*   A map of field_names and field_note values.
*/
function auto_populate_fields_on_hover($field_name) {
    global $Proj;
    $res = false;
    if (!empty($_GET['page'])) {
        $field_info = $Proj->metadata[$field_name];
        if (!empty($field_info['element_note'])) {
            $res = array();
            $res['#'.$field_name.'-tr'] = $field_info['element_note'];
        }
    }
    return $res;
}
?>