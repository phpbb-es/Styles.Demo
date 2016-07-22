<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\includes;

class constants
{
	// Extension path
	const EXT_PATH_IN_LANG = './ext/vinabb/stylesdemo/';

	// Admin roles
	const ROLE_ADMIN_DEMO = 'ROLE_ADMIN_DEMO';

	// Screenshot types
	const SCREENSHOT_TYPE_LOCAL = 0;
	const SCREENSHOT_TYPE_JSON = 1;
	const SCREENSHOT_TYPE_PHANTOM = 2;
	const PHANTOM_URL = 'http://phantomjs.org/download.html';

	// Default style
	const DEFAULT_STYLE = 'prosilver';// Do not change it! This is not the default style of your board.
	const DEFAULT_STYLE_NAME = 'proSilver';
	const DEFAULT_STYLE_URL = 'https://www.phpbb.com/';
}
