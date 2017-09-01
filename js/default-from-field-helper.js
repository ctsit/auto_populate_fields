$(document).ready(function() {
    var mappings = auto_populate_fields.default_from_field.mappings;

    for (var target_name in mappings) {
        var mapping = mappings[target_name];
        var source_value = $(mapping.source).val();

        if (typeof source_value === 'undefined') {
            continue;
        }

        // Setting up default values.
        switch (mapping.type) {
            case 'checkbox':
                $(mapping.selector + '[code="' + source_value + '"]').click();
                break;
            case 'radio':
            case 'yesno':
            case 'truefalse':
                $(mapping.selector).siblings().children('input[value="' + source_value + '"]').click();
                break;
            default:
                $(mapping.selector).val(source_value);
                break;

        }
    }
});