/**
* Based on Switcheroo by OriginalEXE
* https://github.com/OriginalEXE/Switcheroo
* MIT licenced
*/

// Global "use strict", wrap it up in functions if you can't deal with it...
"use strict";

var $viewportButtons = $('.mobile-btn, .tablet-btn, .desktop-btn'),
	$styleList = $('.styles-list'),
	$body = $('body'),
	$lastViewIframe = '',
	$styleIframe = $('.style-iframe');

// Insert styles to carousel
$.each($styles,
	function(key, object)
	{
		if ('tooltip' in object)
		{
			var tooltip = 'title="' + object.tooltip.replace(/"/g, '\'') + '"';
		}
		else
		{
			var tooltip = '';
		}

		var priceRibbon = (object.price > 0) ? '<div class="ribbon"><span>' + object.price_label + '</span></div>' : '';

		$styleList.append('<a class="style pull-left" data-id="' + key + '" ' + tooltip + '><img src="' + object.img + '" alt="' + object.name + '" width="300" height="300"><span data-toggle="popover" data-content="' + object.info + '" class="title">' + object.name + '</span><span class="badge"><i class="fa fa-star"></i> ' + object.phpbb + '</span>' + priceRibbon + '</a>');

		// Display style info
		$('.title').popover(
			{
				trigger: 'hover',
				placement: 'top',
				html: true
			}
		);
	}
);

// Get button on click
$('.get-btn').click(
	function()
	{
		if ($current_style in $styles)
		{
			$('#styleName').html($styles[$current_style]['name']);
			$('#styleInfo').html($styles[$current_style]['phpbb_info'] + '<br>' + $styles[$current_style]['info']);

			if ($styles[$current_style]['price'] > 0)
			{
				$('#downloadPurchase').html('<i class="fa fa-shopping-cart"></i> ' + $label_purchase);
				$('#downloadPurchase').attr('class', 'btn btn-danger');
			}
			else
			{
				$('#downloadPurchase').html('<i class="fa fa-download"></i> ' + $label_download);
				$('#downloadPurchase').attr('class', 'btn btn-success');
			}

			$('#downloadAlert').modal('show');
		}

		return false;
	}
);

// Download/Purchase button on click
$('#downloadPurchase').click(
	function()
	{
		if ($current_style in $styles)
		{
			$('#downloadAlert').modal('hide');
			top.location.href = $styles[$current_style]['download'];
		}

		return false;
	}
);

// Details button on click
$('#viewDetails').click(
	function()
	{
		if ($current_style in $styles)
		{
			$('#downloadAlert').modal('hide');
			window.open($styles[$current_style]['vinabb']);
		}

		return false;
	}
);

// Language button on click
$('.lang-btn').click(
	function()
	{
		// Switch language
		$current_lang = ($current_lang == $default_lang) ? $switch_lang : $default_lang;

		// Update language button
		if ($current_lang == $default_lang)
		{
			$('#langButton').attr('class', 'fa fa-globe show-tooltip');
			$('#langButton').tooltip('hide');
			$('#langButton').attr('data-original-title', $default_to_switch_title);
		}
		else
		{
			$('#langButton').attr('class', 'fa fa-star show-tooltip');
			$('#langButton').tooltip('hide');
			$('#langButton').attr('data-original-title', $switch_to_default_title);
		}

		// Pre-loading effects
		$('.preloader, .preloading-icon').fadeIn(400);

		$styleIframe.load(
			function()
			{
				$('.preloader, .preloading-icon').fadeOut(400);
			}
		);

		// Also add the last-page parameter z=... to URL
		if ($current_style in $styles)
		{
			switcher_viewport_buttons();
			$styleIframe.attr('src', $styles[$current_style].url_lang + '&z=' + encodeURIComponent($lastViewIframe.replace($prefixUrl, '').replace('&amp;', '&')));
			location.hash = '#' + $current_style;
		}

		return false;
	}
);

// Bail out if mobile, it does not behave good, damn idevices...
/*if (jQuery.browser.mobile)
{
	if ($current_style in $styles)
	{
		top.location.href = $styles[$current_style].url;
	}
}*/

// Switch button on click
$('.switch-btn').click(
	function()
	{
		top.location.href = $switch_mode_url;

		return false;
	}
);

// Close bar on click
$('.remove-btn').click(
	function()
	{
		if ($current_style in $styles)
		{
			top.location.href = $styles[$current_style].url;
		}

		return false;
	}
);

// Let's calculate iframe height
function switcher_iframe_height()
{
	if ($body.hasClass('toggle'))
	{
		return;
	}

	var	$w_height = $(window).height(),
		$b_height = $('.switcher-bar').height() + $('.switcher-body').height(),
		$i_height = $w_height - $b_height - 2;

	$styleIframe.height($i_height);
}

// Check if viewport buttons should be displayed
function switcher_viewport_buttons()
{
	if ('undefined' !== typeof $styles[$current_style].responsive && $styles[$current_style].responsive === 0)
	{
		$('.desktop-btn').click();
		$viewportButtons.addClass('disabled').removeClass('visible').css({ 'opacity': 0, 'visibility': 'hidden' });
	}
	else
	{
		$viewportButtons.removeClass('disabled').addClass('visible').css({ 'opacity': 1, 'visibility': 'visible' });
	}
}

$(document).ready(switcher_iframe_height);
$(window).on('resize load', switcher_iframe_height);

// Switching views
$('.desktop-btn').on('click',
	function()
	{
		$styleIframe.animate({'width': $(window).width()});

		return false;
	}
);

$('.tablet-btn').on('click',
	function()
	{
		$styleIframe.animate({'width': '768px'});

		return false;
	}
);

$('.mobile-btn' ).on('click',
	function()
	{
		$styleIframe.animate({'width': '480px'});

		return false;
	}
);

// Yeah, I use carousel, sue me.
$styleList.carouFredSel(
	{
		auto: false,
		circular: false,
		infinite: false,
		cookie: 'position',
		mousewheel: true,
		scroll:
		{
			items: 'page'
		},
		swipe:
		{
			onTouch	: true,
			onMouse	: true
		},
		width: '100%',
		prev: '.styles-prev',
		next: '.styles-next'
	}
);

// On click, toggle style switcher
$('.style-switcher a').on('click',
	function()
	{
		$body.toggleClass('toggle');

		if (!$body.hasClass('toggle'))
		{
			setTimeout('switcher_iframe_height()', 210);
			setTimeout('switcher_iframe_height()', 310);
			setTimeout('switcher_iframe_height()', 410);
			setTimeout('switcher_iframe_height()', 1500);
			setTimeout('switcher_iframe_height()', 2500);
		}

		return false;
	}
);

// Hide preloader on iframe load
$styleIframe.load(
	function()
	{
		$('.preloader, .preloading-icon').fadeOut(400);
	}
);

// Start the application
$(document).ready(
	function()
	{
		$current_style = location.hash.replace('#', '');

		if (!($current_style in $styles) || $current_style === '')
		{
			$current_style = location.search.replace('?select=', '');

			if (!($current_style in $styles) || $current_style === '')
			{
				for (var key in $styles)
				{
					if ($styles.hasOwnProperty(key))
					{
						break;
					}
				}

				$current_style = key;
			}
		}

		// Update language button
		if ($('#langButton').length > 0)
		{
			if ($current_lang == $default_lang)
			{
				$('#langButton').attr('class', 'fa fa-globe show-tooltip');
				$('#langButton').tooltip('hide');
				$('#langButton').attr('data-original-title', $default_to_switch_title);
			}
			else
			{
				$('#langButton').attr('class', 'fa fa-star show-tooltip');
				$('#langButton').tooltip('hide');
				$('#langButton').attr('data-original-title', $switch_to_default_title);
			}
		}

		// Update get button
		if ($('#getButton').length > 0)
		{
			if ($styles[$current_style].price > 0)
			{
				$('#getButton').attr('data-original-title', $label_purchase);
				$('#getButton').attr('class', 'fa fa-shopping-cart animate-pulse show-tooltip');
			}
			else
			{
				$('#getButton').attr('data-original-title', $label_download);
				$('#getButton').attr('class', 'fa fa-download animate-pulse show-tooltip');
			}
		}

		// Update style name + price label
		var styleIsFree = ($styles[$current_style].price > 0) ? '<span class="label label-danger">' + $styles[$current_style].price_label + '</span>' : '<span class="label label-success">' + $label_free + '</span>';
		$('.style-switcher a').html('<strong>' + $styles[$current_style].name + '</strong>' + '&nbsp;&nbsp;' + styleIsFree);

		switcher_viewport_buttons();
		$styleIframe.attr('src', $styles[$current_style].url);

		$('.style').tooltip(
			{
				container: 'body',
				html: true,
				placement: 'auto bottom',
				trigger: 'hover'
			}
		);

		$('.show-tooltip').tooltip({placement: 'bottom'});
	}
);

$('.style').click(
	function()
	{
		$current_style = $(this).data('id');

		if ($current_style in $styles)
		{
			$body.toggleClass('toggle');
			$('.preloader, .preloading-icon').fadeIn(400);

			$styleIframe.load(
				function()
				{
					$('.preloader, .preloading-icon').fadeOut(400);
				}
			);

			// Update get button
			if ($('#getButton').length > 0)
			{
				if ($styles[$current_style].price > 0)
				{
					$('#getButton').attr('data-original-title', $label_purchase);
					$('#getButton').attr('class', 'fa fa-shopping-cart animate-pulse show-tooltip');
				}
				else
				{
					$('#getButton').attr('data-original-title', $label_download);
					$('#getButton').attr('class', 'fa fa-download animate-pulse show-tooltip');
				}
			}

			// Update style name + price label
			var styleIsFree = ($styles[$current_style].price > 0) ? '<span class="label label-danger">' + $styles[$current_style].price_label + '</span>' : '<span class="label label-success">' + $label_free + '</span>';
			$('.style-switcher a').html('<strong>' + $styles[$current_style].name + '</strong>' + '&nbsp;&nbsp;' + styleIsFree);

			$styleIframe.attr('src', $styles[$current_style].url);
			location.hash = '#' + $current_style;
		}

		switcher_viewport_buttons();

		return false;
	}
);

$styleIframe.load(function(){
    $lastViewIframe = this.contentWindow.location.href;
});
