var App = {
	config:{
		url: '',
		layout: '',
		section: ''
	},
	Init: function(config){

		App.config = $.extend(App.config, config || {});
		if(window.name != "") App.config.canvas = true;
		$(document).ready(function(){

			Main();

		});

	},


}

var Main = function() {

	$('.screen').height( $(window).height() );

	App.config.url = $('#data').attr('data-url');
	App.config.layout = $('#data').attr('data-layout');
	App.config.section = $('#data').attr('data-section');
	App.config.theme = $('#data').attr('data-theme');
	
	Pager();


	
	// Si se quere ejecutar una funcion por sección crearla en pre/section y descomentar:
	// Ejemplo de fn: pageFN['principal'] = function() { /*codigo*/ }
	// if (typeof pageFN[App.config.section] == "function") {
	// 	pageFN[App.config.section]();
	// }



	// Si queremos usar el componente accordion
	// $('.accordion').accordion();

	// Si queremos usar el componente ajaxForm se explica en pre/proyect/helpers.js
	$('.ajaxForm').ajaxForm();

	$('.arroba').html('@');
}