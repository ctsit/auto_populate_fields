<?php
/**
 * @file
 * Provides "Default field value if the action is present" feature.
 */

/**
 * Handles @DEFAULT_ON_VISIBLE action tag.
 */
function auto_populate_fields_default_on_visible() {
    return;
    require_once "initial_conditions.php";
    global $double_data_entry, $user_rights, $quesion_by_section, $pageFields, $Proj;
    
    if (!checkIfPageIsDataentryOrSurvey() || !checkIfRecordExists()) {
        return;
    }

    $record = $_GET['id'];
    if ($double_data_entry && $user_rights['double_data'] != 0) {
        $record = $record . '--' . $user_rights['double_data'];
    }

    if (fieldOrFormHasData()) {
        return;
    }

    /* 
    * Populate forward_map, this actually has the key,value pair of the fields present in branching logic 
    * and backward_map has the reverse relationship. These are used to breakdown the branching logic and 
    * create the listener on the parent fields.
    */

    $add_default_mappings = array();
    $forward_map = array();
    $backward_map = array();
    foreach ($Proj->metadata as $field_name => $field_info) {
        if (empty($field_info['misc'])) {
            continue;
        }

        $default_value=Form::getValueInQuotesActionTag($field_info['misc'],'@DEFAULT_ON_VISIBLE');
        if (empty($default_value)) {
            // if add default field is not set then just continue from here.
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
                $forward_map[$sub_value][] = $key;
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

    ?>

    <script type="text/javascript">

        // document.ready is used so that the methods inside this does not have global scope.        
        $(document).ready(function () {

            // get the mappings from php through json_encode.
            var add_default_mappings = <?php print json_encode($add_default_mappings); ?>;

            //this method is used to set value to fields.
            function setValue (mapping, field_name, selector, value) {
                var elem_type = mapping['element_type'];

                // for each type logic for setting values is different this handles all the 4 types.
                if (elem_type == 'checkbox') {
                    var arrValue = value.trim().split(',');
                    var arr = JSON.parse(mapping['options']);
                    for (var i = 0; i < arr.length; i++) {
                        var index = arr[i];
                        var selector1 = selector + '[code="' + index + '"]';
                        if (arrValue.includes(index)) {
                            $(selector1).click();
                        } else {
                            $(selector1).prop('checked', false);

                            // this is very important to do. redcap checks the value of the field in other field
                            // so even after setting the value we also need to populate in other fields as well.
                            // jquery can populate them using siblings method.
                            $(selector1).siblings('input').val('');
                        }
                    }
                } else if (elem_type == 'select') {

                    // while restting value empty string is passed to remove the selection.
                    if (value == '') {
                        selector += ' option[value=""]';
                    } else {
                        selector += ' option[value="' + value + '"]';
                    }
                    // this will set a default value or remove the selection .
                    $(selector).prop('selected', true);
                } else if (elem_type == 'radio') {

                    // if value is '' this reset the field and none of the radio buttons are selected.
                    if (value == '') {
                        return radioResetVal(field_name.replace('__radio', ''),'form');
                    } else {
                        selector += '[value="' + value + '"]';
                        $(selector).click();
                    }                    
                } else {
                    $(selector).val(value);
                }
            }

            var forward_map = <?php print json_encode($forward_map)?>;

            // add an event listener for all the fields which can hide other fields.
            for (var key in forward_map) {
                $("#"+key+"-tr").change(function () {

                    // this map is used to store the state of the field before resetting the fields;
                    aux = {};

                    // add the info to aux map for all.
                    var children = forward_map[key];
                    for (var i = 0; i < children.length; i++) {
                        var field_name = children[i];
                        var $elem = $(add_default_mappings[field_name]['selector']);
                        aux[field_name] = {'visible': $elem.is(':visible'), 'value': $elem.val()};
                    }

                    // now reset all the values.
                    for (var i = 0; i < children.length; i++) {
                        var field_name = children[i];
                        var $elem = $(add_default_mappings[field_name]['selector']);
                        var elem_type = add_default_mappings[field_name]['element_type'];
                        var selector = add_default_mappings[field_name]['selector'];
                        
                        if (aux[field_name]['visible']) {
                            if (elem_type == 'checkbox') {
                                var arr = JSON.parse(add_default_mappings[field_name]['options']);
                                for (var j = 0; j < arr.length; j++) {
                                    var index = arr[j];
                                    selector += '[code="' + index + '"]';
                                    $(selector).prop('checked', false);
                                    $(selector).siblings('input').val('');
                                }
                            } else if (elem_type == 'radio') {
                                radioResetVal(field_name,'form');
                            } else if (elem_type == 'select') {
                                selector += " option[value=\"\"]";
                                $(selector).prop('selected', true);
                            } else {
                                $elem.val('');
                            }
                        }
                    }

                    // forcing redcap to do branching logic 
                    calculate();
                    doBranching();

                    for (var i = 0; i < children.length; i++) {
                        var field_name = children[i];
                        var mapping = add_default_mappings[field_name];
                        var parent_field_name = mapping['parent_field_name'];
                        var id = mapping['id'];
                        var selector = mapping['selector'];
                        var def_value = mapping['default_value'];
                        var elem_type = mapping['element_type'];

                        if ($(this).prop('name') == field_name) continue;

                        if ($(id).is(":visible")) {

                            if (!aux[field_name]['visible']) {

                                // this populates the field with default values if the field is visible now 
                                // and hidden previosuly
                                setValue(mapping, parent_field_name, selector, def_value);
                            } else {

                                // this just populates with previous state, this case occurs if field is visible 
                                // both now and after.
                                setValue(mapping, parent_field_name, selector, aux[field_name]['value']);
                            }
                        } else if (aux[field_name]['visible']) {
                            // this resets the value if the field is visible previosuly and hidden now.
                            setValue(mapping, parent_field_name, selector, '');
                        }
                    }

                    // forcing redcap to do branching logic
                    calculate();
                    doBranching();
                });
            }
            
        });

    </script>
    <?php
};

?>
