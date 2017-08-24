$(document).ready(function() {
	// Checking if we are on the Online Designer page.
	if ($('#div_field_annotation') === 0) {
		return false;
    }
    
    var eventId = '<?php echo $event_id; ?>';
    var maxEventId = '<?php echo $max_event_id; ?>';
    var phpArray = '<?php echo $encoded_result; ?>';
    if (eventId >= maxEventId) {
        var resultArray = JSON.parse(phpArray);
        for (var i=0; i<resultArray.length; i++){
            $('[name=' + resultArray[i].field_name + '"]').val(resultArray[i].value);
        }
    }
});