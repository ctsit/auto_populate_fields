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
            '@DEFAULT-FROM-PREVIOUS-EVENT': 'Sets a field\'s default value based on its own value in a previous event. To map the default value from another field, you may specify the source as a parameter to the action tag, e.g @DEFAULT-FROM-PREVIOUS-EVENT="source_field".'
            '@DEFAULT_<N>': 'Provides the possibility to define secondary, tertiary, etc default values. If @DEFAULT returns an empty value, the next tag available - let\'s say @DEFAULT_1 - is checked. If @DEFAULT_1 returns empty, the next tag available - let\'s say @DEFAULT_2 - is checked, and so on. This is useful when a fallback value is needed for piping (e.g. @DEFAULT="[first_name]" @DEFAULT_1="Joe Doe").'
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
