<?php
class AFD {
    public $conjuntoDeIdentificadores = [];
    public $alfabetoDeEntrada = [];
    public $estadoInicial = "";
    public $estadosFinales = [];
    public $funcionDeTransicion = [];

    public function __construct() {
        // Crea el objeto sin parámetros.
        
    }

    public function crearAFD($identificadores, $alfabeto, $estadoI, $estadosF) {
        $this->conjuntoDeIdentificadores = explode(",", $identificadores);
        $this->alfabetoDeEntrada = explode(",", $alfabeto);
        $this->estadoInicial = $estadoI;
        $this->estadosFinales = explode(",", $estadosF);
    }
    public function llenarFuncionDeTransicion($funcion) {
        $funcionPuntoYComa = explode(";", $funcion);
        for ($i = 0;$i < count($funcionPuntoYComa);$i++) {
            $transiciones = explode(",", $funcionPuntoYComa[$i]);
            $this->funcionDeTransicion[$transiciones[0]][$transiciones[1]][] = $transiciones[2];
        }
    }
    private function buscarMasTransiciones($estadoInicial, $estadoDeLlegada) {
        foreach ($this->funcionDeTransicion[$estadoInicial] as $a => $transicion) {
            if ($transicion[0] == $estadoDeLlegada) {
                $caracteres[] = $a;
            }
        }
        return implode(",", $caracteres);
    }
    public function dibujarAFD() {
        $link = "https://image-charts.com/chart?cht=gv&chl=digraph {";
        foreach ($this->estadosFinales as $estadoFinal) {
            if ($estadoFinal != "") {
                $link = $link . '"' . $estadoFinal . '"' . "[shape=doublecircle];";
            }
        }
        $link = $link . '"ei"[shape=point]; "ei" -> ' . '"' . $this->estadoInicial . '"' . ';';

        foreach ($this->funcionDeTransicion as $estado => $transiciones) {
            $listos = [];
            foreach ($transiciones as $transicion) {
                if (!in_array($transicion[0], $listos)) {
                    $link = $link . '"' . $estado . '"' . " -> " . '"' . $transicion[0] . '"' . '[label="' . $this->buscarMasTransiciones($estado, $transicion[0]) . '"];';
                    $listos[] = $transicion[0];
                }
            }
        }
        $link = $link . "}";
        return $link;
    }
}

class AP extends AFD {
    public $relacionDeTransicion = [];
	public $separacionArray = [];
    public $pila_aux = array("Z");

	/* q0,A/@/1,q0;q0,B/1/@,q1;q1,B/1/@,q1;q1,@/Z/@,q2 */

    public function __construct() {
        // Crea el objeto sin parámetros.
        
    }
    public function crearAP($identificadores, $alfabeto, $estadoI, $estadosF) {
        $this->conjuntoDeIdentificadores = explode(",", $identificadores);
        $this->alfabetoDeEntrada = explode(",", $alfabeto);
        $this->estadoInicial = $estadoI;
        $this->estadosFinales = explode(",", $estadosF);
    }
    public function llenarRelacionDeTransicion($funcion) {/* READ-POP-PUSH    @=VACIO */
        $funcionPuntoYComa = explode(";", $funcion);
        for ($i = 0; $i < count($funcionPuntoYComa); $i++) {
            $transiciones = explode(",", $funcionPuntoYComa[$i]);
            $separacionTransicion = explode("/", $transiciones[1]);
			/* $this->relacionDeTransicion[$transiciones[0]][$separacionTransicion[0]][] = $transiciones[2]; */
			$this->relacionDeTransicion[$transiciones[0]][$transiciones[1]][] = $transiciones[2];
			$this->separacionArray[$i] =  $separacionTransicion;
			/* echo "\n\n----------------------------------\n\n";
			var_dump($this->relacionDeTransicion); */
			/* var_dump($transiciones); */
        } 
    }

	private function buscarMasTransicionesAP($estado1, $estado2) {
        foreach ($this->relacionDeTransicion[$estado1] as $a => $transicion) {
            foreach ($transicion as $t) {
                if ($t == $estado2) {
                    $caracteres[] = $a;
                }
            }
        }
        return implode(",", $caracteres);
    }

    public function dibujarAP() {
        $link = "https://image-charts.com/chart?cht=gv&chl=digraph {";
        foreach ($this->estadosFinales as $estadoFinal) {
            if ($estadoFinal != "") {
                $link = $link . '"' . $estadoFinal . '"' . "[shape=doublecircle];";
            }
        }
        $link = $link . '"ei"[shape=point]; "ei" -> ' . '"' . $this->estadoInicial . '"' . ';';

        foreach ($this->relacionDeTransicion as $estado => $transiciones) {
            $listos = [];
            foreach ($transiciones as $transicion) {
                foreach ($transicion as $t) {
                    if (!in_array($t, $listos)) {
                        $link = $link . '"' . $estado . '"' . " -> " . '"' . $t . '"' . '[label="' . $this->buscarMasTransicionesAP($estado, $t) . '"];';
                        $listos[] = $t;
                    }
                }
            }
        }
        $link = $link . "}";
        return $link;
    }

