var Pager = function(){
	var routes = jQuery.parseJSON( $('#routes').html() );

	var ELEMENTS_IN = $('[data-animate-in]');
	$.each(ELEMENTS_IN, function(i,el) {
		var data = fnanimate ('data-animate-in', el);
	});

	var page = new Page(App.config.url);

	for (var i = 0; i < routes.length; i++) {
		page.set(routes[i],set);
	};

	function set (data) {
	
    	var path = data.full_href;

		var MAIN = $('#main').addClass('old-main');

		var nano = MAIN.loading();

		var ELEMENTS_OUT = $('[data-animate-out]');
		var ELEMENTS_IN = $('[data-animate-in]');

		var time_page_out = 0;
		
		$.each(ELEMENTS_OUT, function(i,el) {
			var data = fnanimate ('data-animate-out', el);

			if(data['time_total'] > time_page_out) time_page_out = data['time_total'];

		});

		$.get( path )
		  .done(function( html ) {
		  	setTimeout(function() {
				nano.end();

		  		var NEW_MAIN = $(html);

		    	MAIN.after(NEW_MAIN);
		    	MAIN.remove();

		    	Main();
		  		
		  	}, time_page_out + 300);
		});

		return false;
	}
}