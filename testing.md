# Testing Auto-Populate Fields

Testing REDCap Auto-Populate Fields can be simple or complex, depending on the feature you are testing. This document presents a few tests you can run and describes resources you might use in those tests.

## Example Files

To simplify this process, an example project and testing data sets are provided in the [examples](./examples/) folder.

### test_project.xml

`test_project.xml` is a longitudinal project with 8 events and forms. Each form is enabled as a survey. There is data in the Baseline and Visit 1 events. Visits 2 and 3 and Unscheduled Visit 1-3 are all empty. The event matrix allows longitudinal collection for most forms.

![Event Matrix for test_project.xml](img/event_matrix.png)

Each of the forms test_categorical_data and test_dates use action tags from Auto-Populate Fields on some of their fields. Most fields use the @DEFAULT-FROM-PREVIOUS-EVENT action tag. `birthday_mdy_from_ymd` copies its data from the `birthday_ymd` field on the previous event. `birthday_mdy_from_ymd_static` uses the `@DEFAULT_0` tag to set a static value of '2019-06-29'.

### visit_2_arm_1_data.csv

`visit_2_arm_1_data.csv` has data just for the visit 2 event

### unschedule_visit_1_arm_1_data.csv

`unschedule_visit_1_arm_1_data.csv` has data just for the Unscheduled Visit 1 event. Its data differs from the Visit 2 data.

## Tests

Consider running each of these tests described here to validate the module is behaving correctly. For each test, create a test project from the project XML file, then enable the Auto-Populate Fields module.

### Test @DEFAULT-FROM-PREVIOUS-EVENT

To test the `@DEFAULT-FROM-PREVIOUS-EVENT` action tag, first test a field that uses it without naming another field. Note a field that has that action tag, look at the last filled event on the form that has the field, note or set a value for that field. Then open that form on the next event, the value from the previous event should be set on the new event.

Next, find a field (e.g., `birthday_mdy_from_ymd`) that uses `@DEFAULT-FROM-PREVIOUS-EVENT=some_field_name`. Note the value of `some_field_name` on event N. On event N, open the form that has the field with `@DEFAULT-FROM-PREVIOUS-EVENT=some_field_name`. The value should be pre-filled with the value for `some_field_name` on event N.

Test `@DEFAULT-FROM-PREVIOUS-EVENT` with `Enable chronological previous event detection` enabled. In the module-level configuration, check `Enable chronological previous event detection`. Locate a field on Visit 2 that uses `@DEFAULT-FROM-PREVIOUS-EVENT`. Set the value on visit 2. On Unscheduled Visit 1, note the value from visit 2 being prefilled. Set a higher value on Unscheduled Visit 1. Open the form on visit 3. The field should pre-fill with the higher value from Unscheduled Visit 1. Set an even higher value. Open Unscheduled Visit 2 and note the prefilled value. It should be the highest value, the value from Visit 3.
