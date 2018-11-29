# Oil-Based Boilerplate
A boilerplate for developing React-powered Wordpress themes, plugins, and guten-block that using shared components.

## Features
- Uses Gutenberg's React wrappers
- React Components can be shared between the theme, plugin, and guten-blocks. See below for more info
- Docker compose file for testing. More below
- Sass support

## Usage
1. Clone repository `git clone https://github.com/kidunot89/oil-based-boilerplate`.
2. Run `npm install && npm run docker-vols && npm run build` in project working directory.
3. (Docker only *Docker-Compose required*) Run `npm run start-docker` in project working directory.
3. (Local Installation) Run `npm run link-wp -- <path-to-wp-install> <path-to-plugins> <path-to-themes>` in project working directory.
4. Run `npm start` in project working directory.
5. Navigate to `http://localhost:8080/` and run through the installation.
6. Install Gutenberg on Admin dashboard, then activate `Gutenberg` and `Oil-Based` in `Plugins` as well as `Oil-Based` in `Themes`.
7. Navigate to "Permalink" under "Settings" and setting "Common Settings" to anything but "Plain".
8. Now you ready to code. Run `npm run stop-docker` in project working directory to stop and destroy docker containers.

### Usage w/o Docker 
The `link-wp` script simply symlinks links the required project directories into your Wordpress installation. The issue with this is that there is a good chance the script won't work if the user who owns the wordpress plugins and themes directories is not the same user as the one running the script like `www-data`. In situations like this you have two choices.
- Change the owner of the themes and plugins directory to be the user running the script, run the script, and change the owner back to the original user.
- Or Manually symlink all the directories. This means the three sub-directories in the `_dev` directory point to the plugins directory, the `build/plugin` to the plugins directory, `build/theme` to the themes directory.

## Folder Structure
```
├── php
│   ├── plugin - the wordpress plugin static files
│   └── theme - the wordpress theme static files
├── src
│   ├── app
│   │   ├── ...
│   │   └── index.js - admin page React starting point
│   ├── blocks
│   │   ├── ...
│   │   └── index.js - guten-blocks importer
│   ├── shared
│   │   ├── ...
│   │   └── index.js - shared exporter/starting point
│   └── theme
│       ├── ...
│       └── index.js - theme React starting point
├── .gitignore
├── docker-compose.yml
└── package.json 
```

## Use Shared
Importing by relative path between the `theme`, `app`, and `blocks` directories result is a multiple copies of the code in each resulting js file. That's where the `shared` directory comes in. `shared/index.js` should be used as an exporter as shown below.

### Exporter
```
// Imports
import SharedComponent from './shared-component.js';

// Add exports to window object
window.oilBasedShared = {
    SharedComponent,
};
```

`shared/index.js` is compiled into `plugin/shared.js` and enqueued as `oil-based-shared-js` which is used as a dependency along side `wp-element` for `oil-based-theme-js`, `oil-based-app-js`, and `oil-based-block-js`. If you want to use the component exposed in the example above you could do something similar to the next example.

### Import shared component
```
// Import render function from gutenberg's wrapper
const { render } = wp.element;

// Import shared component
const { SharedComponent } = window.oilBasedShared;

render( <SharedComponent />, document.getElementById( 'root' ) );
```

## Thanks
- [Ahmad Awais](https://github.com/ahmadawais) and your [Create-Guten-Block](https://github.com/ahmadawais/create-guten-block) repository inspired a lot of my webpack build configuration, although I'm still working out a few of the babel-loader configurations.
