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
    public function convertirAFDAER () {
        foreach($this->estadosFinales as $estadoFinal) {
            $this->relacionDeTransicion[$estadoFinal]["@"][] = "F";
        }
        $this->relacionDeTransicion["F"] = [];
        $this->conjuntoDeIdentificadores[] = "F";
        $this->estadosFinales = ["F"];
        $this->cambiarEtiquetasAExpresionesRegulares();
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