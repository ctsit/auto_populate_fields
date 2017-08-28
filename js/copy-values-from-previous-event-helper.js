$(document).ready(function() {
	// Checking if we are on the Online Designer page.
	if ($('#div_field_annotation') === 0) {
		return false;
    }

    var eventId = auto_populate_fields.copy_values_from_previous_event.eventId;
    var maxEventId = auto_populate_fields.copy_values_from_previous_event.maxEventId;
    var phpArray = auto_populate_fields.copy_values_from_previous_event.result;
    if (eventId >= maxEventId) {
        var resultArray = phpArray;
        for (var i=0; i<resultArray.length; i++){
            $('[name=' + resultArray[i].field_name + ']').val(resultArray[i].value);
        }
    }
});