$(function(){
	var $window = $(window),
		$body = $('body'),
		pageClass = $body.attr('class'),
		popup = $('.js-popup'),
		dark = $('.js-dark'),
		cssActive = '_active',
		cssHover = '_hover',
		cssDisable = '_disable',
		cssFocus = '_focus',
		cssHide = '_hide',
		cssOpen = '_open',
		cssError = '_error',
		cssScroll = '_scroll'
	;

	// Toggle nav
	(function(){
		var toggleBtn = $('.js-toggle-btn'),
			toggleWrap = $('.js-toggle-wrap')
		;

		toggleBtn.on('click', function(){
			toggleBtn.toggleClass(cssActive);
			toggleWrap.toggleClass(cssActive);
		});
	})();

	// FAQ
	(function(){
		$('.faq-item__btn').on('click', function(e){
			e.preventDefault();
			if ($(this).hasClass('_active')){
				$(this).removeClass('_active');
				$(this).next('.faq-item__wrap').slideUp(200);
			} else {
				$(this).addClass('_active');
				$(this).next('.faq-item__wrap').slideDown(200);
			}
		});
	})();

	// Reviews
	(function(){
		$('.js-review-btn').on('click', function(){
			$(this).fadeOut(100);
			$(this).next('.js-review-wrap').prop('src', 'https://www.youtube.com/embed/' + $(this).data('id') + '?autoplay=1');
		});
	})();

	// Scroll top
	(function(){
		$('.scrolltop').on('click', function(){
			$('html, body').animate({
				scrollTop: 0
			}, 300);

			return false;
		});
		$(window).on('scroll', function(){
			if ($(window).scrollTop() >= 600){
				$('.scrolltop').addClass(cssActive);
			} else {
				$('.scrolltop').removeClass(cssActive);
			}
		});
	})();
	
$(document).ready(function() {

	//E-mail Ajax Send
	$("form").submit(function() { //Change
		var th = $(this);
		var thank = $('.popup_thank');
		var close_popup = $('.closed__popup_js');
		$.ajax({
			type: "POST",
			url: "mail.php", //Change
			data: th.serialize()
		}).done(function() {
			thank.addClass('js-popup _active');
			setTimeout(function() {
				// Done Functions
				close_popup.removeClass('_active');
				th.trigger("reset");
			}, 0);
		});
		return false;
	});

});

$(document).ready(function () {
	$('.popup_thank .popup-close').click(function () {
		$('.popup_thank').removeClass('js-popup _active');
	})
});

	// Map
	(function(){
		$('.map').mouseenter(function(){
			$.getScript("https://api-maps.yandex.ru/2.1/?lang=ru_RU", function(){
				ymaps.ready(init);
				var map;

				function init(){
					var map,
						mapCenter = [55.546022, 37.294538],
						point = [55.546022, 37.294538]
					;

					map = new ymaps.Map("map", {
						center: mapCenter,
						zoom: 12
					},{
						minZoom: 6,
						maxZoom: 20
					});

					addr = new ymaps.Placemark(point, {}, {iconLayout: 'default#image', iconImageHref: '/img/pin.png', iconImageSize: [33, 41], iconImageOffset: [-16.5, -41], zIndex: 4});
					map.behaviors.disable('scrollZoom'); 
					map.geoObjects.add(addr);
					map.controls.add('zoomControl', {top: 50, left: 5});
				}
			});
		});
	})();

	// Owl carousel
	if ($('.owl-carousel').length) {
		for (var i = 0; i < $('.owl-carousel').length; i++) {
			var c = $($('.owl-carousel')[i]);
			$('.owl-carousel')[i].owl = c;

			initOwlCarousel(c);
		}
	}

	function initOwlCarousel(c) {
		var aliaces = ["-", "-sm-", "-md-", "-lg-", "-xl-", "-xxl-"],
			values = [0, 576, 768, 992, 1200, 1600],
			isNoviBuilder = false,
			responsive = {}
		;

		for (var j = 0; j < values.length; j++) {
			responsive[values[j]] = {};
			for (var k = j; k >= -1; k--) {
				if (!responsive[values[j]]["items"] && c.attr("data" + aliaces[k] + "items")) {
					responsive[values[j]]["items"] = k < 0 ? 1 : parseInt(c.attr("data" + aliaces[k] + "items"), 10);
				}
				if (!responsive[values[j]]["stagePadding"] && responsive[values[j]]["stagePadding"] !== 0 && c.attr("data" + aliaces[k] + "stage-padding")) {
					responsive[values[j]]["stagePadding"] = k < 0 ? 0 : parseInt(c.attr("data" + aliaces[k] + "stage-padding"), 10);
				}
				if (!responsive[values[j]]["margin"] && responsive[values[j]]["margin"] !== 0 && c.attr("data" + aliaces[k] + "margin")) {
					responsive[values[j]]["margin"] = k < 0 ? 30 : parseInt(c.attr("data" + aliaces[k] + "margin"), 10);
				}
			}
		}

		// Enable custom pagination
		if (c.attr('data-dots-custom')) {
			c.on("initialized.owl.carousel", function (event) {
				var carousel = $(event.currentTarget),
						customPag = $(carousel.attr("data-dots-custom")),
						active = 0;

				if (carousel.attr('data-active')) {
					active = parseInt(carousel.attr('data-active'), 10);
				}

				carousel.trigger('to.owl.carousel', [active, 300, true]);
				customPag.find("[data-owl-item='" + active + "']").addClass("active");

				customPag.find("[data-owl-item]").on('click', function (e) {
					e.preventDefault();
					carousel.trigger('to.owl.carousel', [parseInt(this.getAttribute("data-owl-item"), 10), 300, true]);
				});

				carousel.on("translate.owl.carousel", function (event) {
					customPag.find(".active").removeClass("active");
					customPag.find("[data-owl-item='" + event.item.index + "']").addClass("active")
				});
			});
		}

		if (c.attr('data-nav-custom')) {
			c.on("initialized.owl.carousel", function (event) {
				var carousel = $(event.currentTarget),
						customNav = $(carousel.attr("data-nav-custom"));

				// Custom Navigation Events
				customNav.find(".owl-arrow-next").click(function (e) {
					e.preventDefault();
					carousel.trigger('next.owl.carousel');
				});
				customNav.find(".owl-arrow-prev").click(function (e) {
					e.preventDefault();
					carousel.trigger('prev.owl.carousel');
				});
			});
		}

		c.owlCarousel({
			autoplay: isNoviBuilder ? false : c.attr("data-autoplay") === "true",
			slideBy: c.attr("data-slideby") ? c.attr("data-slideby") : 1,
			loop: isNoviBuilder ? false : c.attr("data-loop") !== "false",
			items: 1,
			center: c.attr("data-center") === "true",
			dotsContainer: c.attr("data-pagination-class") || false,
			navContainer: c.attr("data-navigation-class") || false,
			mouseDrag: isNoviBuilder ? false : c.attr("data-mouse-drag") !== "false",
			nav: c.attr("data-nav") === "true",
			dots: c.attr("data-dots") === "true",
			dotsEach: c.attr("data-dots-each") ? parseInt(c.attr("data-dots-each"), 10) : false,
			animateIn: c.attr('data-animation-in') ? c.attr('data-animation-in') : false,
			animateOut: c.attr('data-animation-out') ? c.attr('data-animation-out') : false,
			responsive: responsive,
			navText: c.attr("data-nav-text") ? $.parseJSON( c.attr("data-nav-text") ) : [],
			navClass: c.attr("data-nav-class") ? $.parseJSON( c.attr("data-nav-class") ) : ['owl-prev', 'owl-next']
		});
	}

	// Works
	(function(){
		$('.js-more-photo').on('click', function(e){
			e.preventDefault();
			$('.works').find('.js-hidden:lt(6)').removeClass('js-hidden').removeClass('_hidden');
			if ($('.js-hidden').length <= 0){
				$(this).remove();
			}
		});
	})();

	// Scroll
	(function(){
		$('.js-scroll').on('click', function(e){
			e.preventDefault();
			var href = $(this).attr('href'),
				offsetTop = $(href).offset().top-100
			;

			$('.js-toggle-btn').removeClass(cssActive);
			$('.js-toggle-wrap').removeClass(cssActive);
			$('html, body').stop().animate({scrollTop: offsetTop}, 400);
		});
	})();

	// Info
	(function(){
		var btn = $('.js-info-btn'),
			wrap = $('.js-info-wrap')
		;

		btn.on('click', function(e){
			e.preventDefault();
			var href = $(this).attr('href');

			if (!$(this).hasClass(cssActive)){
				btn.removeClass(cssActive);
				$(this).addClass(cssActive);
				wrap.fadeOut(0);
				$(href).fadeIn(200);
			}
		});
	})();

	// Popup
	(function(){
		$('.js-open').on('click', function(e){
			e.preventDefault();
			var href = $(this).data('href') ? $(this).data('href') : $(this).attr('href');

			dark.toggleClass(cssActive);
			$(href).toggleClass(cssActive);
		});
		$('.js-popup-close').on('click', function(e){
			dark.removeClass(cssActive);
			popup.removeClass(cssActive);
		});
		dark.on('click', function(e){
			dark.removeClass(cssActive);
			popup.removeClass(cssActive);
		});
	})();
});

