# Changelog
## [0.0.3]
### Added
- **`npm run link-wp`** New script adding for link project directories to local WordPress installation

## [0.0.2]
### Added
- **Reusable Site Component** A simple theme made with React and WPGraphQL-Composer. Located at `src/shared/site.jsx`.
- **Reusable Provider Component** A simple wrapper provider made with Apollo and React-Router. Located at `src/shared/provider.jsx`.
### Changes
- **SCSS Modules** Shared and App SCSS build configuration modified

## [0.0.1]
### Added
- **Guten-block boilerplate** the boilerplate from [Create Guten Block](https://github.com/ahmadawais/create-guten-block/tree/master/packages/cgb-scripts/template/src). Located at `src/block`;
- **`php`** contains `plugin/shared.php` for loading shared dependencies, `plugin/app.php` for loading options page boilerplate, `plugin/blocks.php` for loading guten-blocks, and `theme/functions.php` for loading theme dependencies. 
- **`src`** contains `theme/index.js` which is loaded by `functions.php`, `blocks/index.js` which is loading by `blocks.php`, `app/index.js` which loading by `app.php`, and `shared/index.js` which is loaded by `shared.php` and used as a dependency by all other scripts project scripts
