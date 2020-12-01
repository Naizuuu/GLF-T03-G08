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
        $automata1 = new AFD();
        $automata1->crearAFD($identificadores1, $alfabeto, $estadoInicial1, $estadosFinales1);
        $automata1->llenarFuncionDeTransicion($fTrans1);
    }

    $dibujoUno = 'dibujar' . $automataElegido;

@endphp
<h1 style="margin-bottom: 2%;" class="text-center display-2">Resultado Autómatas</h1>
<div class="procesoUno" style="display: none;">
    <h2 style="margin-bottom: 2%;" class="text-center display-4">Inicio</h2>
    <div class="row">
        <div class="col-sm">
            <h2 class="text-center">{{$automataElegido}}</h1>
            <img src="{{$automata1->$dibujoUno()}}" alt="Automata {{$automataElegido}}">
        </div>
    </div>
</div>

<nav aria-label="..." style="display: inline-block; text-shadow: none; margin-top: 3%;">
    <ul class="pagination pagination-lg">
        <li class="page-item"><button type="button" class="page-link" id="navUno" onclick="">Resultado Inicio</button></li>
        {{-- <li class="page-item"><button type="button" class="page-link" id="navDos" onclick="">Simplificación</button></li>
        <li class="page-item"><button type="button" class="page-link" id="navTres" onclick="">Complemento</button></li>
        <li class="page-item"><button type="button" class="page-link" id="navCuatro" onclick="">Unión</button></li>
        <li class="page-item"><button type="button" class="page-link" id="navCinco" onclick="">Concatenación</button></li> --}}
    </ul>
</nav>

@endsection