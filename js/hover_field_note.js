$(document).ready(function() {
    var hover_map = autoPopulateFields.hover_field_note;
    for (var selector in hover_map) {
        $(selector).prop('title', hover_map[selector]);
        var note_selector = selector + " .note";
        $(note_selector).hide();
    }
});