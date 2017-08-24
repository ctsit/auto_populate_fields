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
		var $new_action_tag = $default_action_tag.clone();

		// Column 1: updating button behavior.
		var $button = $new_action_tag.find('td button');
		$button.attr('onclick', $button.attr('onclick').replace('@DEFAULT', '@DEFAULT-FROM-FIELD'));

		// Columns 2: updating action tag label.
		$new_action_tag.children('td').filter(isDefaultLabelColumn).text('@DEFAULT-FROM-FIELD');

		// Column 3: updating action tag description.
		$new_action_tag.children('td').last().text('Sets a fields\'s initial value from an existing field on the same form. This is useful when using hidden fields as source for visible fields - e.g. @DEFAULT-FROM-FIELD=\'hidden_first_name\'.');

		// Inserting @DEFAULT-FROM-FIELD after @DEFAULT.
		$new_action_tag.insertAfter($default_action_tag);
	});
});
