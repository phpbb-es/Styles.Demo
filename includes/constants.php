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
	// Admin roles
	const ROLE_ADMIN_DEMO = 'ROLE_ADMIN_DEMO';

	// Username of the anonymous account
	const ANONYMOUS_USERNAME = 'Anonymous';

	// Screenshot types
	const SCREENSHOT_EXT = '.png';
	const SCREENSHOT_TYPE_LOCAL = 0;
	const SCREENSHOT_TYPE_JSON = 1;
	const SCREENSHOT_TYPE_PHANTOM = 2;

	// Minimum width/height
	const MIN_PHONE_WIDTH = 100;
	const MIN_SCREEN_WIDTH = 800;
	const MIN_SCREEN_HEIGHT = 600;

	// PhantomJS
	const PHANTOM_URL = 'http://phantomjs.org/download.html';

	// Styles
	const DEFAULT_STYLE = 'prosilver';// Do not change it! This is not the default style of your board.
	const STYLES_PER_PAGE = 20;
	const MAX_CONFIG_NAME = 255;

	// Currency symbol
	const CURRENCY_SYMBOL = '$%d';
}
