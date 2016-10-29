<?php
/**
 * PTabla2 -- Componente encargado de administrar el elemento "Tabla" (table).
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */

/**
 * Componente encargado de administrar el elemento "Paginaci�n" de una tabla.
 */
require_once('PTablaPaginacion.php');

class PTabla2 {
    /**
     * Nombre de la Tabla.
     *
     * @var string
     */
    var $nombre;

    /**
     * Arreglo con las columnas de la tabla.
     * Los elementos del arreglo son de tipo PTablaColumna.
     *
     * @var array
     */
    var $columnas;

    /**
     * El n�mero de filas que se muestran por p�gina en la tabla.
     *
     * @var int
     */
    var $filasPagina;

    /**
     * El nombre de la clave que tiene el valor de la p�gina actual.
     *
     * @var string
     */
    var $paginaActualClaveNombre;

    /**
     * Indica si los datos de la tabla se pueden ordenar de diferentes maneras.
     *
     * @var boolean
     */
    var $hayOrdenamiento;

    /**
     * El t�tulo de la lista de ordenamiento.
     *
     * @var string
     */
    var $ordenamientoTitulo;

    /**
     * El nombre de la clave que tiene el valor del criterio por el que se deben ordenar los datos.
     *
     * @var string
     */
    var $ordenamientoClaveNombre;

    /**
     * El nombre de la funci�n javascript que administra el ordenamiento en el cliente.
     *
     * @var string
     */
    var $ordenamientoFuncionNombre;

    /**
     * El valor del criterio por el que est�n ordenados los datos inicialmente.
     *
     * @var string
     */
    var $ordenInicial;

    /**
     * Arreglo con el nombre y valor de las propiedades de la Tabla.
     * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
     * de la propiedad (ej. $propiedades['name'] = "nombreTabla", asignara el valor "nombreTabla"
     * a la propiedad "name" del elemento "table" HTM).
     *
     * @var array
     */
    var $propiedades;

    /**
     * Arreglo con el nombre y valor de las propiedades de la fila del t�tulo de la Tabla.
     * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
     * de la propiedad (ej. $trTituloPropiedades['align'] = "center", asignara el valor "center"
     * a la propiedad "align" del elemento "tr" del t�tulo de la Tabla).
     *
     * @var array
     */
    var $trTituloPropiedades;

    /**
     * Arreglo con el nombre y valor de las propiedades de todas las filas de la Tabla.
     * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
     * de la propiedad (ej. $trPropiedades['align'] = "center", asignara el valor "center"
     * a la propiedad "align" del elemento "tr" de todas las filas de la Tabla).
     *
     * @var array
     */
    var $trPropiedades;

    /**
     * El mensaje que se debe mostrar cuando la tabla no tiene datos para mostrar.
     *
     * @var string
     */
    var $sinDatosMensaje;

    /**
     * Funci�b que devuelve el html de la �ltima fila de la tabla.
     *
     * @var string
     */
    var $totalFilaFuncion;

    /**
     * Objeto que tiene los m�todos para administrar los datos.
     *
     * @var object
     */
    var $controlador;

    /**
     * Nombre del m�todo que consulta los datos.
     * Esta funci�n debe devolver un entero que indica el numero de filas que tiene la consulta.
     *
     * @var string
     */
    var $conDatosFuncionNombre;

    /**
     * Nombre del m�todo que devuelve los datos de una fila.
     * Esta funci�n debe devolver un arreglo de strings o false cuando no existan m�s registros.
     *
     * @var string
     */
    var $conFilaFuncionNombre;

    /**
     * N�mero de filas en total que tiene la consulta que se va a listar.
     *
     * @var int
     */
    var $filasTotal;
    
    var $filasLimitePaginacion;

    var $sizePagination;
    
