$(document).ready(function() {
    var settings = autoPopulateFields.field_note_display;
    $.each(settings.hover, function (selector, field_notes) {
        $(selector).prop('title', field_notes).find('.note').hide();
    });
});
