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
        // include_once 'includes/default_from_field.php';
        // include_once 'includes/default_on_visible.php';

        $input = array();
        $input[] = auto_populate_fields_copy_values_from_previous_event($project_id);
        // auto_populate_fields_default_on_visible();
        // auto_populate_fields_default_from_field();
        $this->initJsVars($input);
        print_r($input);

        print '<script src="' . $this->getUrl('js/copy-values-from-previous-event-helper.js') . '"></script>';        
        // print '<script src="' . $this->getUrl('js/copy-values-from-previous-event-helper.js') . '"></script>';
        // print '<script src="' . $this->getUrl('js/default-from-field-helper.js') . '"></script>';

    }

    function initJsVars ($input) {
        ?>
        <script>
            console.log("hello");
            if (typeof RedcapJsSettings === 'undefined') {
                RedcapJsSettings = {};
            }
            var encodedVal = '<?php echo json_encode($input); ?>';
            console.log(encodedVal);
            var parsedVal = JSON.parse(encodedVal);
            console.log(parsedVal);
            // for(int i = 0; i < parsedVal.length ; i++) {
            //     console.log(parsedVal[i]);
            // }
        </script>
        <?php
    }
}
