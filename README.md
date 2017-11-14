# REDCap Auto Populate Fields
This REDCap Module provides tools to autopopulate fields on data entry forms.

## Prerequisites
- [REDCap Modules](https://github.com/vanderbilt/redcap-external-modules)

## Installation
- Clone this repo into to `<redcap-root>/modules/auto_populate_fields_v1.0`.
- Go to **Control Center > Manage External Modules** and enable Auto Populate Fields.
- For each project you want to use this module, go to the project home page, click on **Manage External Modules** link, and then enable Auto Populate Fields for that project.

## Features included

### Default when visible
By default, when a field that is hidden by branching logic contains a `@DEFAULT` action tag, an annoying alert is displayed on page load.
> ERASE CURRENT VALUE OF FIELD "<field_name>"?

This module changes the default branching logic behavior in order to avoid that. Now, when some non-empty field gets hidden by branching logic, no more warning messages are shown - instead, the hidden value persists available until form submission, when it is finally erased.

### Choice key piping on @DEFAULT
When piping some choice selection field (dropdown, checkboxes) to set a @DEFAULT action tag, the returned value is now the key instead of the label.

### New action tags
This module provides 2 new [action tags](https://wiki.chpc.utah.edu/pages/viewpage.action?pageId=595001400):

#### @DEFAULT-FROM-PREVIOUS-EVENT
Sets a field's default value based on its own value in a previous event. To map the default value from another field, you may specify the source as a parameter to the action tag, e.g `@DEFAULT-FROM-PREVIOUS-EVENT="source_field"`. Analogously to `@DEFAULT_<N>`, `@DEFAULT-FROM-PREVIOUS-EVENT_<N>` is also provided.

#### @DEFAULT_\<N\>
Provides the possibility to define secondary, tertiary, etc default values. If `@DEFAULT` returns an empty value, the next tag available - let's say `@DEFAULT_1` - is checked. If `@DEFAULT_1` returns empty, the next tag available - let's say `@DEFAULT_2` - is checked, and so on. This is useful when a fallback value is needed for piping (e.g. `@DEFAULT="[first_name]" @DEFAULT_1="Joe Doe"`).
