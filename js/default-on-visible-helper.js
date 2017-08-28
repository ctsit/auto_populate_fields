$(document).ready(function () {
    
    // get the mappings from php through json_encode.
    var add_default_mappings = auto_populate_fields.default_on_visible.add_default_mappings;//  <?php print json_encode($add_default_mappings); ?>;

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

    var forward_map = auto_populate_fields.default_on_visible.forward_map; //<?php print json_encode($forward_map)?>;

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