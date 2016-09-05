/**
* Based on Switcheroo by OriginalEXE
* https://github.com/OriginalEXE/Switcheroo
* MIT licenced
*/

// Global 'use strict', wrap it up in functions if you can't deal with it...
'use strict';

var $viewportButtons = $('.phone-btn, .tablet-btn, .desktop-btn'),
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

		var priceRibbon = (object.price) ? '<div class="ribbon"><span>' + object.price_label + '</span></div>' : '';

		$styleList.append('<a class="style pull-left" data-id="' + key + '" ' + tooltip + '><img src="' + object.img + '" alt="' + object.name + '" width="300" height="300"><span data-toggle="popover" data-content="' + object.info.replace(/"/g, '\'') + '" class="title">' + object.name + '</span><span class="badge"><i class="fa fa-star"></i> ' + object.phpbb + '</span>' + priceRibbon + '</a>');

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
			$('#styleName').html($styles[$current_style].name);
			$('#styleInfo').html($styles[$current_style].phpbb_info + '<br>' + $styles[$current_style].info);
			$('#styleInfoDialog').modal('show');
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
			$('#styleInfoDialog').modal('hide');

			if ($styles[$current_style].price || !$downloadDirect)
			{
				window.open($styles[$current_style].download);
			}
			else
			{
				top.location.href = $styles[$current_style].download;
			}
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
			$('#styleInfoDialog').modal('hide');
			window.open($styles[$current_style].details);
		}

		return false;
	}
);

// Support button on click
$('#getSupport').click(
	function()
	{
		if ($current_style in $styles)
		{
			$('#styleInfoDialog').modal('hide');
			window.open($styles[$current_style].support);
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
			$('#langButton').prop('class', 'fa fa-globe show-tooltip');
			$('#langButton').tooltip('hide');
			$('#langButton').attr('data-original-title', $default_to_switch_title);
		}
		else
		{
			$('#langButton').prop('class', 'fa fa-star show-tooltip');
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

		// Also add the switch-lang parameter l=1 and the last-page parameter z=... to URL
		if ($current_style in $styles)
		{
			switcher_viewport_buttons();
			$styleIframe.prop('src', $styles[$current_style].url + '&l=1&z=' + encodeURIComponent($lastViewIframe.replace($prefixUrl, '').replace(/&amp;/g, '&')));
			location.hash = '#' + $current_style;
		}

		return false;
	}
);

// Bail out if mobile, it does not behave good...
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
		$viewportButtons.addClass('disabled').removeClass('visible').css({'opacity': 0, 'visibility': 'hidden'});
	}
	else
	{
		$viewportButtons.removeClass('disabled').addClass('visible').css({'opacity': 1, 'visibility': 'visible'});
	}
}

$(document).ready(switcher_iframe_height);
$(window).on('resize load', switcher_iframe_height);

// Switching device views
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
		$styleIframe.animate({'width': $tabletWidth + 'px'});

		return false;
	}
);

