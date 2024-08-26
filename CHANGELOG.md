# auto_populate_fields 2.6.1 (released 2024-08-26)
- Initialize eventsForms[$event] as an array if null (@saipavan10-git, #61, #62)
- Preserves survey field action tags (@michael-bentz, #52, #53)

## [2.6.0] - 2020-10-09
### Changed
- Upgrade test_project to enable surveys (Kyle Chesney)
- Explicitly check that field to hide is present in a form prevents bug on multipage forms in surveys. (Kyle Chesney)

### Added
- Add support for Surveys (Kyle Chesney)


## [2.5.2] - 2020-08-21
### Changed
- Added a element check to prevent attempting to hide nonexistent elements (James Pence)
- add minimum REDCap version requirement of 8.0.3 (Kyle Chesney)

## [2.5.1] - 2020-04-24
### Changed
- Address issues found in REDCap 9.4.1 - 9.8.4 (Kyle Chesney)
- Rename license file to match Vandy's spec (Philip Chase)


## [2.5.0] - 2020-01-07
### Added
- Add Zenodo doi to README.md (Kyle Chesney)
- Update queries of redcap_log_event tables to support REDCap >= 9.6.0 (Kyle Chesney)
- Add text_project.xml (Philip Chase)
- Add discussion of temporal order and null auto-population in README (Kyle Chesney)
- Create authors.md (Philip Chase)

### Changed
- Fix crashing on mismatched Date formats (Kyle Chesney)
- Update authors in config.json (Philip Chase)

### Removed
- Remove setting project_id as an index in redcap_log_event table project_id is already a composite index (Kyle Chesney)


## [2.4.0] - 2019-10-20
### Added
- Auto populate date fields in the same format they were entered. (Kyle Chesney)
- Document the need for alter-table privileges to enable the module. (Kyle Chesney)
- Allow auto population on repeating events using the most recent field value. (Kyle Chesney)

### Changed
- Fix auto population from previous events on survey fields with branching logic. (Kyle Chesney)


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
