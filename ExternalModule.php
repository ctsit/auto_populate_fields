<?php
/**
 * @file
 * Provides ExternalModule class for Auto Populate Fields.
 */

namespace AutoPopulateFields\ExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;

/**
 * ExternalModule class for Auto Populate Fields.
 */
class ExternalModule extends AbstractExternalModule {

    /**
     * @inheritdoc
     */
     function hook_every_page_top($project_id) {
        include_once 'includes/copy_values_from_previous_event.php';
        include_once 'includes/default_from_field.php';
        include_once 'includes/default_on_visible.php';

        $input = array();
        $input['copy_values_from_previous_event'] = auto_populate_fields_copy_values_from_previous_event($project_id);
        $input['default_from_field'] = auto_populate_fields_default_from_field();
        $input['default_on_visible'] = auto_populate_fields_default_on_visible();

        // initialize js variables
        $this->initJsVars($input);
        
        // collect all the js files into array
        $js_files = array();
        $js_files[] = 'js/new-action-tag-help-text.js';
        if ($input['copy_values_from_previous_event']) {
            $js_files[] = 'js/copy-values-from-previous-event-helper.js';
        }
        if ($input['default_from_field']) {
            $js_files[] = 'js/default-from-field-helper.js';
        }
        if ($input['default_on_visible']) {
            $js_files[] = 'js/default-on-visible-helper.js';
        }

        // loads all the js files into an array
        $this->loadJsFiles($js_files);
    }

    /**
     * @inheritdoc
     */
    function loadJsFiles ($js_files) {
        foreach($js_files as $file) {
            print '<script src="' . $this->getUrl($file) . '"></script>';
        }
    }

    /**
     * @inheritdoc
     */
    function initJsVars ($input) {
        ?>
        <script>
            var auto_populate_fields = <?php echo json_encode($input); ?>;
        </script>
        <?php
    }
}
