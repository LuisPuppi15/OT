define(function(require) {
	var perusuarioAccesos = function() {
		var my = this;
		var self = {};

		// Módulos
		var $ = require('jquery');
		var ajax = require('ajax');
		var componentes = require('componentes');

		my.id;
		my.scope = {};

		self.dialog = {
			'scope': null,
		};
		self.form = {
			'scope': null,
		};
		self.btnResetear = {
			'scope': null,
			'ruta': null,
		}
		self.dialogoModalPre = {
			'scope': null,
			'btnLlamar': null,
		};

		my.init = function(contenedor, id) {
			my.id = id;
			my.scope = contenedor;
			self.dialog.scope = my.scope.parents('.dialog:first');
			self.form.scope = my.scope.find('form');

			self.initBtnResetear();
			self.initDialogoModalPre();
		}

		self.initBtnResetear = function() {
			self.btnResetear.scope = my.scope.find('.btn-resetear');

			self.btnResetear.scope.on('click', function() {
				self.btnResetear.ruta = $(this).data('ruta');
				self.mostrarAlerta();
			});
		}

		self.initDialogoModalPre = function() {
			self.dialogoModalPre.scope = my.scope.find('.dialog.pre');
			self.dialogoModalPre.btnLlamar = self.dialogoModalPre.scope.find('.llamar');

			self.dialogoModalPre.btnLlamar.on('click', function(e) {
				var respuesta = ajax.ajaxGet(self.btnResetear.ruta);

				if (respuesta.success) {
					self.dialogoModalPre.scope.dialog('close');
					self.dialog.scope.dialog('close');
					$('body').trigger('tabla-mantenedor:listar');

				} else {
					console.log(respuesta);
				};
			});
		}

		self.mostrarAlerta = function() {
			self.dialogoModalPre.scope.dialog('open');
		}
	}

	return perusuarioAccesos;
});