    /**
     * Constructora. Inicializa los atributos.
     *
     * @param string $nombre Nombre de la tabla.
     * @return PTabla
     */
    function PTabla2($nombre) {
        // Inicializacion de atributos.
        $this->nombre						= $nombre;

        $this->columnas						= array();
        $this->filasPagina					= 0;
        $this->paginaActualClaveNombre		= $this->nombre."Pag";
        $this->hayOrdenamiento				= false;
        $this->ordenamientoTitulo			= "-- Seleccione --";
        $this->ordenamientoClaveNombre		= $this->nombre."Ord";
        $this->ordenamientoFuncionNombre	= $this->nombre."OrdFuncion";
        $this->ordenInicial					= null;
        $this->propiedades					= array();
        $this->sinDatosMensaje				= "";
        $this->datos						= array();
        $this->trTituloPropiedades			= array();
        $this->trPropiedades				= array();
        $this->totalFilaFuncion				= null;

        $this->controlador					= null;
        $this->conDatosFuncionNombre		= null;
        $this->conFilaFuncionNombre			= null;
        $this->filasTotal					= null;
        $this->sizePagination = "100%";
    }

    /**
     * Modifica el n�mero de filas que se muestran por p�gina en la tabla.
     *
     * @param int $filasPagina El n�mero de filas que se muestran por p�gina en la tabla.
     */
    function modFilasPagina($filasPagina) {
        $this->filasPagina = $filasPagina;
    }

    /**
     * Modifica si los datos de la tabla se pueden ordenar de diferentes maneras.
     *
     * @param boolean $hayOrdenamiento Indica si los datos de la tabla se pueden ordenar de diferentes maneras.
     */
    function modHayOrdenamiento($hayOrdenamiento, $ordenInicial = null) {
        $this->hayOrdenamiento = $hayOrdenamiento;
        $this->ordenInicial = $ordenInicial;
    }

    /**
     * Modifica el t�tulo de la lista de ordenamiento.
     *
     * @param string $ordenamientoTitulo
     */
    function modOrdenamientoTitulo($ordenamientoTitulo) {
        $this->ordenamientoTitulo = $ordenamientoTitulo;
    }

    /**
     * Modifica el objeto y los nombres de los m�todos que administran los items.
     * El m�todo que consulta los datos debe devolver un entero que indica el numero de filas que tiene la consulta.
     * El m�todo que devuelve los datos de una fila debe devolver un arreglo de strings o false cuando no existan m�s registros.
     *
     * @param object $controlador Objeto que tiene los m�todos para administrar los datos.
     * @param string $conDatosFuncionNombre Nombre del m�todo que consulta los datos.
     * @param string $conFilaFuncionNombre Nombre del m�todo que devuelve los datos de una fila.
     */
    function modControlador($controlador, $conDatosFuncionNombre, $conFilaFuncionNombre) {
        $this->controlador = $controlador;
        $this->conDatosFuncionNombre = $conDatosFuncionNombre;
        $this->conFilaFuncionNombre = $conFilaFuncionNombre;
    }

    /**
     * Adiciona una propiedad y su valor a la Tabla.
     *
     * @param string $nombre Nombre de la propiedad.
     * @param string $valor Valor de la propiedad.
     */
    function adiPropiedad($nombre, $valor) {
        $this->propiedades[$nombre] = $valor;
    }

    /**
     * Adiciona una propiedad y su valor a la fila del t�tulo de la Tabla.
     *
     * @param string $nombre Nombre de la propiedad.
     * @param string $valor Valor de la propiedad.
     */
    function adiTrTituloPropiedad($nombre, $valor) {
        $this->trTituloPropiedades[$nombre] = $valor;
    }

    /**
     * Adiciona una propiedad y su valor a las filas de la Tabla seleccionadas.
     *
     * @param string $nombre Nombre de la propiedad.
     * @param string $valor Valor de la propiedad.
     * @param int $filaInicio La fila desde la que se debe incluir la propiedad.
     * @param int $filasIntervalo El intervalo de filas en las que se debe incluir la propiedad.
     */
    function adiTrPropiedad($nombre, $valor, $filaInicio = 1, $filasIntervalo = 1) {
        $this->trPropiedades[] = array(	"nombre" => $nombre, "valor" => $valor, "filaInicio" => $filaInicio, "filasIntervalo" => $filasIntervalo);
    }

    /**
     * Modifica el mensaje que se debe mostrar cuando la tabla no tiene datos para mostrar.
     *
     * @param string $sinDatosMensaje El mensaje que se debe mostrar cuando la tabla no tiene datos para mostrar.
     */
    function adiSinDatosMensaje($sinDatosMensaje) {
        $this->sinDatosMensaje = $sinDatosMensaje;
    }

