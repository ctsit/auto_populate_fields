$(document).ready(function(){
	var ae_id = autoPopulateFields.default_ae_id.id;
	var fields = autoPopulateFields.default_ae_id.fields;
	
	for(var i=0;i<fields.length;i++) {
		$('[name="'+fields[i]+'"]').val(ae_id);
	}
});