    private function unirFuncionesDeTransicion($tipoAutomata1, $tipoAutomata2, $automata1, $automata2) {
        $arregloDeRelacionDeTransicion = [];
        if($tipoAutomata1 == "AP" && $tipoAutomata2 == "AP") {
            $arregloDeRelacionDeTransicion = array_merge
            ($automata1->relacionDeTransicion, $automata2->relacionDeTransicion);
        }
        return array_merge(["z0" => ["@/@/@" => [$automata1->estadoInicial, $automata2->estadoInicial]]], $arregloDeRelacionDeTransicion);
    }

    public function union($automata1, $automata2) {
        $automata3 = new AP;
        $automata3->conjuntoDeIdentificadores[] = "z0";
        $automata3->conjuntoDeIdentificadores = array_merge($automata1->conjuntoDeIdentificadores, $automata2->conjuntoDeIdentificadores);
        array_unshift($automata3->conjuntoDeIdentificadores, "z0");
        $automata3->alfabetoDeEntrada = $automata1->alfabetoDeEntrada;
        $automata3->estadoInicial = "z0";
        $automata3->estadosFinales = array_merge($automata1->estadosFinales, $automata2->estadosFinales);
        $automata3->relacionDeTransicion = $this->unirFuncionesDeTransicion(get_class($automata1) , get_class($automata2) , $automata1, $automata2);
        return $automata3;
    }

    private function concatenarFuncionesDeTransicion($tipoAutomata1, $tipoAutomata2, $automata1, $automata2) {
        $arregloDeRelacionDeTransicion = [];
        if ($tipoAutomata1 == "AP" && $tipoAutomata2 == "AP") {
            $arregloDeRelacionDeTransicion = array_merge($automata1->relacionDeTransicion, $automata2->relacionDeTransicion);
        }
        foreach ($automata1->estadosFinales as $estadoFinal) {
            $arregloDeRelacionDeTransicion[$estadoFinal]["@/Z/@"][] = $automata2->estadoInicial;
        }
        return array_merge(["z0" => ["@/@/Z" => [$automata1->estadoInicial]]], $arregloDeRelacionDeTransicion);
    }

    public function concatenacion($automata1, $automata2) {
        $automata3 = new AP;
        $automata3->conjuntoDeIdentificadores = array_merge($automata1->conjuntoDeIdentificadores, $automata2->conjuntoDeIdentificadores);
        $automata3->alfabetoDeEntrada = $automata1->alfabetoDeEntrada;
        /* $automata3->estadoInicial = $automata1->estadoInicial; */
            $automata3->estadoInicial = "z0";
        $automata3->estadosFinales = $automata2->estadosFinales;
        $automata3->relacionDeTransicion = $this->concatenarFuncionesDeTransicion(get_class($automata1) , get_class($automata2) , $automata1, $automata2);
        return $automata3;
    }
}

class AFND extends AFD {
    public $relacionDeTransicion = [];

