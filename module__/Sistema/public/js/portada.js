define(function(require) {
	var portada = function() {
		var my = this;
		var self = {};

		my.id;
		self.scope;
		self.cabecera = {
			'scope': null,
		};
		self.cuerpo = {
			'scope': null,
		};
		self.menuPrincipal = {
			'scope': null,
		};
		self.menuInterno = {
			'scope': null,
		};
		self.items;
		self.contenido = {
			'scope': null,
		};

		// Módulos
		var $ = require('jquery');
		var ajax = require('ajax');
		var componentes = require('componentes');

		my.init = function(contenedor, id) {
			my.id = id;
			self.scope = contenedor;
			self.cabecera.scope = self.scope.find('.row.cabecera');
			self.cuerpo.scope = self.scope.find('.row.cuerpo');
			self.menuPrincipal.scope = self.cuerpo.scope.find('.menu-principal');
			self.menuInterno.scope = self.scope.find('.menu-interno');
			self.contenido.scope = self.scope.find('.contenido');
			self.items = self.menuInterno.scope.find('a[data-toggle="tab"]');

			self.initEventos();
			self.initContenido();
		}

		// Eventos
		self.initEventos = function() {
			self.items.on('click', function(e) {
				self.cssItems($(this));

				var ruta = $(this).data('ruta');
				var id = $(this).attr('href');
				var data = ajax.ajaxGet(ruta);

				self.contenido.scope = $('.contenido').find(id);
				componentes.htmlLoad(data, self.contenido.scope);
			});

			self.menuPrincipal.scope.find('a[role="tab"]').each(function() {
				$(this).on('click', function() {
					if (!$(this).parent().hasClass('active')) {
						var $html = self.scope.find('.contenido .tab-pane.active');
						componentes.limpiarComponentes($html);
						$html.empty();
						self.limpiarEstadoMenus();
					};
				});
			});
		}

		self.limpiarEstadoMenus = function() {
			self.items.parents('li').removeClass('active');
		}

		// Helpers
		self.initContenido = function() {
			self.cssContenido = (function() {
				var altVentana = $(document).height();
				var offset = self.contenido.scope.offset();
				var altura = altVentana - offset.top;

				self.contenido.scope.css('min-height', altura);
			})();
		}

		self.cssItems = function(item) {
			self.items.each(function() {
				$(this).removeClass('active');
			});

			if (item.hasClass('subitem')) {
				item.addClass('active')
			};
		}

	}

	return portada;
});