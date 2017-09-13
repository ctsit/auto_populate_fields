$(document).ready(function() {
    // Checking if we are on the Online Designer page.
    if ($('#div_field_annotation').length === 0) {
        return false;
    }

    $('body').on('dialogopen', function(event, ui) {
        var $popup = $(event.target);
        if ($popup.prop('id') !== 'action_tag_explain_popup') {
            // That's not the popup we are looking for...
            return false;
        }

        // Aux function that checks if text matches the "@DEFAULT" string.
        var isDefaultLabelColumn = function() {
            return $(this).text() === '@DEFAULT';
        }

        // Getting @DEFAULT row from action tags help table.
        var $default_action_tag = $popup.find('td').filter(isDefaultLabelColumn).parent();
        if ($default_action_tag.length !== 1) {
            return;
        }

        // Setting up action tags description texts.
        var texts = {
            '@DEFAULT-FROM-PREVIOUS-EVENT': 'Sets a field\'s default value based on its own value in a previous event. To map the default value from another field, you may specify the source as a parameter to the action tag, e.g @DEFAULT-FROM-PREVIOUS-EVENT="source_field".',
            '@DEFAULT-WHEN-VISIBLE': 'If the field is visible it sets the initial value otherwise it removes the value. This is mainly useful in fields which are visible and hidden by branching logic, e.g. @DEFAULT-FROM-FIELD=\'10\'.',
            '@DEFAULT-FROM-FIELD': 'Sets a field\'s default value from an existing field on the same form. This is useful when using hidden fields as source for visible fields, e.g. @DEFAULT-FROM-FIELD=\'hidden_first_name\'.',
            '@FIELD-NOTE-DISPLAY': 'This action tag takes value \'hover\' e.g. @FIELD-NOTE-DISPLAY=\'hover\'. If a field contains this action tag and the value is given as \'hover\', then the field note is hidden form the view, and shown on hover of the field. This is useful when the filed_note is too long and it is unnecessary to show the field note all the time.'
        };

        $.each(texts, function(tag_name, descr) {
            // Creating a new action tag row.
            var $new_action_tag = $default_action_tag.clone();
            var $cols = $new_action_tag.children('td');
            var $button = $cols.find('button');

            // Column 1: updating button behavior.
            $button.attr('onclick', $button.attr('onclick').replace('@DEFAULT', tag_name));

            // Columns 2: updating action tag label.
            $cols.filter(isDefaultLabelColumn).text(tag_name);

            // Column 3: updating action tag description.
            $cols.last().text(descr);

            // Placing new action tag.
            $new_action_tag.insertAfter($default_action_tag);
        });
    });
});
