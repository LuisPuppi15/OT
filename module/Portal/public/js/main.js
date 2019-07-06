$(function() {
	$('.bxslider').bxSlider({
		mode: 'fade',
		pager: false,
		responsive: true,
		captions: true,
		auto: true,
		pause: 6000,
	});

	$('.menu-superior .drowdown').hover(
		function() {
			if (!$(this).hasClass('open')) {
				$(this).addClass('open');
			};
		},
		function() {
			$(this).removeClass('open');
		}
	);

	var contador = $('.usuario a');
	var htmlContador = $('.usuario a').html();
	$('.usuario span').html(htmlContador);
	contador.remove();
});