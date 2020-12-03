@extends('layouts.plantilla')

@include('automatas/clase-automata')

@section('title', 'Trabajo 3 - Autómatas de Pila y Expresiones Regulares')

@section('content')

@php
    $alfabeto = base64_decode($_GET['a']);
    $identificadores1 = base64_decode($_GET['i1']);
    $estadoInicial1 = base64_decode($_GET['ei1']);
    $estadosFinales1 = base64_decode($_GET['ef1']);
    $fTrans1 = base64_decode($_GET['f1']);
    $automataElegido = base64_decode($_GET['ae']); 


    /* automata 2 */
    $identificadores2 = base64_decode($_GET['i2']);
    $estadoInicial2 = base64_decode($_GET['ei2']);
    $estadosFinales2 = base64_decode($_GET['ef2']);
    $fTrans2 = base64_decode($_GET['f2']);

    if($automataElegido == "AP" && $automataElegido == "AP") {
        $automata1 = new AP();
        $automata1->crearAP($identificadores1, $alfabeto, $estadoInicial1, $estadosFinales1);
        $automata1->llenarRelacionDeTransicion($fTrans1);
        $automata2 = new AP();
        $automata2->crearAP($identificadores2, $alfabeto, $estadoInicial2, $estadosFinales2);
        $automata2->llenarRelacionDeTransicion($fTrans2);
    }

@endphp
<h1 style="margin-bottom: 2%;" class="text-center display-2">Resultado Autómatas</h1>
<div class="procesoUno" style="display: none;">
    <h2 style="margin-bottom: 2%;" class="text-center display-4">Inicio</h2>
    <div class="row">
        <div class="col-sm">
            <h2 class="text-center">{{$automataElegido}}</h1>
            <img src="{{$automata1->dibujarAP()}}" alt="Automata {{$automataElegido}}">
            @php echo "<br><br>"; var_dump($automata1->dibujarAP()); @endphp
        </div>
        <div class="col-sm">
            <h2 class="text-center">{{$automataElegido}}</h1>
            <img src="{{$automata2->dibujarAP()}}" alt="Automata {{$automataElegido}}">
            @php echo "<br><br>"; var_dump($automata2->dibujarAP()); @endphp
        </div>
    </div>
</div>

<nav aria-label="..." style="display: inline-block; text-shadow: none; margin-top: 3%;">
    <ul class="pagination pagination-lg">
        <li class="page-item"><button type="button" class="page-link" id="navUno" onclick="">Resultado Inicio</button></li>
        <li class="page-item"><button type="button" class="page-link" id="navDos" onclick="">Simplificación</button></li>
        <li class="page-item"><button type="button" class="page-link" id="navTres" onclick="">Complemento</button></li>
        <li class="page-item"><button type="button" class="page-link" id="navCuatro" onclick="">Unión</button></li>
        <li class="page-item"><button type="button" class="page-link" id="navCinco" onclick="">Concatenación</button></li>
        <li class="page-item"><button type="button" class="page-link" id="navSeis" onclick="">Intersección</button></li>
    </ul>
</nav>

@endsection