var addFile = function(file){
	var par = $(this).parent(),
		fileName = ''
	;

	if (file.target.value)
		fileName = file.target.value.split('\\').pop();

	par.find('label > span').html(fileName);
}

var calcForm = function(e){
	e.preventDefault();
	var $form = $(this),
		formData = new FormData($form.get(0))
	;
	
	$form.find('.required').each(function(){
		$(this).removeClass('_error');
	});
	$('.required').on('change', function(){
		$(this).removeClass('_error');
	});

	$('#calculateForm').find('input[name="bid_width"]').remove();
	$('#calculateForm').find('input[name="bid_height"]').remove();
	$('#calculateForm').find('input[name="bid_depth"]').remove();
	$('#calculateForm').find('input[name="bid_doors"]').remove();
	$('#calculateForm').find('input[name="bid_facade"]').remove();
	$('#calculateForm').find('input[name="bid_price"]').remove();

}
/*$(document).ready(function() {
	$('.calc .js-bid-submit').click(function() {
		$('.js-dark').addClass('_active');
		$('#calculateForm').addClass('_active');
	});
});*/
var calcFormInput = function(e){
	if ($(this).hasClass('bid_width')){
		if ($(this).val() < 600){
			$(this).val(600);
		}
		if ($(this).val() > 10000){
			$(this).val(10000);
		}
	}
	if ($(this).hasClass('bid_height')){
		if ($(this).val() < 1600){
			$(this).val(1600);
		}
	}
	if ($(this).hasClass('bid_depth')){
		if ($(this).val() < 400){
			$(this).val(400);
		}
	}
}

