# REDCap Auto Populate Fields
This REDCap Module provides tools to autopopulate fields.

## Prerequisites
- [REDCap Modules](https://github.com/vanderbilt/redcap-external-modules)

## Installation
- Clone this repo into to `<redcap-root>/modules/auto_populate_fields_v1.0`.
- Go to **Control Center > Manage External Modules** and enable Auto Populate Fields.
- For each project you want to use this module, go to the project home page, click on **Manage External Modules** link, and then enable Auto Populate Fields for that project.

## Features included
This module provides 4 new [action tags](https://wiki.chpc.utah.edu/pages/viewpage.action?pageId=595001400):

#### @DEFAULT-FROM-FIELD
It allows users to set up a field's default value from an existing field on the same form. Use case examples:
- Using hidden fields as source for visible fields, e.g. `@DEFAULT-FROM-FIELD="hidden_first_name"`.
- When a form field has been populated in the backend by a DET or API call - `@DEFAULT` cannot handle this.

#### @DEFAULT-WHEN-VISIBLE
If the field is visible it sets the initial value otherwise it removes the value. This is mainly useful in fields which are visible and hidden by branching logic, e.g. `@DEFAULT-WHEN-VISIBLE="10"`.

#### @DEFAULT-FROM-PREVIOUS-EVENT
Sets a field's default value based on its own value in a previous event. To map the default value from another field, you may specify the source as a parameter to the action tag, e.g `@DEFAULT-FROM-PREVIOUS-EVENT="source_field"`.

#### @AE_ID
Generates custom ids for repeating Adverse Events. The custom id consists of record id, event id and repeating instance id.
