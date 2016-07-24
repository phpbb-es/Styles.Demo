var page = require('webpage').create();

page.viewportSize = {width: {phantom.width}, height: {phantom.height}};
page.clipRect = {top: 0, left: 0, width: {phantom.width}, height: {phantom.width}};

page.onLoadFinished = function(status)
{
	if (status == 'success')
	{
		page.render('{phantom.img}');
	}

	phantom.exit();
};

page.open('{phantom.url}');
