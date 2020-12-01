@php
    $alfabeto = '';
    $automataElegido = 'AFD';
@endphp

@isset($_GET['alfabetoAutomata'])
    @php
        $alfabeto = $_GET['alfabetoAutomata'];
        $automataElegido = $_GET['automataElegido'];
        $transicionA = '';
        $transicionB = '';
    @endphp

    <h1 class="text-center display-1">Autómata</h1>
    <div class="container">
        <div class="row">
            <div class="col-sm"> {{-- PRIMERA COLUMNA --}}
                <h1 class="text-center display-3">{{$automataElegido}}</h1>
                @include('layouts.partials.' . $automataElegido, ['alfabeto' => $alfabeto, 'transicion' => $transicionA, 'iden' => 'p', 'cantEstado' => 'cantidadEstados1'])
            </div>
            @if($automataElegido == 'AP')
                <div class="col-sm"> {{-- SEGUNDA COLUMNA --}}
                    <h1 class="text-center display-3">{{$automataElegido}}</h1>
                    @include('layouts.partials.' . $automataElegido, ['alfabeto' => $alfabeto, 'transicion' => $transicionB, 'iden' => 'q', 'cantEstado' => 'cantidadEstados2'])
                </div>
            @endif
        </div>
        @prepend('menu')
        @if(isset($_GET['cantidadEstados1_identificadores']))
            {{-- @if(isset($_GET['cantidadEstados1_identificadores']) && isset($_GET['cantidadEstados2_identificadores'])) --}}
                @php
                    $alfabeto = $_GET['alfabetoAutomata'];
                    /* automata 1 */
                    $identificadores1 = $_GET['cantidadEstados1_identificadores'];
                    $estadoInicial1 = $_GET['cantidadEstados1_eInicial'];
                    $estadosFinales1 = $_GET['cantidadEstados1_estadosfinales'];
                    $fTrans1 = $_GET['cantidadEstados1_transicion'];
                    if($automataElegido == 'AP' && isset($_GET['cantidadEstados2_identificadores'])) {
                        /* automata 2 */
                        $identificadores2 = $_GET['cantidadEstados2_identificadores'];
                        $estadoInicial2 = $_GET['cantidadEstados2_eInicial'];
                        $estadosFinales2 = $_GET['cantidadEstados2_estadosfinales'];
                        $fTrans2 = $_GET['cantidadEstados2_transicion'];
                    }                 
                @endphp
                @if($automataElegido == 'AFD')
                <a style="text-decoration: none;" id="gotomenu" href="{{route('automata_afd') . '?a=' . base64_encode($alfabeto) . '&i1=' . base64_encode($identificadores1) . '&ei1=' . base64_encode($estadoInicial1) . '&ef1=' . base64_encode($estadosFinales1) . '&f1=' . base64_encode($fTrans1) . '&ae=' . base64_encode($automataElegido)}}">
                    <button style="margin-bottom: 2%;" type="button" class="btn btn-danger btn-lg btn-block">Ir al menu</button>
                </a>
                @endif
                @if($automataElegido == 'AP')
                <a style="text-decoration: none;" id="gotomenu" href="{{route('automata_ap') . '?a=' . base64_encode($alfabeto) . '&i1=' . base64_encode($identificadores1) . '&i2=' . base64_encode($identificadores2) . '&ei1=' . base64_encode($estadoInicial1) . '&ei2=' . base64_encode($estadoInicial2) . '&ef1=' . base64_encode($estadosFinales1) . '&ef2=' . base64_encode($estadosFinales2) . '&f1=' . base64_encode($fTrans1) . '&f2=' . base64_encode($fTrans2) . '&ae=' . base64_encode($automataElegido)}}">
                    <button style="margin-bottom: 2%;" type="button" class="btn btn-secondary btn-lg btn-block">Ir al menu</button>
                </a>
                @endif
        @endif
        @endprepend
    </div>
@endisset