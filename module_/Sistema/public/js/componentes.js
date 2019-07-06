define(function(require) {
	var my = this;
	var self = {};

	// Componentes
	var helpers = require('helpers');

	self.componentes = {};

	my.htmlLoad = function(html, $scope) {
		var $html = $(html);
		my.limpiarComponentes($scope);
		$scope.html($html);
		my.cargarComponentes($html);
	}

	self.registrarComponentes = function($html) {
		$html.parent().find('.componente').each(function() {
			var $componente = $(this);
			var strComponente = $componente.data('componente').split(' ');

			for (var i = 0; i < strComponente.length; i++) {
				var componente = new(require(strComponente[i]))();
				var id = self.generarId(strComponente[i]);
				var dataNombre = $componente.data(strComponente[i] + '-nombre');
				var nombres = [];

				if (dataNombre) {
					var strNombre = dataNombre.split(' ');

					for (var j = 0; j < strNombre.length; j++) {
						nombres.push(strNombre[j]);
					};
				};

				if ($componente.data(strComponente[i] + '-id') === undefined) {
					$componente.data(strComponente[i] + '-id', id);
				}

				self.componentes[id] = {
					'id': id,
					'nombres': nombres,
					'instancia': componente,
					'padres': {},
				};
			};
		});

		self.setPadres($html);
	}

	my.cargarComponentes = function($html) {
		self.registrarComponentes($html);

		$html.parent().find('.componente').each(function() {
			var $componente = $(this);
			var strComponentes = $componente.data('componente').split(' ');

			for (var i = 0; i < strComponentes.length; i++) {
				var id = $componente.data(strComponentes[i] + '-id');
				self.componentes[id].instancia.init($componente, id);
			};
		});
	}

	self.generarId = function(strComponente) {
		var id = strComponente + '-' + Math.round((Math.random() * Math.random()) * Math.pow(10, 15));
		return id;
	}

	self.getComponente = function(id) {
		return self.componentes[id];
	}

	self.setPadres = function($html) {
		$html.parent().find('.componente').each(function() {
			$componente = $(this);
			var strComponentes = $componente.data('componente').split(' ');

			for (var i = 0; i < strComponentes.length; i++) {
				var id = $componente.data(strComponentes[i] + '-id');
				var componente = self.getComponente(id);

				var $padre = $componente.filter(function() {
					var $elemento = $(this);
					var strComponente = $elemento.data('componente').split(' ');

					for (var i = 0; i < strComponente.length; i++) {
						if ($elemento.data(strComponente[i] + '-id') == id) {
							return true;
						};
					};
				}).parents('.componente:first');

				if ($padre.length > 0) {
					var strComponente = $padre.data('componente').split(' ');

					for (var i = 0; i < strComponente.length; i++) {
						var padreId = $padre.data(strComponente[i] + '-id');
						var dataNombres = $padre.data(strComponente[i] + '-nombre');

						if (dataNombres) {
							var strNombres = dataNombres.split(' ');

							for (var j = 0; j < strNombres.length; j++) {
								componente.padres[strNombres[j]] = self.componentes[padreId].id;
							};
						};

					};
				};
			};
		});
	}

	my.getComponenteHijo = function(idPadre, nombre) {
		for (var id in self.componentes) {
			var componente = self.componentes[id];
			var cNombre = componente.nombre;

			for (var prop in componente.padres) {
				var cIdPadre = componente.padres[prop];

				if (cIdPadre == idPadre) {
					for (var i = 0; i < componente.nombres.length; i++) {
						var cNombre = componente.nombres[i];

						if (cNombre == nombre) {
							return componente.instancia;
						};
					};
				};
			}
		}
	};

	/**
	 * Cuando tenga varios componentes con el mismo nombre,
	 * por ejemplo modales con nombre modal-post
	 */
	my.getComponenteHijoPorClase = function(idPadre, nombre, clase) {}

	my.limpiarComponentes = function($html) {
		function eliminarComponente($componente) {
			var strComponentes = $componente.data('componente').split(' ');
			for (var i = 0; i < strComponentes.length; i++) {
				var id = $componente.data(strComponentes[i] + '-id');
				delete self.componentes[id];
			};
		}

		if ($html.hasClass('.componente')) {
			eliminarComponente($html);
		};
		$html.find('.componente').each(function () {
			eliminarComponente($(this));
		});
	}

	return my;
});