# Styles Demo
For phpBB 3.1.2+ - 3.2.0

Tested phpBB versions: 3.1.2, 3.1.9, 3.2.0-RC1

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
