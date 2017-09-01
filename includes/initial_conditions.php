<?php
/**
 * @file
 * Provides initial helper functions before processing the action tags.
 */

/**
 * Checks if page is data entry or survey page.
 */
function checkIfPageIsDataentryOrSurvey() {
    // Checking if we are in a data entry or survey page.
    if (!in_array(PAGE, array('DataEntry/index.php', 'surveys/index.php', 'Surveys/theme_view.php'))) {
        return false;
    }

    // Checking additional conditions for survey pages.
    if (PAGE == 'surveys/index.php' && !(isset($_GET['s']) && defined('NOAUTH'))) {
        return false;
    }
    return true;
}

function checkIfRecordExists() {
    // Checking current record ID.
    if (empty($_GET['id'])) {
        return false;
    }
    return true;
}

function fieldOrFormHasData() {
    global $quesion_by_section, $pageFields;
    $is_survey = PAGE != 'DataEntry/index.php';
    if ($is_survey && $question_by_section && Records::fieldsHaveData($record, $pageFields[$_GET['__page__']], $_GET['event_id'])) {
        // The page has data.
        return true;
    }

    if (Records::formHasData($record, $_GET['page'], $_GET['event_id'], $_GET['instance'])) {
        // The page has data.
        return true;
    }
    return false;
}
?>