    /**
     * Adiciona una columna "Simple" a la tabla.
     * Una columna "Simple" es una columna que tiene un t�tulo, muestra un solo campo de los datos,
     * y no tiene ni propiedades ni una plantilla especial.
     *
     * @param strin $titulo T�tulo de la Columna.
     * @param strin $clave Nombre de la clave del campo que se quiere mostrar en la Columna.
     * @param boolean $esOrdenable Indica si los datos de la tabla se pueden ordenar por la Columna.
     */
    function adiColumnaSimple($titulo, $clave, $esOrdenable = false) {
        $columna = new PTablaColumna();
        $columna->adiTitulo($titulo);
        $columna->adiClave($clave);
        $columna->modEsOrdenable($esOrdenable);
        $this->columnas[] = $columna;
    }

    /**
     * Adiciona una columna a la tabla.
     *
     * @param PTablaColumna $columna
     */
    function adiColumna($columna) {
        $this->columnas[] = $columna;
    }

    /**
     * Adiciona la funcion que devuelve el html de la fila al final de la tabla.
     *
     * @param string $totalFilaFuncion
     */
    function adiTotalFilaFuncion($totalFilaFuncion) {
        $this->totalFilaFuncion = $totalFilaFuncion;
    }

    /**
     * Genera el codigo HTM de la tabla, su paginaci�n y su ordenamiento y los pinta.
     * El nombre del tag para la Tabla debe ser igual al nombre del componente.
     * El nombre del tag para la paginaci�n de la Tabla debe ser igual
     * al nombre del componente seguido por la palabra "Paginacion".
     * El nombre del tag para el ordenamiento de la Tabla debe ser igual
     * al nombre del componente seguido por la palabra "Ordenamiento".
     * Ej.: para la tabla con el nombre "nombreTabla"
     * ser�n: "nombreTablaPaginacion" y "nombreTablaOrdenamiento".
     *
     * @param NokTemplate $nokTemplate Objeto de la libreria NokTemplate que permite usar templates.
     */
    function pintar($nokTemplate) {
        $tablaHtm = $this->conHtm();
        $nokTemplate->asignar($this->nombre, $tablaHtm);

        $paginacionName = $this->nombre."Paginacion";
        $paginacionHtm = $this->PaginacionHtm($paginacionName);
        $paginacionHtm.= $this->PaginacionJquery($this->nombre,$paginacionName);
        $paginacionTag = $paginacionName;
        $nokTemplate->asignar($paginacionTag, $paginacionHtm);
        
        /*$paginacionHtm = $this->conPaginacionHtm();
        $paginacionTag = $this->nombre."Paginacion";
        $nokTemplate->asignar($paginacionTag, $paginacionHtm);

        $ordenamientoHtm = $this->conOrdenamientoHtm();
        $ordenamientoTag = $this->nombre."Ordenamiento";
        $nokTemplate->asignar($ordenamientoTag, $ordenamientoHtm);

        $filasPaginaNumHtm = $this->conFilasPaginaNumHtm();
        $filasPaginaNumTag = $this->nombre."Total";
        $nokTemplate->asignar($filasPaginaNumTag, $filasPaginaNumHtm);*/
    }
    
    function PaginacionHtm($idTablePaginator)
    {
            $paginacionHtm = " "
                    . "<div id=\"$idTablePaginator\" class=\"pager black\" style=\"width:$this->sizePagination; margin:auto;\"> </div>";

            return $paginacionHtm;
    }

    function PaginacionJquery($idTable,$idPaginacion)
    {
        $paginacionJquery = "
            <script>
            (function($){
           	var rowCount = $('#$idTable tr').length-1;
                $('#$idPaginacion').smartpaginator({ 
                             totalrecords: rowCount,
                             recordsperpage: $this->filasLimitePaginacion,
                             datacontainer: '$idTable', 
                             dataelement: 'tr', 
                             initval: 0, 
                             next: 'Next', 
                             prev: 'Prev', 
                             first: 'First', 
                             last: 'Last', 
                             theme: 'black' 
                     });
            })(jQuery);
            </script>";
        return $paginacionJquery;
    }
    
