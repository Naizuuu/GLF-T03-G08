@extends('layouts.plantilla')

@section('title', 'Trabajo 3 - Autómatas de Pila y Expresiones Regulares')

@section('content')

<form id="form" method="GET" autocomplete="off">
    {{-- <h1 class="text-center display-1">¿Qué tipo de autómatas ingresará?</h1> --}}
    {{-- <div class="container">
        <a style="text-decoration: none;" id="gotomenu" href="{{route('automata_afd')}}">
            <button style="margin-top: 2%;" type="button" class="btn btn-danger btn-lg btn-block">AFD</button>
        </a>
        <a style="text-decoration: none;" id="gotomenu" href="{{route('automata_ap')}}">
            <button style="margin-top: 2%;" type="button" class="btn btn-danger btn-lg btn-block">2 AP</button>
        </a>
    </div> --}}

    <div id="stepOne">@include('layouts.partials.stepOne')</div>
    <div id="stepTwo">@include('layouts.partials.stepTwo')</div>

    <button type="submit" style="margin-bottom: 3%;" class="btn btn-lg btn-block custom-btn" onclick="">Confirmar</button>
    @stack('menu')
</form>

@endsection