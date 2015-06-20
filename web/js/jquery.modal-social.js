/*
Creacion de una ventana modal y transparencia al fondo.
 */

(function ($) {

    $.fn.modalSocial = function () {
        var bgdiv = $('<div>').attr({'id': 'bgtransparent'});
        bgdiv.addClass('bgtransparent');
        $('body').append(bgdiv);

        var wscr = $(window).width();
        var hscr = $(window).height();

        $('#bgtransparent').animate({width: wscr, height: hscr}, '500', null, function () {
            $('#dialog-modal').fadeIn('fast');
                
            $(window).resize();
        });

    }
})(jQuery);

$(window).resize(function () {
    // dimensiones de la ventana
    var ancho = '80%';
    var alto = 'auto';

    var wscr = $(window).width();
    var hscr = $(window).height();

    // estableciendo dimensiones de background
    $('#bgtransparent').css("width", wscr);
    $('#bgtransparent').css("height", hscr);

    // definiendo tama�o del contenedor
    $('#dialog-modal').css("width", ancho);
    $('#dialog-modal').css("height", alto);



    // obtiendo tama�o de contenedor
    var wcnt = $('#dialog-modal').width();
    var hcnt = $('#dialog-modal').height();

    // obtener posicion central
    var mleft = (wscr - wcnt) / 2;
    var mtop = (hscr - hcnt) / 2;

    // estableciendo posicion
    $('#dialog-modal').css("left", mleft + 'px');
    $('#dialog-modal').css("top", mtop + 'px');

});
$(window).keyup(function (event) {
    if (event.keyCode == 27) {
        closeModalSocial();
    }
});

function closeModalSocial() {
    $('#dialog-modal').fadeOut('slow', function () {
        $(this).fadeOut('fast');
        $('#bgtransparent').slideUp('slow', function () {
            $(this).remove();
        });
    });
}