    /**
     * Retorna el c�digo para el "table" HTM.
     *
     * @return string
     */
    function conHtm() {
        $paginaActual = (isset($_GET[$this->paginaActualClaveNombre]) ? $_GET[$this->paginaActualClaveNombre] : 1);
        $ordenActual = (isset($_GET[$this->ordenamientoClaveNombre]) ? $_GET[$this->ordenamientoClaveNombre] : $this->ordenInicial);

        $filaInicio = ($paginaActual - 1) * $this->filasPagina;
        $this->filasTotal = $this->controlador->{$this->conDatosFuncionNombre}($filaInicio, $ordenActual);

        $tablaHtm = "<table name=\"{$this->nombre}\"";
        foreach ($this->propiedades as $nombre => $valor) {
            $tablaHtm .= " {$nombre}=\"{$valor}\"";
        }
        $tablaHtm .= ">";

        $tablaHtm .= $this->conTitulosHtm();
        
        for ($i = 0; ($this->filasPagina === 0 or $i < $this->filasPagina) and (($datos = $this->controlador->{$this->conFilaFuncionNombre}($i + 1)) !== false); $i++) {
            $filaNumero = 1;
            $tablaHtm .= "\n<tr";

            foreach ($this->trPropiedades as $propiedad) {
                $nombre = $propiedad['nombre'];
                $valor = $propiedad['valor'];
                $filaInicio = $propiedad['filaInicio'];
                $filasIntervalo = $propiedad['filasIntervalo'];

                $filaOperacion = ($filaNumero - $filaInicio) / $filasIntervalo;
                if ($filaNumero >= $filaInicio and is_int($filaOperacion)) {
                    $tablaHtm .= " {$nombre}=\"{$valor}\"";
                }
            }

            $tablaHtm .= ">";

            $columnaNumero = 0;
            foreach ($this->columnas as $columna) {
                /* @var $columna PTablaColumna */
                $tablaHtm .= "\n\t<td";

                foreach ($columna->conTdPropiedades() as $nombre => $valor) {
                    $valor = str_replace("{id}", $datos['id'], $valor);
                    $tablaHtm .= " {$nombre}=\"{$valor}\"";
                }

                $tablaHtm .= ">";
                $tablaHtm .= $columna->conHtm($datos[$columnaNumero++]);
                $tablaHtm .= "</td>";
            }

            $tablaHtm .= "\n</tr>";

            $filaNumero++;
        }

        if ($this->totalFilaFuncion != null) {
            $tablaHtm .= $this->controlador->{$this->totalFilaFuncion}();
        }

        if ($i == 0) {
            $tablaHtm .= "<tr><td colspan=\"".count($this->columnas)."\" align=\"center\">{$this->sinDatosMensaje}</td></tr>";
        }

        $tablaHtm .= "</table>";

        return $tablaHtm;
    }

    /**
     * Devuelve el total de filas y el intervalo de filas de los datos que se van a mostrar en la p�gina actual.
     *
     * @return string
     */
    function conFilasPaginaNumHtm() {
        $paginaActual = (isset($_GET[$this->paginaActualClaveNombre]) ? $_GET[$this->paginaActualClaveNombre] : 1);
        $inicio = ($paginaActual - 1) * $this->filasPagina;

        if ($this->filasPagina > 0) {
            $fin = $inicio + $this->filasPagina;
            $fin = ($fin > $this->filasTotal ? $this->filasTotal : $fin);
        } else {
            $fin = $this->filasTotal;
        }

        if ($this->filasTotal > 0) {
            $inicio++;
        }

        $filasPaginaNumHtm = "<span id=\"totalInicio\">{$inicio}</span> - <span id=\"totalFin\">{$fin}</span> de <span id=\"totalNumero\">{$this->filasTotal}</span>";

        return $filasPaginaNumHtm;
    }

    /**
     * Retorna el c�digo HTM de la paginaci�n.
     *
     * @return string
     */
    function conPaginacionHtm() {
        $tablaPaginacion = new PTablaPaginacion($this->nombre, $this->filasPagina);
        $paginacionHtm = $tablaPaginacion->conHtm($this->filasTotal);

        return $paginacionHtm;
    }

