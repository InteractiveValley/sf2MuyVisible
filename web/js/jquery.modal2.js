/* 
 modal.js ventana modal basica
 Copyright � Jesus Li�an www.ribosomatic.com
 
 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 
 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 
 7 de abril 2013
 Modificaciones realizadas por: InteractiveValley Systmes <richpolis@gmail.com> 
 
 */

(function ($) {

    $.fn.dialogModalRS = function (urlPage, callback) {
        var bgdiv = $('<div>').attr({'id': 'bgtransparent'});
        bgdiv.addClass('bgtransparent');
        $('body').append(bgdiv);

        var wscr = $(window).width();
        var hscr = $(window).height();

        $('#bgtransparent').animate({width: wscr, height: hscr}, 'fast', null, function () {
            $(this).animate({height: hscr}, 'slow', null, function () {
                var moddiv = $('<div>').attr({'id': 'dialog-modal'});
                moddiv.addClass('bgmodal2');
                $('body').append(moddiv);

                $('#dialog-modal').load(urlPage, function () {
                    //alguna accion que realice na la pagina 
                    if (callback)
                        callback();
                });
                $(window).resize();
            });
        });

    }
})(jQuery);

$(window).resize(function () {
    // dimensiones de la ventana
    var ancho = 950;
    var alto = 690;

    var wscr = $(window).width();
    var hscr = $(window).height();

    // estableciendo dimensiones de background
    $('#bgtransparent').css("width", wscr);
    $('#bgtransparent').css("height", hscr);

    // definiendo tama�o del contenedor
    $('#dialog-modal').css("width", ancho + 'px');
    $('#dialog-modal').css("height", alto + 'px');



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
        closeMensajeModal();
    }
});

function closeDialogModalRS() {
    $('#dialog-modal').fadeOut('slow', function () {
        $(this).remove();
        $('#bgtransparent').slideUp('slow', function () {
            $(this).remove();
        });
    });
}



