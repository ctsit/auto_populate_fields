autoPopulateFields.defaultWhenVisible.init = function() {
    
    // Extracting evalLogic function body.
    var evalLogicBodyString = evalLogic.toString();
    
    // To prevent style setting on nonexistent elements adding in a check for existence before trying to hide an element.
    evalLogicBodyString =  evalLogicBodyString.replace(
        /document\.getElementById\(this_field\+\'\-tr\'\)\.style\.display=\'none\';/g,
        'if ( document.getElementById(this_field + \'-tr\') != null ){ document.getElementById(this_field + \'-tr\').style.display = \'none\'; }'
    );
    
    evalLogicBody = evalLogicBodyString.slice(evalLogicBodyString.indexOf('{') + 1, evalLogicBodyString.lastIndexOf('}'));
    
    // Changing evalLogic() function behavior: hide fields even when the message is not shown.
    var target = 'var eraseIt = false;';
    var replacement = 'var eraseIt = false; if( document.getElementById(this_field + \'-tr\') != null ){ document.getElementById(this_field + \'-tr\').style.display = \'none\'; }';

    // Overriding original function.
    evalLogic = new Function('this_field', 'byPassEraseFieldPrompt', 'logic', evalLogicBody.replace(target, replacement));

    // Creating another version of evalLogic() that erases fields when message is not shown.
    var evalLogicSubmit = new Function('this_field', 'byPassEraseFieldPrompt', 'logic', evalLogicBody.replace(target, 'var eraseIt = true;'));

    // Overriding formSubmitDataEntry() in order to erase hidden branching logic
    // fields before saving data.
    var oldFormSubmitDataEntry = formSubmitDataEntry;
    formSubmitDataEntry = function() {
        $.each(autoPopulateFields.defaultWhenVisible.branchingEquations, function(fieldName, equation) {
            // If equation result is false, erase field value.
            try {                
                var equationResult = new Function('return (' + equation + ')')(); // Use Function constructor instead
                if (!equationResult) {
                    evalLogicSubmit(fieldName, false, false);
                }
            }
            catch ( e ) {
                // field specified in equation was not present
                if ( document.getElementsByName(fieldName).length !== 0 ) { // must check if fieldName is present on multipage forms
                    evalLogicSubmit( fieldName, false, false );
                }
            }
        });

        oldFormSubmitDataEntry();
    }
};

// Setting branching logic to not show messages.
showEraseValuePrompt = 0;

// In REDCap >= 9.4.1 evalLogic is not always in scope immediately
if (!autoPopulateFields.versionMod) {
    autoPopulateFields.defaultWhenVisible.init();
}

/**
 * This block of code prevents "leave with unsaved changes" alerts from being
 * supressed due to showEraseValuePrompt = 0.
 */
$(document).ready(function() {
    if (autoPopulateFields.versionMod) {
        autoPopulateFields.defaultWhenVisible.init(); // override evalLogic when it is in scope
        doBranching(); // recalculate branching logic with the modified evalLogic
    }
    var oldOnBeforeUnload = window.onbeforeunload;

    window.onbeforeunload = function() {
        // Enable flag when user is leaving the window.
        showEraseValuePrompt = 1;
        return oldOnBeforeUnload();
    }

    $('a').click(function(event) {
        // Enable flag when user is leaving the window by clicking on a link.
        showEraseValuePrompt = 1;
    });

    $('input, select').change(function() {
        // Disabling flag when some value is changed to preserve
        // "Default when visible" functionality.
        showEraseValuePrompt = 0;
    });
});
