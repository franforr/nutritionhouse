var Requiere  = function(script, done){
  $.getScript( App.config.layout+'js/'+script )
    .done(function( script, textStatus ) {
      done();
    })
    .fail(function( jqxhr, settings, exception ) {
      console.error( "Ajax error Requiere  Script: " + script );
  });
}

var media = function(bk,fn) {
  if( breakpoint(bk) ) {
    fn();
  }
  $(window).on('resize', function() {

    if( breakpoint(bk) ) {
      fn();
    }
  });
}

var breakpoint = function(bk) {
  var ww = $( window ).width();

  var r = [];
  if( ww <= 544 ) { r.push('xs'); r.push('<sm'); r.push('<md'); r.push('<lg'); r.push('xl'); }
  if( ww > 544 && ww <=768 ) { r.push('sm'); r.push('>xs'); r.push('<md'); r.push('<lg'); r.push('<xl');  }
  if( ww > 768 && ww <= 992 ) { r.push('md'); r.push('>sm'); r.push('>xs'); r.push('<lg'); r.push('<xl'); }
  if( ww > 992 && ww <= 1800 ) { r.push('lg'); r.push('>md'); r.push('>sm'); r.push('>xs'); r.push('<xl'); }
  if( ww > 1800 ) { r.push('xl'); r.push('>lg'); r.push('>md'); r.push('>sm'); r.push('>xs'); }

  return Boolean( $.inArray( bk, r ) >= 0 );
}

// Depende de pre/components/_loading.scss
$.fn.loading = function(btn) {

  b = new Nanobar();
  NanoVal = 0;
  b.go(NanoVal);

  $(btn).addClass('disabled')
  $(this).addClass('loading');

    NanoInterval = setInterval(function(){
        if( NanoVal - 80 ) {
      NanoVal = NanoVal + Math.floor(Math.random() * (10 - 2));
      b.go(NanoVal);
        }
    }, 300);

    this.end = function() {
    b.go(100);
        this.removeClass('loading');
        $(btn).removeClass('disabled');
        clearInterval(NanoInterval);
    }


    return this;
}


$.fn.errorsForm = function(errors) {
  var _this = this;
  $(errors).each(function(z,error) {

    var input = $('[name="'+error+'"]', _this);
    var box = $('[data-show-error="'+error+'"]', _this);

    if(input.length) {

      input.addClass('form-control-danger');
      input.parent().addClass('has-danger');
      input.one('focus change',function() {
        $(this).removeClass('error');
        $(this).parent().removeClass('has-danger');
      });
    } 
    if (box.length) {
      box.addClass('box-has-danger');
      setTimeout(function() {
        box.one('mouseover',function() {
          $(this).removeClass('box-has-danger');
        });
      }, 300);
    }
  });
  
}

var ajaxFormCallback = [];
$.fn.ajaxForm = function() {
  return this.each(function(z,form) {

    var form = $(form);

    $('button',form).click(function() {
      $(this).addClass('this_is_the_btn');
    });

    $(form).submit(function(e) {
      var btn = $('.this_is_the_btn', form).removeClass('this_is_the_btn');

      var loading = form.loading(btn);

      e.preventDefault();
      $.ajax({
        type:'POST',
        url:form.attr('action'),
        data:form.serialize(),
        success:function(data){ 

          loading.end();
          if(data.error==0){


            var cb = data.callback;
            if( cb )
              ajaxFormCallback[cb](data);

          } else {
            form.errorsForm(data.inputErrors);
            loading.end();
          };
        },
        error:function(){
          loading.end();
          console.dir('Error JSON');
        },
        dataType:'json'
      });

      return false; 
    });

  });
}

function fnanimate(attr, el) {
  var params = $(el).attr( attr );
    params = params.split(',');

  var data = [];

  data['efect_class'] = params[0];
  data['time_class'] = (params[2]=='slow' || params[2]=='fast') ? params[2] : ''; 
  data['time_start'] = parseFloat(params[1]);

  if (params[2] == 'fast') {
    data['time_efect'] = 300;
  } else if (params[2] == 'slow') {
    data['time_efect'] = 1000;
  } else {
    data['time_efect'] = 600;
  } 
  
  data['time_total'] = data['time_start'] + data['time_efect'];

  setTimeout( function() {
    $(el).addClass('animated');
    $(el).addClass(data['efect_class']);
    $(el).addClass(data['time_class']);
  },data['time_start']);

  return data;
}


// DESC: Ajusta el alto como una imagen height:100%
// USO: $(selector).div_responsive(); al selector ponerle data-original-size="x,x"
$.fn.div_responsive = function() {
  $(this).each(function(i,el) {
    var _this = el;
    var original_size = $(_this).attr('data-original-size').split(',');
    var original_w = original_size[0];
    var original_h = original_size[1];

    $( window ).on('load resize',function() {


      var w = $(_this).width();
      var h = ( w * original_h ) / original_w;

      h = Math.round(h);
      $(_this).height(h);

    });
  });

}

// $.fn.picture_in_modal = function(_file) {

//   if( $(this).hasClass('picture_in_modal_reander') ) return;

//     $(this).click(function() {
      
//       var src = $(this).attr('data-modal-in-picture') ? $(this).attr('data-modal-in-picture') : _file;

//         $('.bootstrap_modal').remove();

//         var c = $('<div class="bootstrap_modal"></div>').html('<div class="modal fade" id="bootstrap_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\
//                   <div class="modal-dialog modal-lg" role="document">\
//                     <div class="modal-content text-center">\
//                       </div>\
//                     </div>\
//                   </div>\
//                 </div>');

        
//         $('body').prepend(c);

//         var IMG = $('<img src="'+src+'" style="max-width:100%" />');

//         $('.modal-content', c).append(IMG);

//         $('#bootstrap_modal', c).modal('show').on('hidden.bs.modal', function (e) {
//            c.remove();
//         });
//     }).addClass('picture_in_modal_reander');
// }