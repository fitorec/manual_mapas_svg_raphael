# Desarrollando Mapas con SVG y Raphael.js

> **Nota:** Esto sólo es un borrador de algun futuro taller del [ADSL](http://adsl.org.mx/).

Hace algunos meses escribí un articulo acerca del desarrollo de un mapa de [México con Javascript y SVG](http://fitorec.wordpress.com/2011/04/11/mapa-de-mexico-con-svg-y-javascript-empotrado-en-html/).

Hoy a la distancia me permito hacer un análisis respecto a la solución desarrollada en aquella ocasión, y presento otra solución bajo las mismas premisas:

 - **Sin embedidos que requieran plugins especiales**(No Flash, Apples, ni SilverXX).
 - Deberá ser **Cross-Browser**.
 - Deberá ser lo mas reutilizable posible.
 - Deberá estar basada en estándares.

###Resultado.

<div id="mapa_mexico">
	<div id="chaptersMap">
	</div>
</div>

Este manual tratare de explicar una metodología para el desarrollo de dichos mapas. El cual esta dividido en 3 partes: en la **1ra** parte tratare de explicar como generar un embebido basado en canvas desarrollado con **svg** y **Javascript**, la **2da** parte explicare como desarrollar tus propios mapas **SVG** y como integrarlos, y finalmente como **3ra** parte consta de la conclusión e ideas a futuro, sin mas espero que sea de tu agrado.

## Entrenamiento previo

>[**Raphael.js**](http://raphaeljs.com/) es un framework para el manejo de canvas, la característica  que mas me agrada es que esta basado en el estándar **SVG**.

Por ejemplo el estándar **svg** describe una secuencia de puntos(ó nodos) los cuales pueden ser curvas de baizer entre otras, esta secuencia de puntos es conocida como [**path**](http://w3c.com/svg/path). por otra parte **Raphael** define una función con el mismo nombre **path** el cual recibe como argumento un `String` que describe la secuencia de puntos.


###Creo que queda más claro con el siguiente código Javascript:


	//creamos un lienzo del tipo Raphael el cual tendra un tamaño de 400x400
	var lienzoRaphael = Raphael('cat_gnu', 400, 400);
	//Creamos un path apartir de la secuencia de puntos de nuestra img SVG
	var pathGatoGNU = lienzoRaphael.path('m 234.02458,396.16011 c ...-3.20489 z');
	//Agregamos el estilo del path que estamos dibujando
	pathGatoGNU.attr({fill: '#000000',stroke: '#3C0600','stroke-width': 1});

###Faltaria generar el div `cat_gnu` en el código HTML:

	<div id="cat_gnu">
	</div>

Como podemos ver existe mucha transparencia entre el estándar **SVG** y el espacio de nombres(`namespace`) que define **Raphael.js**, en este caso por cuestiones didácticas el argumento que recibe path es solo una pequeña secuencia, pero puedes ver el resultado:

<div id="cat_gnu">
</div>


## Haciendo Came, Came Ha!!.

> Entiéndase **came came Ha!** como una técnica que no es mía(_del autor de este articulo_), pero  que descubrí, la aprendí, que estoy intentando mejorar y compartir.

Tras una búsqueda me encontré con un articulo que personalmente me agrado mucho la idea y del cual baso la primera parte de este manual, dejo la liga la cual te invito a revisar(aunque tratare de explicar a mayor detalle):

<http://return-true.com/2011/06/using-raphaeljs-to-create-a-map/>

Dada la explicación previa acerca de **Raphael.js**, ¿que pasaría si a `pathGatoGNU` le agregamos algún evento?, por ejemplo el evento `click`, como se muestra a continuación:

	pathGatoGNU.click(function(){
		alert('Miauuuuuuuuuu!');
	});

El resultado lo puedes ver en el [ejemplo 02](./ejemplo02/index.html) de este pequeño manual, puedes probar con darle `click` encima del **gatoGNU**.

Esta misma idea la podemos trasladar al evento `hover` y con esto generamos el efecto del cambio de color en el momento en que el mouse se posiciones sobre algun `path` y con el evento `click` podemos cambiar el `location` de nuestra pagina y con esto generar un enlace, pues simplemente así es como funciona el **mapa de México** que se mostró en el inicio.

En este caso como son varios estados la información de los `paths` se agregaron en un objeto `json` el cual tiene la siguiente sintaxis.

## Datos json Muestra

	var paths = {
		OAX: {
			name: 'Oaxaca',
			slug: 'oax',
			url: '#',
			path: 'm 71.78125,...,-1.8125 z'
		},
		/* ... Los datos de demas estados ... */
		VER: {
			name: 'Veracruz',
			slug: 'VER',
			url: '#',
			path: 'm 400.343,..,-1.71875 z'
		}
	}



## Observe el siguiente Código en Javascript


	jQuery(function($){
		var r = Raphael('chaptersMap', 900, 600);
		r.safari();
		var _label = r.popup(50, 50, "").hide();
		attributes = {
				fill: '#485e96',
				stroke: '#1e336a',
				'stroke-width': 2,
				'stroke-linejoin': 'round'
			};
		arr = new Array();
		/* para cada path de nuestra fuente svg vamos a dibujar un path del tipo Raphael */
		for (var correntPath in paths) {
			var obj = r.path(paths[correntPath].path);
			arr[obj.id] = correntPath;
			obj.attr(attributes);
			/* Al estar encima el mouse de nuestro correntPath, Cambiamos el color y se restablece cuando se deja */
			obj.hover(function(){
				this.animate({
					fill: '#733A6A',
					stroke: '#1F131D'
				}, 300);
				bbox = this.getBBox();
						_label.attr({text: paths[arr[this.id]].name}).update(bbox.x, bbox.y + bbox.height/2, bbox.width).toFront().show();
			}, function(){
				this.animate({
					fill: attributes.fill,
					stroke: attributes.stroke
				}, 300);
				_label.hide();
			});
			/* Accion cuando le damos click a alguna parte de nuestro mapa */
			obj.click(function(){
				location.href = paths[arr[this.id]].url;
			});
		}//fin For
	});


Otro ejemplo interesante:

 - <http://raphaeljs.com/australia.html>
