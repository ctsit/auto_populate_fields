$(document).ready(function() {
    var hover_map = autoPopulateFields.field_note_display.hover;
    for (var key in hover_map) {
        field_note_map = hover_map[key];
        var selector = Object.keys(field_note_map)[0];
        $(selector).prop('title', field_note_map[selector]);
        var field_note_selector = selector + " .note";
        $(field_note_selector).hide();
    }
});