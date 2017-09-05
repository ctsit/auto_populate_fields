$(document).ready(function() {
	// Checking if we are on the Online Designer page.
	if ($('#div_field_annotation') === 0) {
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

		// Creating a new action tag row - @DEFAULT-FROM-FIELD - from @DEFAULT.
		var $new_action_tag1 = $default_action_tag.clone();
		// Creating a new action tag row - @DEFAULT-ON-VISIBLE - from @DEFAULT.
		var $new_action_tag2 = $default_action_tag.clone();
		// Creating a new action tag row - @DEFAULT-FROM-PREVIOUS-VALUE - from @DEFAULT.
		var $new_action_tag3 = $default_action_tag.clone();

		// Column 1: updating button behavior.
		var $button1 = $new_action_tag1.find('td button');
		$button1.attr('onclick', $button1.attr('onclick').replace('@DEFAULT', '@DEFAULT-FROM-FIELD'));

		var $button2 = $new_action_tag2.find('td button');
		$button2.attr('onclick', $button2.attr('onclick').replace('@DEFAULT', '@DEFAULT-ON-VISIBLE'));
		
		var $button3 = $new_action_tag3.find('td button');
		$button3.attr('onclick', $button3.attr('onclick').replace('@DEFAULT', '@DEFAULT-FROM-PREVIOUS-VALUE'));
		
		// Columns 2: updating action tag label.
		$new_action_tag1.children('td').filter(isDefaultLabelColumn).text('@DEFAULT-FROM-FIELD');
		$new_action_tag2.children('td').filter(isDefaultLabelColumn).text('@DEFAULT-ON-VISIBLE');
		$new_action_tag3.children('td').filter(isDefaultLabelColumn).text('@DEFAULT-FROM-PREVIOUS-VALUE');
		
		// Column 3: updating action tag description.
		$new_action_tag1.children('td').last().text('Sets a fields\'s default value from an existing field on the same form. This is useful when using hidden fields as source for visible fields, e.g. @DEFAULT-FROM-FIELD=\'hidden_first_name\'.');
		$new_action_tag2.children('td').last().text('If the field is visible it sets the initial value otherwise it removes the value. This is mainly useful in fields which are visible and hidden by branching logic, e.g. @DEFAULT-FROM-FIELD=\'10\'.');
		$new_action_tag3.children('td').last().text('Sets a field\'s default value based on its own value in a previous event. To map the default value from another field, you may specify the source as a parameter to the action tag, e.g @DEFAULT-FROM-PREVIOUS-EVENT="source_field".');
		
		// Inserting new action tags after @DEFAULT.
		$new_action_tag1.insertAfter($default_action_tag);
		$new_action_tag2.insertAfter($new_action_tag1);
		$new_action_tag3.insertAfter($new_action_tag2);
	});
});