    public function __construct() {
        // Crea el objeto sin parámetros.
        
    }
    public function crearAFND($identificadores, $alfabeto, $estadoI, $estadosF) {
        $this->conjuntoDeIdentificadores = explode(",", $identificadores);
        $this->alfabetoDeEntrada = explode(",", $alfabeto);
        $this->estadoInicial = $estadoI;
        $this->estadosFinales = explode(",", $estadosF);
    }
    public function llenarRelacionDeTransicion($funcion) {
        $funcionPuntoYComa = explode(";", $funcion);
        for ($i = 0;$i < count($funcionPuntoYComa);$i++) {
            $transiciones = explode(",", $funcionPuntoYComa[$i]);
            $this->relacionDeTransicion[$transiciones[0]][$transiciones[1]][] = $transiciones[2];
        }
    }
    private function transformarEtiquetasAExpresionesRegulares() {
        foreach($this->conjuntoDeIdentificadores as $identificador1) {
            foreach($this->conjuntoDeIdentificadores as $identificador2) {
                $expresionRegular = [];
                $unset = 0;
                foreach ($this->relacionDeTransicion[$identificador1] as $caracter => $transiciones) {
                    foreach($transiciones as $estado2) {
                        if ($estado2 == $identificador2) {
                            $expresionRegular[] = $caracter;
                            unset($this->relacionDeTransicion[$identificador1][$caracter]);
                            $unset = 1;
                        }
                    }
                }
                if($unset == 1) {
                    $this->relacionDeTransicion[$identificador1][implode('+', $expresionRegular)][] = $identificador2;
                }
            }
        }
    }
    private function estanConectados ($estado1, $estado2) {
        foreach($this->relacionDeTransicion[$estado1] as $estadoDeLlegada) {
            if($estadoDeLlegada[0] == $estado2) {
                return true;
            }
        }
    }
    private function agregarTransicionesVaciasEntreEstados() {
        foreach($this->conjuntoDeIdentificadores as $identificador1) {
            foreach($this->conjuntoDeIdentificadores as $identificador2) {
                if(!$this->estanConectados($identificador1, $identificador2)) {
                    $this->relacionDeTransicion[$identificador1]["$"][] = $identificador2;
                }
            }
        }
    }
    private function estadosPorEliminar () {
        $estados = [];
        foreach($this->relacionDeTransicion as $estado1 => $transiciones) {
            if ($estado1 != $this->estadoInicial && !in_array($estado1, $this->estadosFinales)) {
                $estados[] = $estado1;
            }
        }
        return $estados;
    }
    private function estados1 ($estadoDeLlegada) {
        $estados = [];
        foreach ($this->relacionDeTransicion as $estado1 => $transiciones) {
            foreach ($transiciones as $caracter => $estados2) {
                foreach ($estados2 as $estado2) {
                    if($estado2 == $estadoDeLlegada && $estado1 != $estado2) {
                        $estados[] = [$estado1,$caracter];
                    }
                }
            }
        }
        return $estados;
    }
    private function transicionAsiMismo ($estado) {
        foreach ($this->relacionDeTransicion[$estado] as $caracter => $estados) {
            foreach ($estados as $estadoDeLlegada) {
                if($estadoDeLlegada == $estado) {
                    return $caracter;
                }
            }
        }
    }
    private function simplificarER ($etiqueta) {
        foreach($etiqueta as $key => $valor) {
            if($valor == "$") {
                return [];
            }
            else {
                if($valor == "$*") {
                    $etiqueta[$key] = "@";
                    return $this->simplificarER($etiqueta);
                }
                else {
                    if($valor == "@") {
                        unset($etiqueta[$key]);
                        return $this->simplificarER($etiqueta);
                    }
                }
            }
        }
        return $etiqueta;
    }
    private function agregarParentesis($arreglo) {
        $cadena = "";
        foreach($arreglo as $a) {
            $cadena = $cadena."(".$a.")";
        }
        return $cadena;
    }
    private function desconectarTransicionesHaciaElEstado($estado) {
        foreach($this->relacionDeTransicion as $estado1 => $transiciones) {
            foreach($transiciones as $caracter => $estados) {
                foreach($estados as $estado2) {
                    if($estado2 == $estado) {
                        if(count($estados)>1) {
                            unset($this->relacionDeTransicion[$estado1][$caracter][array_search($estado, $estados)]);
                            $this->relacionDeTransicion[$estado1][$caracter] = array_values($this->relacionDeTransicion[$estado1][$caracter]);
                        }
                        else {
                            unset($this->relacionDeTransicion[$estado1][$caracter]);
                        }
                    }
                }
            }
        }
    }
    private function eliminarEstadosNoInicialesYNoFinales() {
        $estadosAEliminar = $this->estadosPorEliminar();
        while(!empty($estadosAEliminar)) {
            $estado = current($estadosAEliminar);
            foreach ($this->estados1($estado) as $primerEstado) {
                foreach ($this->relacionDeTransicion[$estado] as $caracter => $transiciones) {
                    foreach ($transiciones as $tercerEstado) {
                        if($tercerEstado != $estado) {
                            $etiqueta = [$primerEstado[1]];
                            if (strpos($this->transicionAsiMismo($estado),"+") !== false) {
                                $etiqueta[] = "(".$this->transicionAsiMismo($estado)."*)";
                            }    
                            else {
                                $etiqueta[] = $this->transicionAsiMismo($estado)."*";
                            }
                            $etiqueta[] = $caracter;
                            $etiqueta = $this->simplificarER($etiqueta);
                            if(!empty($etiqueta)) {
                                $llave = $this->agregarParentesis($etiqueta);
                                if($tercerEstado == $primerEstado[0]) {
                                    $this->relacionDeTransicion[$primerEstado[0]][$this->transicionAsiMismo($tercerEstado)."+".$llave][] = $tercerEstado;
                                    unset($this->relacionDeTransicion[$primerEstado[0]][$this->transicionAsiMismo($tercerEstado)]);
                                }
                                else {
                                    $this->relacionDeTransicion[$primerEstado[0]][$llave][] = $tercerEstado;
                                }
                            }
                        }
                    }
                }
            }
            unset($this->relacionDeTransicion[$estado]);
            unset($this->conjuntoDeIdentificadores[array_search($estado, $this->conjuntoDeIdentificadores)]);
            $this->desconectarTransicionesHaciaElEstado($estado);
            array_shift($estadosAEliminar);
        }
    }
    private function unirTransiciones() {
        $union = "";
        foreach($this->relacionDeTransicion[$this->estadoInicial] as $etiqueta => $transiciones) {
            foreach($transiciones as $estado2) {
                if($estado2 == $this->estadosFinales[0]) {
                    if ($union == "") {
                        $union = $etiqueta;
                        unset($this->relacionDeTransicion[$this->estadoInicial][$etiqueta]);
                    }
                    else {
                        $union = $union."+".$etiqueta;
                        unset($this->relacionDeTransicion[$this->estadoInicial][$etiqueta]);
                    }
                }
            }
        }
        $this->relacionDeTransicion[$this->estadoInicial][$union][] = $this->estadosFinales[0];
    }
    private function ER(){
        $expresionRegularFinal = "((";
        $expresionRegularFinal = $expresionRegularFinal.$this->transicionAsiMismo($this->estadoInicial).")*(";
        foreach($this->relacionDeTransicion[$this->estadoInicial] as $etiqueta => $transiciones) {
            foreach($transiciones as $transicion) {
                if($transicion == $this->estadosFinales[0]) {
                    $expresionRegularFinal = $expresionRegularFinal.$etiqueta.")";
                }
            }
        }
        $expresionRegularFinal = $expresionRegularFinal."(".$this->transicionAsiMismo($this->estadosFinales[0]).")*(";
        foreach($this->relacionDeTransicion[$this->estadosFinales[0]] as $etiqueta => $transiciones) {
            foreach($transiciones as $transicion) {
                if($transicion == $this->estadoInicial) {
                    $expresionRegularFinal = $expresionRegularFinal.$etiqueta."))(";
                }
            }
        }
        $expresionRegularFinal = $expresionRegularFinal.$this->transicionAsiMismo($this->estadoInicial).")*(";
        foreach($this->relacionDeTransicion[$this->estadoInicial] as $etiqueta => $transiciones) {
            foreach($transiciones as $transicion) {
                if($transicion == $this->estadosFinales[0]) {
                    $expresionRegularFinal = $expresionRegularFinal.$etiqueta.")";
                }
            }
        }
        $expresionRegularFinal = $expresionRegularFinal."(".$this->transicionAsiMismo($this->estadosFinales[0]).")*";
        return $expresionRegularFinal;
    }
    public function convertirAFDaER () {
        if(count($this->estadosFinales)>1) {
            foreach($this->estadosFinales as $estadoFinal) {
                $this->relacionDeTransicion[$estadoFinal]["@"][] = "F";
            }
            $this->relacionDeTransicion["F"] = [];
            $this->conjuntoDeIdentificadores[] = "F";
            $this->estadosFinales = ["F"];
        }
        $this->transformarEtiquetasAExpresionesRegulares();
        $this->agregarTransicionesVaciasEntreEstados ();
        $this->eliminarEstadosNoInicialesYNoFinales();
        $this->unirTransiciones();
        return $this->ER();
    }
    private function buscarMasTransicionesAFND($estado1, $estado2) {
        foreach ($this->relacionDeTransicion[$estado1] as $a => $transicion) {
            foreach ($transicion as $t) {
                if ($t == $estado2) {
                    $caracteres[] = $a;
                }
            }
        }
        return implode(",", $caracteres);
    }
    public function dibujarAFND() {
        $link = "https://image-charts.com/chart?cht=gv&chl=digraph {";
        foreach ($this->estadosFinales as $estadoFinal) {
            if ($estadoFinal != "") {
                $link = $link . '"' . $estadoFinal . '"' . "[shape=doublecircle];";
            }
        }
        $link = $link . '"ei"[shape=point]; "ei" -> ' . '"' . $this->estadoInicial . '"' . ';';

        foreach ($this->relacionDeTransicion as $estado => $transiciones) {
            $listos = [];
            foreach ($transiciones as $transicion) {
                foreach ($transicion as $t) {
                    if (!in_array($t, $listos)) {
                        $link = $link . '"' . $estado . '"' . " -> " . '"' . $t . '"' . '[label="' . $this->buscarMasTransicionesAFND($estado, $t) . '"];';
                        $listos[] = $t;
                    }
                }
            }
        }
        $link = $link . "}";
        return $link;
    }
}
?>