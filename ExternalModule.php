<?php
/**
 * @file
 * Provides ExternalModule class for Auto Populate Fields.
 */

namespace AutoPopulateFields\ExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;

require_once 'includes/helper.php';

/**
 * ExternalModule class for Auto Populate Fields.
 */
class ExternalModule extends AbstractExternalModule {

    /**
     * @inheritdoc
     */
    function hook_every_page_top($project_id) {
        $features = auto_populate_fields_get_available_features();

        $js_vars = array();
        $js_files = array('js/helper.js');

        foreach ($features as $feature) {
            include_once 'includes/' . $feature . '.php';

            $function = 'auto_populate_fields_' . $feature;
            if ($settings = $function()) {
                $js_vars[$feature] = $settings;
                $js_files[] = 'js/' . $feature . '.js';
            }
        }

        // if features are present then hover field note.
        if (!empty($features)) {
            $js_vars['hover_field_note'] = getFieldsWithActionTags();
            $js_files[] = 'js/hover_field_note.js';
        }

        if ($js_vars) {
            // Set up js variables.
            $this->initJsVars($js_vars);
        }

        // Loads js files.
        $this->loadJsFiles($js_files);
    }

    /**
     * Loads js files.
     *
     * @param array $js_files
     *   An array of js files paths within the module.
     */
    function loadJsFiles($js_files) {
        foreach ($js_files as $file) {
            echo '<script src="' . $this->getUrl($file) . '"></script>';
        }
    }

    /**
     * Loads js variables.
     *
     * @param array $varss
     *   An array of js variables to set up.
     */
    function initJsVars($vars) {
        echo '<script> var autoPopulateFields = ' . json_encode($vars) . ';</script>';
    }
}
