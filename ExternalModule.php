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
        
        $this->initJsVars($input);
        print '<script src="' . $this->getUrl('js/new-action-tag-help-text.js') . '"></script>';
        if ($input['copy_values_from_previous_event']) {
            print '<script src="' . $this->getUrl('js/copy-values-from-previous-event-helper.js') . '"></script>';
        }
        if ($input['default_from_field']) {
            print '<script src="' . $this->getUrl('js/default-from-field-helper.js') . '"></script>';
        }
        if ($input['default_on_visible']) {
            print '<script src="' . $this->getUrl('js/default-on-visible-helper.js') . '"></script>';
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
