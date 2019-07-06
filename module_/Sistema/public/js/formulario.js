define(function(require) {
	var formulario = function() {
		var my = this;
		var self = {};

		// Módulos
		var $ = require('jquery');
		var ajax = require('ajax');

		my.id;
		my.scope;

		self.form;
		self.validator;

		my.init = function(contenedor, id) {
			my.id = id;
			my.scope = contenedor;

			self.initValidator();
			self.initSelectsPickers();
			self.initDatepickers();
			self.initInputFiles();
			self.initCkeditor();
		}

		self.initValidator = function() {
			self.form = my.scope.find('form');

			self.form.bootstrapValidator({
				excluded: [':disabled', ':hidden', ':not(:visible)'],
				feedbackIcons: {
					valid: 'glyphicon glyphicon-ok',
					invalid: 'glyphicon glyphicon-remove',
					validating: 'glyphicon glyphicon-refresh'
				},
				live: 'enabled',
				submitButtons: 'button[name="enviar"]',
				fields: {
					'*': {
						trigger: 'change keyup',
					}
				},
				onSuccess: function(e) {
					e.preventDefault();

					for (var prop in CKEDITOR.instances) {
						CKEDITOR.instances[prop].updateElement();
					}

					var $form = $(e.target);
					self.enviarFormulario($form);
				},
			});

			self.validator = $(self.form).data('bootstrapValidator');
		}

		self.initSelectsPickers = function() {
			my.scope.find('select').each(function() {
				$(this).selectpicker();
				$(this).on('change', function() {
					var accion = $(this).data('accion');
					if (accion !== undefined) {
						var ruta = accion.ruta.replace(':parametros', $(this).val());
						var respuesta = ajax.ajaxGet(ruta);

						var control = self.form.find('select[name="' + accion.control + '"]');
						control.html(respuesta.options);
						control.selectpicker('refresh');
					};
				});
			});
		}

		self.initDatepickers = function() {
			my.scope.find('[data-provide="datepicker"]').each(function() {
				var input = $(this);
				input.datepicker('update');
				input.on('changeDate', function() {
					input.datepicker('hide');
					self.validator.revalidateField(input);
				});
			});
		}

		self.initInputFiles = function() {
			my.scope.find('[data-input-type="img"]').each(function() {
				var opciones = {};

				var path = $(this).data('path');
				if (path) {
					opciones.initialPreview = [
						'<img src="' + path + '" class="file-preview-image" >'
					];
				};

				var value = $(this).data('value');
				if (value) {
					opciones.initialCaption = value;
				};

				$(this).fileinput(opciones);
			});

			my.scope.find('[data-input-type="pdf"]').each(function() {
				var opciones = {
					showPreview: false,
				};

				var value = $(this).data('value');
				if (value) {
					opciones.initialCaption = value;
				};

				$(this).fileinput(opciones);
			});


			my.scope.find('input[type="file"]').each(function() {
				$(this).on('change', function() {
					var name = $(this).attr('name');
					var mirror = my.scope.find('input[data-mirror="' + name + '"]')

					if (mirror.length > 0) {
						mirror.val($(this).val());
						self.validator.revalidateField(mirror);
					};
				});
			});
		}

		self.initCkeditor = function() {
			my.scope.find('.ckeditor').each(function() {
				var editor = $(this).ckeditor({
					language: 'es',
					uiColor: '#FFFFFF',
					toolbarGroups: [{
							name: 'clipboard',
							groups: ['clipboard', 'undo']
						}, {
							name: 'links'
						}, {
							name: 'insert'
						}, {
							name: 'others'
						},
						'/', {
							name: 'basicstyles',
							groups: ['basicstyles', 'cleanup']
						}, {
							name: 'paragraph',
							groups: ['list', 'indent', 'blocks', 'align']
						}, {
							name: 'styles'
						}, {
							name: 'colors'
						}, {
							name: 'about'
						}
					],
					filebrowserBrowseUrl: 'ckfinder/ckfinder.html',
					filebrowserImageBrowseUrl: 'ckfinder/ckfinder.html?type=Images',
					filebrowserFlashBrowseUrl: 'ckfinder/ckfinder.html?type=Flash',
					filebrowserUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
					filebrowserImageUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
					filebrowserFlashUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
					on: {
						instanceReady: function() {
							var eventData = {};
							eventData.id = my.id;
							$('body').trigger('formulario:redimensionado', [eventData]);
						},
					}
				});
			});
		}

		self.enviarFormulario = function($form) {
			var ruta = $form.data('ruta');
			var form = $form[0];
			var formData = new FormData(form);

			$form.find('input[type="file"]').each(function() {
				var name = $(this).attr('name');
				var files = $(this)[0].files;
				formData.append(name, files);
			});

			var respuesta = ajax.ajaxPost(ruta, formData);

			if (respuesta.success) {
				my.scope.find('.ckeditor').each(function() {
					var name = $(this).attr('name');
					var editor = CKEDITOR.instances[name];
					editor.removeAllListeners();
					CKEDITOR.remove(editor);
				});

				var eventData = {};
				eventData.id = my.id;
				eventData.keyId = respuesta.datos.keyId;
				eventData.valueId = respuesta.datos.id;
				eventData.cadena = respuesta.datos.cadena;

				$('body').trigger('formulario:success', [eventData]);

			} else {
				my.scope.html(respuesta);
			};
		}
	}

	return formulario;
});