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

    if($automataElegido == "AFD") {
        $automata1 = new AFND();
        $automata1->crearAFND($identificadores1, $alfabeto, $estadoInicial1, $estadosFinales1);
        $automata1->llenarRelacionDeTransicion($fTrans1);
    }

@endphp
<h1 style="margin-bottom: 2%;" class="text-center display-2">Resultado Autómatas</h1>
<div class="procesoUno" style="display: none;">
    <h2 style="margin-bottom: 2%;" class="text-center display-4">Inicio</h2>
    <div class="row">
        <div class="col-sm">
            <h2 class="text-center">{{$automataElegido}}</h1>
            <img src="{{$automata1->dibujarAFND()}}" alt="Automata {{$automataElegido}}">
            <h2 class="text-center" style="margin-top: 3%;">Expresión Regular {{$automataElegido}}</h1>
            <h5>@php print_r($automata1->convertirAFDaER()); @endphp</h5>
        </div>
    </div>
</div>

<nav aria-label="..." style="display: inline-block; text-shadow: none; margin-top: 3%;">
    <ul class="pagination pagination-lg">
        <li class="page-item"><button type="button" class="page-link" id="navUno" onclick="">Resultado Inicio</button></li>
    </ul>
</nav>

@endsection