//Verificar compatibilidade do browser com o sistema
function verificaNavegador() {
    if (!(Modernizr.borderradius && Modernizr.boxshadow && Modernizr.fontface && Modernizr.opacity && Modernizr.cssanimations && Modernizr.cssgradients)) {
        if (confirm('Navegador não compativel com o sistema. Recomenda-se instalar a mais nova versão do Google Chrome. \n\Deseja baixa-lo?')) {
            window.open("https://www.google.com/intl/pt-BR/chrome/browser/");
        }
    }
}

$(document).ready(function() {
    $('div#cs-alert-success').hide();
    $('div#cs-alert-danger').hide();
    verificaNavegador();
});