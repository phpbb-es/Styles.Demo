/*!
 * Switcheroo by OriginalEXE
 * https://github.com/OriginalEXE/Switcheroo
 * MIT licenced
 */

// Global "use strict", wrap it up in functions if you can't deal with it...
"use strict";

var $viewportButtons = $('.mobile-btn, .tablet-btn, .desktop-btn'),
	$styleList = $('.styles-list'),
	$body = $('body'),
	$styleIframe = $('.style-iframe');

// Insert styles to carousel
$.each($styles, function(key, object) {
	if ('tooltip' in object) {
		var tooltip = 'title="' + object.tooltip.replace(/"/g, '\'') + '"';
	} else {
		var tooltip = '';
	}

	var newBadge = (object.newest == '1') ? '<div class="ribbon-wrapper-green"><div class="ribbon-green">NEW</div></div>' : '';

	$styleList.append(
		'<a class="style pull-left" data-id="' + key + '" ' + tooltip + '><img src="' + object.img + '" alt="' + object.name + '" width="300" height="300"><span data-toggle="popover" data-content="' + object.info + '" class="title">' + object.name + '</span><span class="badge">' + object.tag + '</span>' + newBadge + '</a>'
//		'<a class="style pull-left" data-id="' + key + '" ' + tooltip + '><img src="' + object.img + '" alt="' + object.name + '" width="236" height="120"><span data-toggle="popover" data-content="' + object.info + '" class="title">' + object.name + '</span><span class="badge">' + object.tag + '</span>' + newBadge + '</a>'
	);

	// Display style info
	$('.title').popover({
		trigger		: 'hover',
		placement	: 'top',
		html		: true
	});
});

// Download button on click
$('.download-btn').click( function() {
	if ($current_style in $styles) {
		if ($styles[$current_style]['price'] > 0) {
			document.getElementById('stylePrice').innerHTML = $styles[$current_style]['price'];
			$('#downloadAlert').modal('show');
		} else {
			top.location.href = $styles[$current_style]['get'];
		}
	}

	return false;
});

$('#agreeDownload').click( function() {
	if ($current_style in $styles) {
		top.location.href = $styles[$current_style]['get'];
	}

	return false;
});

$('#agreePurchase').click( function() {
	if ($current_style in $styles) {
		top.location.href = $styles[$current_style]['buy'];
	}

	return false;
});

// Info button on click
$('.info-btn').click( function() {
	if ($current_style in $styles) {
		window.open($styles[$current_style]['bb']);
	}

	return false;
});

// Bail out if mobile, it does not behave good, damn idevices...
/*if ( jQuery.browser.mobile ) {
	if ( $current_style in $styles ) {
		top.location.href = $styles[$current_style].url;
	}
}*/

// Switch button on click
$('.switch-btn').click( function() {
	top.location.href = $switch_url;
	return false;
});

// Close bar on click
$('.remove-btn').click( function() {
	if ( $current_style in $styles ) {
		top.location.href = $styles[$current_style].url;
	}

	return false;
});

// Let's calculate iframe height
function switcher_iframe_height() {
	if ($body.hasClass('toggle')) return;

	var $w_height = $(window).height(),
		$b_height = $('.switcher-bar').height() + $('.switcher-body').height(),
		$i_height = $w_height - $b_height - 2;

	$styleIframe.height($i_height);
}

// Check if viewport buttons should be displayed
function switcher_viewport_buttons() {
	if ('undefined' !== typeof $styles[$current_style].responsive && $styles[$current_style].responsive === 0) {
		$('.desktop-btn').click();
		$viewportButtons.addClass('disabled').removeClass('visible').css({ 'opacity': 0, 'visibility': 'hidden' });
	} else {
		$viewportButtons.removeClass('disabled').addClass('visible').css({ 'opacity': 1, 'visibility': 'visible' });
	}
}

$(document).ready(switcher_iframe_height);
$(window).on('resize load', switcher_iframe_height);

// Switching views
$('.desktop-btn').on('click', function() {
	$styleIframe.animate({
		'width'	: $(window).width()
	});

	return false;
});

$('.tablet-btn').on('click', function() {
	$styleIframe.animate({
		'width'	: '768px'
	});

	return false;
});

$('.mobile-btn' ).on('click', function() {
	$styleIframe.animate({
		'width'	: '480px'
	});

	return false;
});

// Yeah, I use carousel, sue me.
$styleList.carouFredSel({
	auto		: false,
	circular	: false,
	infinite	: false,
	cookie		: 'position',
	mousewheel	: true,
	scroll		: {
		items	: 'page'
	},
	swipe		: {
		onTouch	: true,
		onMouse	: true
	},
	width		: '100%',
	prev		: '.styles-prev',
	next		: '.styles-next'
});

// On click, toggle style switcher
$('.style-switcher a').on('click', function() {
	$body.toggleClass('toggle');

	if (!$body.hasClass('toggle')) {
		setTimeout('switcher_iframe_height()', 210);
		setTimeout('switcher_iframe_height()', 310);
		setTimeout('switcher_iframe_height()', 410);
		setTimeout('switcher_iframe_height()', 1500);
		setTimeout('switcher_iframe_height()', 2500);
	}

	return false;
});

// Hide preloader on iframe load
$styleIframe.load( function() {
	$('.preloader, .preloading-icon').fadeOut(400);
});

// Start the application
$(document).ready( function() {
	$current_style = location.hash.replace('#', '');

	if (!($current_style in $styles) || $current_style === '') {
		$current_style = location.search.replace('?select=', '');

		if (!( $current_style in $styles) || $current_style === '') {
			for (var key in $styles) if ($styles.hasOwnProperty(key)) break;
			$current_style = key;
		}
	}

	var styleIsFree = ($styles[$current_style].price > 0) ? '<span class="label label-danger">$' + $styles[$current_style].price + '</span>' : '<span class="label label-success">Free</span>';
	var styleIsResponsive = ($styles[$current_style].responsive == '1') ? '<span class="label label-primary">Responsive</span>' : '<span class="label label-warning">Non-Responsive</span>';

	$('.style-switcher a').html( 
		'<strong>' + $styles[$current_style].name + '</strong>'
//		'<strong>' + $styles[$current_style].name + '</strong>' + '&nbsp;&nbsp;' + styleIsFree + '&nbsp;&nbsp;' + styleIsResponsive
	);

	switcher_viewport_buttons();

	$styleIframe.attr('src', $styles[$current_style].url);

	$('.style').tooltip({
		container	: 'body',
		html		: true,
		placement	: 'auto bottom',
		trigger		: 'hover'
	});

	$('.show-tooltip').tooltip({
		placement : 'bottom'
	});
});

$('.style').click( function() {
	$current_style = $(this).data('id');

	if ($current_style in $styles) {
		$body.toggleClass('toggle');

		$('.preloader, .preloading-icon').fadeIn(400);

		$styleIframe.load( function() {
			$('.preloader, .preloading-icon').fadeOut(400);
		});

		var styleIsFree = ($styles[$current_style].price > 0) ? '<span class="label label-danger">$' + $styles[$current_style].price + '</span>' : '<span class="label label-success">Free</span>';
		var styleIsResponsive = ($styles[$current_style].responsive == '1') ? '<span class="label label-primary">Responsive</span>' : '<span class="label label-warning">Non-Responsive</span>';

		$('.style-switcher a').html(
			'<strong>' + $styles[$current_style].name + '</strong>'
//			'<strong>' + $styles[$current_style].name + '</strong>' + '&nbsp;&nbsp;' + styleIsFree + '&nbsp;&nbsp;' + styleIsResponsive
		);

		$styleIframe.attr('src', $styles[$current_style].url);

		location.hash = '#' + $current_style;
	}

	switcher_viewport_buttons();

	return false;
});
