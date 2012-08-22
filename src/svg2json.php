<?php

include_once('./src/config.php');
/**
 * Genera un archivo/salida Json para generar mapas con Raphael.js
 *
 * @param String $svgFile Ruta del archivo a leer para genear el Json
 * @param String $jsonFile Ruta del archivo destino(de no existir imprime el resultado en la salida estandar)
 * @return Boolean Si la opeación se pudo realizar
 * @link http://fitorec.github.com/manual_mapas_svg_raphael
 */
function generarJson($svgFile, $jsonFile=null) {
	global $namesJson; /* importamos la variable global en caso de definir los nombres */
	// Cargamos el SVG que necesitamos
	$svg =  simplexml_load_file($svgFile) or die("No se pudo abrir el archivo SVG ${svgFile}");
	// Para cada path de nuestro archivo svg 
	foreach ($svg->path as $p):
	  $path = (Array)$p;
	  $path=$path['@attributes'];
	  if( !isset($path['id'])) continue;
	  $name = isset($namesJson[$path['id']])? $namesJson[$path['id']]: $path['id'];
	  $paths[$path['id']] = array(
		  'name' => $name,
		  'slug' => $path['id'],
		  'url' => '#',
		  'path' => $path['d']
	  );
	endforeach;
	$strOut = 'var paths ='.json_encode($paths);
	if( $jsonFile && file_exists($jsonFile) ){
		$fout = fopen($jsonFile,'w') or die('El archivo '.$jsonFile.' No se pudo abrir para escritura');
		fwrite($fout,$strOut);
		fclose($fout);
	}else{
		echo $strOut."\n";
	}
}

switch (count($argv)) {
	case 2:
		generarJson($argv[1]);
		break;
	case 3:
		generarJson($argv[1], $argv[2]);
		break;
	default :
		echo "\nPor favor ejecute\n\tphp ${argv[0]} ArchivoEntrada.svg ArchivoSalida.js\n";
		exit(1);
}

?>
