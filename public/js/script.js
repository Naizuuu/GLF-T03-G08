var stepZero = document.getElementById('stepZero');
var stepOne = document.getElementById('stepOne');
var stepTwo = document.getElementById('stepTwo');

/* $(stepOne).ready(function(){
    if ($("#stepOne").html().length > 0) {
      $('#stepZero').hide();
    }                                           
}); */

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
    $("#navCuatro").click(function() {
        $(".procesoCuatro").toggleClass('oculto');
    });
    $("#navCinco").click(function() {
        $(".procesoCinco").toggleClass('oculto');
    });
    $("#navSeis").click(function() {
        $(".procesoSeis").toggleClass('oculto');
    });
});