    /**
     * Retorna el c�digo HTM con los t�tulos de las Columnas de la Tabla.
     *
     * @return string
     */
    function conTitulosHtm() {
        $titulosHtm = "\n<tr";

        foreach ($this->trTituloPropiedades as $nombre => $valor) {
            $titulosHtm .= " {$nombre}=\"{$valor}\"";
        }

        $titulosHtm .= ">";

        foreach ($this->columnas as $columna) {
            $titulosHtm .= "\n\t<th";

            foreach ($columna->conTdTituloPropiedades() as $nombre => $valor) {
                $titulosHtm .= " {$nombre}=\"{$valor}\"";
            }

            $titulosHtm .= ">".$columna->conTitulo()."</th>";
        }

        $titulosHtm .= "\n</tr>";

        return $titulosHtm;
    }

    /**
     * Retorna le c�digo HTM para el ordenamiento de la Tabla.
     *
     * @return string
     */
    function conOrdenamientoHtm() {
        $ordenamientoHtm = "";
        $getHtm = "";

        if ($this->hayOrdenamiento) {
            $ordenActual = (isset($_GET[$this->ordenamientoClaveNombre]) ? $_GET[$this->ordenamientoClaveNombre] : $this->ordenInicial);

            foreach ($_GET as $clave => $valor) {
                if ($clave != $this->ordenamientoClaveNombre and $clave != $this->paginaActualClaveNombre) {
                    $getHtm .= $clave."=".$valor."&";
                }
            }

            $urlHtm = $_SERVER['PHP_SELF']."?".$getHtm.$this->ordenamientoClaveNombre."=";

            $ordenamientoHtm = "
				<script language=\"javascript\">
					function {$this->ordenamientoFuncionNombre}(combo) {
						var url = \"{$urlHtm}\" + combo.value;
						window.location=url;
					}
				</script>";

            $ordenamientoHtm .= "
				<select name=\"{$this->ordenamientoClaveNombre}\" onChange=\"{$this->ordenamientoFuncionNombre}(this);\">";

            if ($this->ordenInicial == null) {
                $ordenamientoHtm .= "<option value=\"\"";

                if ($ordenActual == $this->ordenInicial) {
                    $ordenamientoHtm .= " selected";
                }

                $ordenamientoHtm .= ">{$this->ordenamientoTitulo}</option>";
            }

            foreach ($this->columnas as $columna) {
                /* @var $columna PTablaColumna */
                if ($columna->conEsOrdenable()) {
                    $columnaClave = $columna->conClave();
                    $ordenamientoHtm .= "\n\t\t\t\t\t<option value=\"{$columnaClave}\"";

                    if ($ordenActual == null and $columnaClave == $this->ordenInicial) {
                        $ordenamientoHtm .= " selected";
                    } else if ($ordenActual != null and $columnaClave == $ordenActual) {
                        $ordenamientoHtm .= " selected";
                    }

                    $ordenamientoHtm .= ">{$columna->conTitulo()}</option>";
                }
            }

            $ordenamientoHtm .= "</select>";

        }

        return $ordenamientoHtm;
    }
}

/**
 * PTablaColumna -- Componente encargado de administrar el elemento "Columna" de una tabla (td).
 *
 * @author		Diego M. Garc�a M. - diegarc@gmail.com.
 * @version		3.0
 * @since		2007.
 */
class PTablaColumna2 {
    /**
     * T�tulo de la Columna.
     *
     * @var string
     */
    var $titulo;

    /**
     * Arreglo con el nombre y valor de las propiedades del t�tulo de la Columna.
     * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
     * de la propiedad (ej. $tdTituloPropiedades['name'] = "nombreTitulo", asignara el valor "nombreTitulo"
     * a la propiedad "name" del elemento "td" HTM).
     *
     * @var array
     */
    var $tdTituloPropiedades;

    /**
     * Arreglo con el nombre y valor de las propiedades de la Columna.
     * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
     * de la propiedad (ej. $tdPropiedades['name'] = "nombreColumna", asignara el valor "nombreColumna"
     * a la propiedad "name" de cada elemento "td" HTM).
     *
     * @var array
     */
    var $tdPropiedades;

    /**
     * Indica si los datos de la tabla se pueden ordenar por esta columna.
     *
     * @var boolean
     */
    var $esOrdenable;

