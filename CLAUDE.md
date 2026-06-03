# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **REDCap External Module** ("Auto Populate Fields", v2.6.5) that extends REDCap's field default value functionality via custom action tags. It enables auto-population of form fields with fallback chains, previous longitudinal event lookups, and chronological event detection.

- **Namespace:** `AutoPopulateFields\ExternalModule`
- **Framework version:** 15
- **Requires:** REDCap >= 14.6.4, PHP >= 7.4

## Testing

There is no automated test suite. Testing is manual using the REDCap instance:
- See `testing.md` for instructions
- Import `examples/test_project.xml` (longitudinal, 8 events) into REDCap
- Use the CSV files in `examples/` for specific test scenarios
- Install the module by symlinking or copying the repo to `<redcap-root>/modules/auto_populate_fields_v<version>`

## Architecture

All backend logic lives in a single file: **`ExternalModule.php`**.

The two primary hooks are:
- `redcap_data_entry_form_top()` — entry point for data entry pages
- `redcap_survey_page_top()` — entry point for survey pages; calls `setDefaultValues()` then appends @DEFAULT to survey fields using the module's custom tags

### Core flow

Both hooks call **`setDefaultValues()`** — the central method. It iterates project metadata, finds fields with custom action tags, resolves values via REDCap's `Piping` class, and temporarily overrides metadata to inject computed defaults before REDCap renders the page.
   - Overrides choice field enums so piped values return raw keys, not labels
   - Processes `@DEFAULT-FROM-PREVIOUS-EVENT` by querying prior events (or chronologically last event via SQL on `redcap_log_event`)
   - Chains through `@DEFAULT_<N>` numbered fallback tags
   - Handles date format conversions (MDY/DMY/YMD)

### Key helper methods in ExternalModule.php

| Method | Purpose |
|--------|---------|
| `getMultipleActionTagsQueue()` | Finds and sorts all `@DEFAULT*` action tag variants for a field |
| `overrideActionTag()` | Replaces action tag values in metadata |
| `getValueInActionTag()` | Extracts the value/expression from a tag |
| `currentFormHasData()` | Prevents overwriting existing data |

## Action Tags Implemented

- `@DEFAULT_<N>` — Numbered fallback defaults (e.g., `@DEFAULT_1`, `@DEFAULT_2`)
- `@DEFAULT-FROM-PREVIOUS-EVENT` — Copies value from the same field in the previous event
- `@DEFAULT-FROM-PREVIOUS-EVENT_<N>` — Numbered variants for precedence control
- Both support field mapping syntax: `@DEFAULT-FROM-PREVIOUS-EVENT='{"field_name": "other_field"}'`

## Module Settings (config.json)

| Key | Type | Effect |
|-----|------|--------|
| `use_in_survey` | checkbox | Enables the module on survey pages |
| `chronological_previous_event` | checkbox | When events aren't in chronological order, detects the last event by actual date rather than arm position |

## Important Conventions

- No `eval()` in PHP (explicitly removed in v2.6.2)
- PSR-2 code style
- No Composer, npm, or build tooling — pure PHP
- When adding action tags, add an entry to the `action-tags` array in `config.json` so the Online Designer shows documentation for the new tag
- The module must be backward-compatible with the REDCap minimum version; check for function existence before calling REDCap internals (see existing `method_exists`/`function_exists` guards in the code)
- Like all REDCap External modules, this module conforms to the documentation and conventions described in the REDCap External Modulke Framework at https://github.com/vanderbilt-redcap/external-module-framework-docs
- The official git repo auto_populate_fields is at https://github.com/ctsit/auto_populate_fields/
