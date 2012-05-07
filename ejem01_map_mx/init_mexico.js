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