    /**
     * El tama�o m�ximo del texto para la columna.
     * Si el texto de la columna supera el t�ma�o m�ximo,
     * se cortar� la cadena de texto y se a�adir�n tres (3) puntos al final (...).
     * Ej.: Si el tama�o m�ximo de la cadena es tres (3) y un item es la palabra "Columna",
     * el texto aparecer� de la siguiente manera: "Col...".
     *
     * @var int
     */
    var $textoTam;

    /**
     * Clave con la cual se identifica la columna.
     *
     * @var string
     */
    var $clave;

    /**
     * Constructora. Inicializa los atributos.
     *
     * @param string $nombre Nombre de la Columna.
     * @return PTablaColumna
     */
    function PTablaColumna2() {
        // Inicializacion de atributos.
        $this->titulo				= null;
        $this->tdTituloPropiedades	= array();
        $this->tdPropiedades		= array();
        $this->plantilla			= null;
        $this->esOrdenable			= false;
        $this->textoTam				= 0;

        $this->clave = null;
    }

    /**
     * Retorna el titulo de la Columna.
     *
     * @return string
     */
    function conTitulo() {
        return $this->titulo;
    }

    /**
     * Retorna el arreglo con el nombre y valor de las propiedades del t�tulo de la Columna.
     * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
     * de la propiedad.
     *
     * @return array
     */
    function conTdTituloPropiedades() {
        return $this->tdTituloPropiedades;
    }

    /**
     * Retorna el arreglo con el nombre y valor de las propiedades de la Columna.
     * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
     * de la propiedad.
     *
     * @return array
     */
    function conTdPropiedades() {
        return $this->tdPropiedades;
    }

    /**
     * Devuelve la clave con la cual se identifica la columna.
     *
     * @return string
     */
    function conClave() {
        return $this->clave;
    }

    /**
     * Retorna si los datos de la tabla se pueden ordenar por esta Columna.
     *
     * @return boolean
     */
    function conEsOrdenable() {
        return $this->esOrdenable;
    }

    /**
     * Modifica el t�tulo de la Columna.
     *
     * @param string $titulo El t�tulo de la Columna.
     */
    function adiTitulo($titulo) {
        $this->titulo = $titulo;
    }

    /**
     * Adiciona una propiedad y su valor al t�tulo de la Columna.
     *
     * @param string $nombre Nombre de la propiedad.
     * @param string $valor Valor de la propiedad.
     */
    function adiTdTituloPropiedad($nombre, $valor) {
        $this->tdTituloPropiedades[$nombre] = $valor;
    }

    /**
     * Adiciona el nombre de la funcion que debe llamar la columna para obtener la cadena a imprimir.
     *
     * @param string $funcionNombre
     */
    function adiFuncion($funcionNombre) {
        $this->funcionNombre = $funcionNombre;
    }

    /**
     * Adiciona una propiedad y su valor a la Columna.
     *
     * @param string $nombre Nombre de la propiedad.
     * @param string $valor Valor de la propiedad.
     */
    function adiTdPropiedad($nombre, $valor) {
        $this->tdPropiedades[$nombre] = $valor;
    }

    /**
     * Modifica si los datos de la tabla se pueden ordenar por esta columna.
     *
     * @param boolean $esOrdenable Indica si los datos de la tabla se pueden ordenar por esta columna.
     */
    function modEsOrdenable($esOrdenable, $clave) {
        $this->esOrdenable = $esOrdenable;
        $this->clave = $clave;
    }

    /**
     * Modifica el tama�o m�ximo del texto para la columna.
     * Si el texto de la columna supera el t�ma�o m�ximo,
     * se cortar� la cadena de texto y se a�adir�n tres (3) puntos al final (...).
     * Ej.: Si el tama�o m�ximo de la cadena es tres (3) y un item es la palabra "Columna",
     * el texto aparecer� de la siguiente manera: "Col...".
     *
     * @param int $textoTam El tama�o m�ximo del texto para la columna.
     */
    function modTextoTam($textoTam) {
        $this->textoTam = $textoTam;
    }

    /**
     * Retorna el c�digo para cada "td" HTM de la Columna.
     *
     * @param array $datos La fila que tiene los datos que se quieren mostrar en la columna.
     * @return string
     */
    function conHtm($dato) {
        if ($this->textoTam > 0 and strlen($dato) > $this->textoTam) {
            $dato = substr($dato, 0, $this->textoTam)."...";
        }

        return $dato;
    }
}
