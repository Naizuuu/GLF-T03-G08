@php
    $alfabetoArray = explode(",", $alfabeto);
@endphp


<div class="row">
    <div class="col-sm"> 
        <div class="form-group">  {{-- ESTADOS --}}
            <label for="{{$cantEstado}}">Cantidad de Estados</label>
        <input type="number" class="form-control" name="{{$cantEstado}}" title="Debe ingresar la cantidad de estados del autómata." placeholder="Ingrese la cantidad de estados." min="1" autocomplete="off" value="<?php echo htmlspecialchars($_GET[$cantEstado] ?? '', ENT_QUOTES); ?>" required>
        </div>
    </div>
</div>

@php $cant_estados = 0; $identificadores = ''; $eFinales = ''; @endphp

@isset($_GET[$cantEstado])
    @php
        $cant_estados = (int)$_GET[$cantEstado];
        $auxMax = sizeof($alfabetoArray) * $cant_estados;
        $aux = 0;
    @endphp
@endisset

@for($i = 0; $i < $cant_estados; $i++)
    @if($i == 0) <label style="margin-bottom: 2%;">Seleccione las transiciones</label> @endif
    @foreach($alfabetoArray as $simbolo)
    <div class="row" style="margin-top: 0%;">
        <div class="col-sm">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="{{$iden . $i}}" disabled>
            </div>
        </div>
        <div class="col-sm">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="{{$simbolo}}" disabled>
            </div>
        </div>
        <div class="col-sm">
            <div class="input-group">
                <select class="custom-select" name="{{$cantEstado}}_t{{$i . $simbolo}}">
                    @for($j = 0; $j < $cant_estados; $j++)
                        <option value="{{$iden . $i . ',' . $simbolo . ',' . $iden . $j}}" <?php if(isset($_GET[$cantEstado. '_t' . $i . $simbolo]) && $_GET[$cantEstado. '_t' . $i . $simbolo] == ($iden . $i. ',' . $simbolo . ',' . $iden . $j)) { echo 'selected="selected"'; } ?>>{{$iden . $j}}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    @isset($_GET[$cantEstado. '_t' . $i . $simbolo])
        @php
            if($aux == $auxMax - 1) {
                $transicion = $transicion . $_GET[$cantEstado. '_t' . $i . $simbolo];
                $aux++;
            } else {
                $transicion = $transicion . $_GET[$cantEstado. '_t' . $i . $simbolo] . ';';
                $aux++;
            }
        @endphp
    @endisset

    @endforeach
@endfor

@isset($_GET[$cantEstado . '_t0' . $alfabetoArray[0]])
    <div class="row" style="margin-top: 2%;">
        <div class="col-sm">
            <label for="estadoInicial" style="margin-bottom: 2%;">Seleccione el estado inicial</label>
            <select class="custom-select" name="{{$cantEstado}}_eInicial">
                @for($j = 0; $j < $cant_estados; $j++)
                    <option value="{{$iden . $j}}" <?php if(isset($_GET[$cantEstado.'_eInicial']) && $_GET[$cantEstado.'_eInicial'] == ($iden . $j)) { echo 'selected="selected"'; } ?>>{{$iden . $j}}</option>
                @endfor
            </select>
        </div>
    </div>
    <div class="row" style="margin-top: 2%;">
        <div class="col-sm">
            <div class="form-group">
                <label style="margin-bottom: 0%;">Seleccione el estado final</label>
                <input type="text" class="form-control" style="display: none;" disabled>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 0%;">
        @for($i = 0; $i < $cant_estados; $i++)
        <div class="col-sm">
            <div class="pretty p-default p-round">
            <input type="checkbox" name="{{$cantEstado}}_eFinal_{{$i}}" value="{{$iden . $i}}" <?php if(isset($_GET[$cantEstado . '_eFinal_' . $i]) && $_GET[$cantEstado . '_eFinal_' . $i] == ($iden . $i)) { echo 'checked="checked"'; } ?>>
                <div class="state p-primary-o">
                    <label>{{$iden . $i}}</label>
                </div>
            </div>
        </div>
        @endfor
    </div>

    @for($j = 0; $j < $cant_estados; $j++)
        @php
            if($j == $cant_estados - 1) {
                $identificadores = $identificadores . $iden . $j;
                if(isset($_GET[$cantEstado . '_eFinal_' . $j])) {
                    if(empty($eFinales)) {
                        $eFinales = $eFinales . $_GET[$cantEstado . '_eFinal_' . $j];
                    } else {
                        $eFinales = $eFinales . "," . $_GET[$cantEstado . '_eFinal_' . $j];
                    }
                }
            } else {
                $identificadores = $identificadores . $iden . $j . ',';
                if(isset($_GET[$cantEstado . '_eFinal_' . $j])) {
                    if(empty($eFinales)) {
                        $eFinales = $eFinales . $_GET[$cantEstado . '_eFinal_' . $j];
                    } else {
                        $eFinales = $eFinales . "," . $_GET[$cantEstado . '_eFinal_' . $j];
                    }
                }
            }
        @endphp
    @endfor
    
    <input type="text" style="display: none;" class="form-control" name="{{$cantEstado}}_estadosfinales" value="{{$eFinales}}">
    <input type="text" style="display: none;" class="form-control" name="{{$cantEstado}}_transicion" value="{{$transicion}}">
    <input type="text" style="display: none;" class="form-control" name="{{$cantEstado}}_identificadores" value="{{$identificadores}}">
    
    @isset($_GET[$cantEstado . '_estadosfinales'])
        @empty($_GET[$cantEstado . '_verificador'])
            <h4><br>Por favor, presione confirmar nuevamente.</h4>
            <input type="text" style="display: none;" class="form-control" name="{{$cantEstado}}_verificador" value="1">
        @endempty
    @endisset

@endisset