var submitForm = function(e){
	e.preventDefault();
	var $form = $(this),
		$event = $form.find('.bid_event').val(),
		formData = new FormData($form.get(0)),
		btn = $form.find('.js-bid-submit')
	;
	
	$form.find('.required').each(function(){
		$(this).removeClass('_error');
	});
	$('.required').on('change', function(){
		$(this).removeClass('_error');
	});

	btn.attr('disabled', true);
	
	$.ajax({
		url: '/ajax.php',
		type: $form.attr('method'),
		data: formData,
		contentType: false,
		processData: false,
		dataType: 'json',
		cache: false,
		success: function(data){
			console.log(data);
			console.log($event);
			if(data.result == 'success'){
				$form.html('<div class="good"><b>Спасибо, ваша заявка принята.</b><br>В ближайшие время наш менеджер свяжется с вами.</div>');
				dataLayer.push({'event': $event});
				console.log("'" + $event + "'");
			} else {
				btn.attr('disabled', false);
				for (var errorField in data.text_error){
					$form.find('.' + errorField).addClass('_error');
				}
			}
		},
	});
}

$(document).on('change', '.js-file-add', addFile);
$(document).on('submit', '.js-bid-calc', calcForm);
$(document).on('change', '.js-bid-calc input[type=text]', calcFormInput);
$(document).on('submit', '.js-bid', submitForm);