var stepZero = document.getElementById('stepZero');
var stepOne = document.getElementById('stepOne');
var stepTwo = document.getElementById('stepTwo');


$(stepTwo).ready(function(){
    if ($("#stepTwo").html().length > 0) {
      $('#stepOne').hide();
    }                                           
});

$(document).ready(function(){
    $("#navUno").click(function() {
        $(".procesoUno").toggleClass('oculto');
    });
    $("#navDos").click(function() {
        $(".procesoDos").toggleClass('oculto');
    });
    $("#navTres").click(function() {
        $(".procesoTres").toggleClass('oculto');
    });
});