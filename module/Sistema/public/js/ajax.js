define(function(require) {
	var my = {};

	// Módulos
	var $ = require('jquery');

	my.ajaxGet = function(ruta, beforeSend, complete) {
		var response;

		$.ajax({
			url: ruta,
			type: 'get',
			async: false,
			success: function(data) {
				response = data;
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(jqXHR.responseText);
			},
		});

		return response;
	}

	my.ajaxPost = function(ruta, formData) {
		var response;

		$.ajax({
			url: ruta,
			type: 'post',
			async: false,
			data: formData,
			processData: false,
			contentType: false,
			success: function(data) {
				response = data;
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(jqXHR.responseText);
			},
		});

		return response;
	}

	return my;
});