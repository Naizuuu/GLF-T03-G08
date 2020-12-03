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
    private function cambiarEtiquetasAExpresionesRegulares() {
        foreach($this->conjuntoDeIdentificadores as $identificador) {
            $expresionRegular = [];
            $unset = 0;
            foreach ($this->relacionDeTransicion[$identificador] as $caracter => $transiciones) {
                if ($this->relacionDeTransicion[$identificador][$caracter][0] == $identificador) {
                    $expresionRegular[] = $caracter;
                    unset($this->relacionDeTransicion[$identificador][$caracter]);
                    $unset = 1;
                }
            }
            if($unset == 1) {
                $this->relacionDeTransicion[$identificador][implode('+', $expresionRegular)][] = $identificador;
            }
        }
    }
    private function estanConectados ($estado1, $estado2) {
        foreach($this->relacionDeTransicion[$estado1] as $caracter => $estadoDeLlegada) {
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
    public function convertirAFDaER () {
        foreach($this->estadosFinales as $estadoFinal) {
            $this->relacionDeTransicion[$estadoFinal]["@"][] = "F";
        }
        $this->relacionDeTransicion["F"] = [];
        $this->conjuntoDeIdentificadores[] = "F";
        $this->estadosFinales = ["F"];
        $this->cambiarEtiquetasAExpresionesRegulares();
        $this->agregarTransicionesVaciasEntreEstados ();
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