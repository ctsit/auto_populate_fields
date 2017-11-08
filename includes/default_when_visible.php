<?php
/**
 * @file
 * Provides Default When Visible feature.
 */

/**
 * Handles Default When Visible functionality.
 *
 * The purpose is to avoid the REDCap warnings when a filled field gets hidden
 * on branching logic.
 */
function auto_populate_fields_default_when_visible() {
    if (!isset($_GET['page']) || !function_exists('getBranchingFields')) {
        return;
    }

    $branching_fields = getBranchingFields($_GET['page']);
    return array('branchingTargets' => array_keys($branching_fields[0]));
}
