$.fn.accordion = function() {

	function close_tabs (items) {
		$(items).removeClass('open');
		$('.accordion__item__content',$(items)).css('max-height',0);
	}

	function open_tab (TAB) {
		TAB.addClass('open');
		var h = $('.accordion__item__content__container', $(TAB)).height();
		$('.accordion__item__content',$(TAB)).css('max-height',h);
	}

	$(this).each(function(z,accordion) {

		var a = $(accordion);
		var items = $('.accordion__item', a);


		$(items).each(function(k,item) {
		
			var title = $('.accordion__item__title', item);

			if($(item).hasClass('open')) {
				var h = $('.accordion__item__content__container', $(item)).height();
				$('.accordion__item__content',$(item)).css('max-height',h);
			}

			if( $(title).hasClass('has-items') ) {
				title.on('click', function() {

					var TAB = $(this).parent();

					if( TAB.hasClass('open') ) {

						TAB.removeClass('open');
						$('.accordion__item__content',$(TAB)).css('max-height',0);
					} else {

						close_tabs (items);
	
						open_tab (TAB);
					}
				});
			}

			$('[data-open-tab]').click(function() {


				var name_tab = $(this).attr('data-open-tab');
				var TAB = $('[data-index="'+name_tab+'"]',a);

				if(TAB.length) {
					close_tabs (items);

					open_tab (TAB);
					
				}

			});
		});

 
	});

	return this;

};