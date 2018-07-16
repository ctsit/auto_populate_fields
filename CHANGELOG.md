# Change Log
All notable changes to the Auto-Populate Fields project will be documented in this file.

## [2.3.0] - 2018-07-16
### Added
- Added option to chronologically detect previous event bsed on the redcap\_log\_event table (tbembersimeao)
- Fixing institution name. (Tiago Bember Simeao)
- Adding link to GitHub documentation on module description. (Tiago Bember Simeao)


## [2.2] - 2017-11-22
### Changed
- Update descriptions for this module (Philip Chase)


## [2.1] - 2017-11-22
### Added
- Handling checkboxes on DEFAULT-FROM-PREVIOUS-EVENT. (Tiago Bember Simeao)

### Changed
- Assure that DEFAULT-FROM-PREVIOUS-EVENT only looks back one event (Tiago Bember Simeao)
- Fixing single quotes piping. (Tiago Bember Simeao)
- Preventing 'leave with unsaved changes' alerts supression. (Tiago Bember Simeao)


## [2.0] - 2017-11-20
### Added
- Added @DEFAULT_<N> action tag
- Added @DEFAULT-FROM-PREVIOUS-EVENT_<N> action tag
- Added "choice key piping on @DEFAULT" feature

### Changed
- Refactored "default when visible" feature

### Removed
- Removed @DEFAULT-FROM-FIELD action tag


## [1.0.0] - 2017-10-24
### Summary
- This is the first release

### Added
- resolve scope issues in change listener for default-when-visible action tag (suryayalla)
- remove the default add days action tag (suryayalla)
- add new functionality to defaultly add days to field action tag (suryayalla)
- Adding license file. (Tiago Bember Simeao)
- Removing Field Note Display from this project. (Tiago Bember Simeao)
- Adding documentation. (Tiago Bember Simeao)
- Refactoring Field Note Display feature. (Tiago Bember Simeao)
- add some help text on the functionality of display_field_note on hover (suryayalla)
- add few modifications to helper.php so that it follows the pattern of appending hook name before each function (suryayalla)
- add field_note_display php file for the new action tag (suryayalla)
- add js files for the new action tag, filed_note_display on hover (suryayalla)
- add hover functionality for field note if custom actions are present (suryayalla)
- Adding documentation. (Tiago Bember Simeao)
- add default on visible and default from from field functionality (suryayalla)
- fixing integration issues and following a generalized approach of moving php files in one side and js files in another (suryayalla)
- wrapping the hooks inside their respective folders (suryayalla)
- Initial commit (Surya Prasanna)