$('.phone-btn' ).on('click',
	function()
	{
		$styleIframe.animate({'width': $phoneWidth + 'px'});

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
		// Do not keep ?sid=... in the URL address
		if (top.location.href.indexOf('?sid=') != -1 && history.pushState)
		{
			var newURL = top.location.protocol + "//" + top.location.host + top.location.pathname;
			window.history.pushState({path: newURL}, '', newURL);
		}

		// Convert <url>?style=<style_varname> into <url>#<style_varname> in the URL address
		if (top.location.href.indexOf('?style=') != -1 && history.pushState)
		{
			var newURL = top.location.protocol + "//" + top.location.host + top.location.pathname + top.location.search.replace('?style=', '#');
			window.history.pushState({path: newURL}, '', newURL);
		}

		$current_style = location.hash.replace('#', '');

		if (!($current_style in $styles) || $current_style === '')
		{
			$current_style = location.search.replace('?style=', '');

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
		if ($('#langButton').length)
		{
			if ($current_lang == $default_lang)
			{
				$('#langButton').prop('class', 'fa fa-globe show-tooltip');
				$('#langButton').tooltip('hide');
				$('#langButton').attr('data-original-title', $default_to_switch_title);
			}
			else
			{
				$('#langButton').prop('class', 'fa fa-star show-tooltip');
				$('#langButton').tooltip('hide');
				$('#langButton').attr('data-original-title', $switch_to_default_title);
			}
		}

		// Update get button
		if ($styles[$current_style].download || $styles[$current_style].mirror !== null)
		{
			if ($styles[$current_style].price)
			{
				$('#getButton').prop('class', 'fa fa-shopping-cart animate-pulse show-tooltip');
				$('#getButton').attr('data-original-title', $label_purchase);
			}
			else
			{
				$('#getButton').prop('class', 'fa fa-download animate-pulse show-tooltip');
				$('#getButton').attr('data-original-title', $label_download);
			}
		}
		else
		{
			$('#getButton').prop('class', 'fa fa-info-circle animate-pulse show-tooltip');
			$('#getButton').attr('data-original-title', $label_info);
		}

		// Update buttons on styleInfoDialog
		if ($styles[$current_style].support.length)
		{
			$('#getSupport').prop('disabled', false);
			$("#getSupport").removeClass('hidden');
		}
		else
		{
			$('#getSupport').prop('disabled', true);
			$("#getSupport").addClass('hidden');
		}

		if ($styles[$current_style].details.length)
		{
			$('#viewDetails').prop('disabled', false);
			$("#viewDetails").removeClass('hidden');
		}
		else
		{
			$('#viewDetails').prop('disabled', true);
			$("#viewDetails").addClass('hidden');
		}

		if ($styles[$current_style].download.length)
		{
			$('#downloadPurchase').prop('disabled', false);

			if ($styles[$current_style].price)
			{
				$('#downloadPurchase').html('<i class="fa fa-shopping-cart"></i> ' + $label_purchase);
				$('#downloadPurchase').prop('class', 'btn btn-danger');
			}
			else
			{
				$('#downloadPurchase').html('<i class="fa fa-download"></i> ' + $label_download);
				$('#downloadPurchase').prop('class', 'btn btn-success');
			}
		}
		else
		{
			$('#downloadPurchase').prop('disabled', true);
			$("#downloadPurchase").addClass('hidden');
		}

		if ($styles[$current_style].price || $styles[$current_style].mirror === null)
		{
			$('#getMirror').prop('disabled', true);
			$("#mirrorArea").addClass('hidden');
		}
		else
		{
			$('#getMirror').prop('disabled', false);
			$("#mirrorArea").removeClass('hidden');

			$.each($styles[$current_style].mirror,
				function (key, object)
				{
					$('#mirrorList').append('<li><a href="' + object.url + '"><i class="fa fa-caret-right"></i>&nbsp;&nbsp;' + object.name + '</a></li>');
				}
			);
		}

		// Update style name + price label
		var styleIsFree = ($styles[$current_style].price) ? '<span class="label label-danger hidden-xs">' + $styles[$current_style].price_label + '</span>' : '<span class="label label-success hidden-xs">' + $label_free + '</span>';
		$('.style-switcher a').html('<strong>' + $styles[$current_style].name + '</strong>' + '&nbsp;&nbsp;' + styleIsFree);

		switcher_viewport_buttons();
		$styleIframe.prop('src', $styles[$current_style].url);

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

// Style box on click
$('.style').click(
	function()
	{
		$current_style = $(this).data('id');

		if ($current_style in $styles)
		{
			if ($autoToggle)
			{
				$body.toggleClass('toggle');
			}

			$('.preloader, .preloading-icon').fadeIn(400);

			$styleIframe.load(
				function()
				{
					$('.preloader, .preloading-icon').fadeOut(400);
				}
			);

			// Update get button
			if ($styles[$current_style].download || $styles[$current_style].mirror !== null)
			{
				if ($styles[$current_style].price)
				{
					$('#getButton').prop('class', 'fa fa-shopping-cart animate-pulse show-tooltip');
					$('#getButton').attr('data-original-title', $label_purchase);
				}
				else
				{
					$('#getButton').prop('class', 'fa fa-download animate-pulse show-tooltip');
					$('#getButton').attr('data-original-title', $label_download);
				}
			}
			else
			{
				$('#getButton').prop('class', 'fa fa-info-circle animate-pulse show-tooltip');
				$('#getButton').attr('data-original-title', $label_info);
			}

			// Update buttons on styleInfoDialog
			if ($styles[$current_style].support.length)
			{
				$('#getSupport').prop('disabled', false);
				$("#getSupport").removeClass('hidden');
			}
			else
			{
				$('#getSupport').prop('disabled', true);
				$("#getSupport").addClass('hidden');
			}

			if ($styles[$current_style].details.length)
			{
				$('#viewDetails').prop('disabled', false);
				$("#viewDetails").removeClass('hidden');
			}
			else
			{
				$('#viewDetails').prop('disabled', true);
				$("#viewDetails").addClass('hidden');
			}

			if ($styles[$current_style].download.length)
			{
				$('#downloadPurchase').prop('disabled', false);

				if ($styles[$current_style].price)
				{
					$('#downloadPurchase').html('<i class="fa fa-shopping-cart"></i> ' + $label_purchase);
					$('#downloadPurchase').prop('class', 'btn btn-danger');
				}
				else
				{
					$('#downloadPurchase').html('<i class="fa fa-download"></i> ' + $label_download);
					$('#downloadPurchase').prop('class', 'btn btn-success');
				}
			}
			else
			{
				$('#downloadPurchase').prop('disabled', true);
				$("#downloadPurchase").addClass('hidden');
			}

			// Reset the mirror list
			$('#mirrorList').html('');

			if ($styles[$current_style].price || $styles[$current_style].mirror === null)
			{
				$('#getMirror').prop('disabled', true);
				$("#mirrorArea").addClass('hidden');
			}
			else
			{
				$('#getMirror').prop('disabled', false);
				$("#mirrorArea").removeClass('hidden');

				$.each($styles[$current_style].mirror,
					function (key, object)
					{
						$('#mirrorList').append('<li><a href="' + object.url + '"><i class="fa fa-caret-right"></i>&nbsp;&nbsp;' + object.name + '</a></li>');
					}
				);
			}

			// Update style name + price label
			var styleIsFree = ($styles[$current_style].price) ? '<span class="label label-danger hidden-xs">' + $styles[$current_style].price_label + '</span>' : '<span class="label label-success hidden-xs">' + $label_free + '</span>';
			$('.style-switcher a').html('<strong>' + $styles[$current_style].name + '</strong>' + '&nbsp;&nbsp;' + styleIsFree);

			$styleIframe.prop('src', $styles[$current_style].url);
			location.hash = '#' + $current_style;
		}

		switcher_viewport_buttons();

		return false;
	}
);

// Remember the last iframe href
$styleIframe.load(
	function()
	{
    	$lastViewIframe = this.contentWindow.location.href;
	}
);
