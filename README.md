# Styles Demo
For phpBB 3.1.2+ - 3.2.0

Tested phpBB versions: 3.1.2, 3.1.10, 3.2.0-RC2

## Build Status
* **[master]** (For phpBB 3.2) [![Build Status](https://travis-ci.org/VinaBB/Styles.Demo.svg?branch=master)](https://travis-ci.org/VinaBB/Styles.Demo)
* **[3.1.x]** (For phpBB 3.1) [![Build Status](https://travis-ci.org/VinaBB/Styles.Demo.svg?branch=1.1.x)](https://travis-ci.org/VinaBB/Styles.Demo)

## Features
* Preview front-end styles.
* Preview ACP styles.
* Test responsive design with 3 modes: desktop/tablet/phone.
* Switch dual languages with styles.
* Manage local style data or get remote data via JSON.
* Create style screenshots automatically using [PhantomJS](http://phantomjs.org/).

## Update to a new version
* Disable the extension.
* Delete old files but keep the following directories:
  * `./ext/vinabb/stylesdemo/app/styles/`
  * `./ext/vinabb/stylesdemo/bin/` (if using PhantomJS).
* Upload new files.
* Enable the extension again.
