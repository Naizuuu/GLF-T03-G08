<h1 style="margin-bottom: 5%;" class="text-center display-1">¿Qué tipo de autómatas ingresará?</h1>
<div class="container">
    <div class="row">
        <div class="col-sm"> 
            <label for="automataElegido">Tipo de Autómata</label>
            <div class="input-group" style="margin-top: 0%;">
                <select class="custom-select" name="automataElegido">
                    <option value="AFD" <?php if(isset($_GET['automataElegido']) && $_GET['automataElegido'] == 'AFD') { echo 'selected="selected"'; } ?>>AFD</option>
                    <option value="AP" <?php if(isset($_GET['automataElegido']) && $_GET['automataElegido'] == 'AP') { echo 'selected="selected"'; } ?>>AP</option>
                </select>
            </div>
        </div>
    </div>

    @isset($_GET['automataElegido'])
        @php
            $automataElegido = $_GET['automataElegido'];
        @endphp
    @endisset
    <div class="row">
        <div class="col-sm"> 
            <div class="form-group" style="margin-top: 2%;">
                <label for="alfabetoAutomata">Alfabeto</label>
                <input type="text" class="form-control" name="alfabetoAutomata" id="alfabetoAutomata" title="Debe ingresar el alfabeto como el siguiente ejemplo: a,b,c" pattern="^([a-zA-Z0-9]){1}(,[a-zA-Z0-9]{1})*$" placeholder="Ingrese el alfabeto para los autómatas separados por comas. (Ej: a,b,c)" autocomplete="off" value="<?php echo htmlspecialchars($_GET['alfabetoAutomata'] ?? '', ENT_QUOTES); ?>" required>
            </div>
        </div>
    </div>
    @isset($_GET['alfabetoAutomata'])
        @php
            $alfabeto = $_GET['alfabetoAutomata'];
        @endphp
    @endisset
</div>
    