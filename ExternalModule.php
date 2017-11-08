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
        $js_files = array();

        if (PAGE == 'Design/online_designer.php' && $project_id) {
            $js_files[] = 'js/helper.js';
        }

        if ($project_id) {
            $js_vars = array();

            foreach ($this->getFeatures() as $feature) {
                include_once 'includes/' . $feature . '.php';

                $function = 'auto_populate_fields_' . $feature;
                if (function_exists($function) && ($settings = $function())) {
                    $js_vars[$feature] = $settings;
                    $js_files[] = 'js/' . $feature . '.js';
                }
            }

            if ($js_vars) {
                // Set up js variables.
                $this->initJsVars($js_vars);
            }
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
        echo '<script>var autoPopulateFields = ' . json_encode($vars) . ';</script>';
    }

    /**
     * Gets auto populate features names.
     *
     * @return array
     *   An array containing the features names.
     */
    function getFeatures() {
        return array(
            'default_when_visible',
            'default_enum_key',
            'default_from_previous_event',
        );
    }
}
