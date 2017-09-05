<?php
/**
 * @file
 * Provides Default When Visible feature.
 */

require_once 'helper.php';

/**
 * Handles @DEFAULT-WHEN-VISIBLE action tag.
 */
function auto_populate_fields_default_when_visible() {
    global $Proj;

    /* 
    * Populate forward_map, this actually has the key,value pair of the fields present in branching logic 
    * and backward_map has the reverse relationship. These are used to breakdown the branching logic and 
    * create the listener on the parent fields.
    */
    $add_default_mappings = array();
    $forward_map = array();
    $backward_map = array();

    foreach (auto_populate_fields_get_fields_names() as $field_name) {
        $field_info = $Proj->metadata[$field_name];
        if (!$default_value = auto_populate_fields_action_tag_semaphore($field_info['misc'], '@DEFAULT-WHEN-VISIBLE')) {
            continue;
        }

        // constructs the backward map from branching logic data
        $branching_logic = $field_info['branching_logic'];
        preg_match_all("/\[([^\]]*)\]/", $branching_logic, $matches);
        
        $branch_array = array();
        foreach ($matches[1] as $mat1) {
            $pos = strpos($mat1, '(');
            if ($pos) {
                $branch_array[] = substr($mat1, 0, $pos);
            } else {
                $branch_array[] = $mat1;
            }
        }
        $backward_map[$field_name] = $branch_array;

        // if field has mutiple options or choices to choose, this gets the array of all the values.
        $options = array();
        if ($field_info['element_enum']) {
            foreach (explode("\\n", $field_info['element_enum']) as $tuple) {
                list($key, ) = explode(',', $tuple);
                $options[] = trim($key);
            }
        }

        //construct parent_field_name and selector for each field so that it is easy for the js
        // to reference them than creating the string each time.
        $parent_field_name = "";
        $selector = ($field_info['element_type'] == 'select' ? 'select' : 'input');
        if ($field_info['element_type'] == 'checkbox') {
            $parent_field_name = '__chkn__' . $field_name;
            $selector .= '[name="' . $parent_field_name . '"]';
        } else if ($field_info['element_type'] == 'radio') {
            $parent_field_name = $field_name . '___radio';
            $selector .= ':radio[name="' . $parent_field_name . '"]';
        } else {
            $selector .= '[name="' . $field_name . '"]';
            $parent_field_name = $field_name;
        }

        // finally collect all the information related to a field in the form of a map
        $add_default_mappings[$field_name] = array(
            'id' => "#" . $field_name . '-tr',
            'parent_field_name' => $parent_field_name,
            'selector' => $selector,
            'element_type' => $field_info['element_type'],
            'options' => json_encode($options),
            'branching_logic' => $field_info['branching_logic'],
            'default_value' => $default_value
        );
    }

    // construct forward map from backward map.
    foreach ($backward_map as $key => $value) {
        foreach ($value as $subkey => $sub_value) {
            if (array_key_exists($sub_value, $forward_map)) {
                if (!in_array($key, $forward_map[$sub_value])) {
                    $forward_map[$sub_value][] = $key;
                }
            } else {
                $forward_map[$sub_value] = array();
                $forward_map[$sub_value][] = $key;
            }
        }
    }
    
    // if not fields are eligible for this action tag them simply return from here.
    if (empty($add_default_mappings)) {
        // If no mappings, there is no reason to proceed.
        return;
    }

    // variables that are required by the js are stored returned form this function
    $returnVal = array();
    $returnVal['add_default_mappings'] = $add_default_mappings;
    $returnVal['forward_map'] = $forward_map;
    
    return $returnVal;
}
