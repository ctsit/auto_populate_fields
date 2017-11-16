autoPopulateFields.defaultWhenVisible.init = function() {
    // Setting branching logic to do not show messages.
    showEraseValuePrompt = 0;

    // Extracting evalLogic function body.
    var evalLogicBody = evalLogic.toString();
    var evalLogicBody = evalLogicBody.slice(evalLogicBody.indexOf('{') + 1, evalLogicBody.lastIndexOf('}'));

    // Changing evalLogic() function behavior: hide fields even when the message is not shown.
    var target = 'var eraseIt = false;';
    var replacement = 'var eraseIt = false; document.getElementById(this_field + \'-tr\').style.display = \'none\';';

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
            if (!eval(equation)) {
                evalLogicSubmit(fieldName, false, false);
            }
        });

        oldFormSubmitDataEntry();
    }
};

autoPopulateFields.defaultWhenVisible.init();