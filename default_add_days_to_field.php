<?php
/**
 * @file
 * Provides Default From Field feature.
 */

require_once 'helper.php';

/**
 * Handles @DEFAULT-FROM-FIELD action tag.
 */
function auto_populate_fields_default_add_days_to_field() {
    global $Proj;
    print_r("Hello");
    $relationMap = array();
    $field_map = array();
    // prettyPrint($Proj->metadata);
    foreach (auto_populate_fields_get_fields_names() as $field_name) {
        $field_info = $Proj->metadata[$field_name];
        if (!$default_value = auto_populate_fields_action_tag_semaphore($field_info['misc'], '@ADD-DAYS-TO-FIELD')) {
            continue;
        }
        prettyPrint($default_value);
        if (empty($default_value)) {
            continue;
        }
        $val_arr2 = split(',', $default_value);
        prettyPrint($val_arr2);

        $source_field_name = substr($val_arr2[0], 1, -1);
        prettyPrint($source_field_name);

        // Checking if source field exists.
        if (empty($Proj->metadata[$source_field_name])) {
            continue;
        }
        $subRes = array();
        $field_map[$field_name] = intval(trim($val_arr2[1]));

        if (array_key_exists($source_field_name, $relationMap)) {
            $temp = $relationMap[$source_field_name];
            $temp[] = $field_name;
        } else {
            $temp = array();
            $temp[] = $field_name;
        }
        $relationMap[$source_field_name] = $temp;
    }
    $res['relationMap'] = $relationMap;
    $res['fieldMap'] = $field_map;
    prettyPrint($res);
    return $res;
}

function prettyPrint($a, $b=null) {
    if ($b) echo "<b>".$b."</b>";
    echo '<pre>' . print_r($a,1).'</pre>';
}

function printConsole($obj) {
    $jsonprd = json_encode($obj);
    print_r('"<script>console.log('.$jsonprd.')</script>');
}

?>