(function($){
    $.fn.resizeOnApproach = function(settings){
    
    
    
        var config = {
            'elementDefault': 35,
            'elementClosest': 55,
            'triggerDistance': 200,
            'setWidthAndHeight':false,
            'leftToRight' : false,
            'reduce' : 3
        };
        
        if (settings) 
            $.extend(config, settings);
        
       
        
        
        var setWidthAndHeight=config.setWidthAndHeight;
        var expandIcon = this;
        var imgSize = config.elementDefault;
        var imgMax = config.elementClosest;
        var trigger = config.triggerDistance;
        var reduce = config.reduce;
        
        var max = imgMax - imgSize;
        
        var factor = max / trigger;
        var resized = false;
        $(document).ready(function(){
            expandIcon.each(function(){
                this.style.width = imgSize + 'px';
                if (setWidthAndHeight) {
                           this.style.height = imgSize + 'px';
                }
            });
        });

        var f = function(e){

var between = 0;
        var imgSize = config.elementDefault;
        
            var mouseX = e.pageX;
            var mouseY = e.pageY;
var z = 100;

            expandIcon.each(function(){
                var w = $(this).width();
                var h = $(this).height();

                //how far away the top left corner of the element is from the corner of the window
                var pos = $(this).offset();
                //calculate the distance from the mouse poiter to the centre of the square. Sum takes into account that the image position is taken from corner
                var dist = distToSqEdge(w, pos.left + (w / 2), 
                  pos.top + (h / 2), mouseX, mouseY);
                //set the distance to zero if inside the square
                
                var size = 0;
                if (dist < trigger) {
                    if (dist < 0) {
                        dist = 0;
                    }
                    resized = true;
                    size = imgSize + (max - (dist * factor));
                }
                else {
                    size = imgSize;
                }
                this.style.width = (size - between) + 'px';
                if (setWidthAndHeight) {
                       this.style.height = size + 'px';
                }
                var avg = (imgMax + size) / 2;
                $(this).find('img').height(avg).width(avg);
if (config.leftToRight) {
  $(this).css('zIndex', z--);
}
if (reduce > 0) {
  between += (reduce - 1);
  imgSize--;
}
if (between > imgSize - 5) { between = imgSize - 5; }


            });
            
        };
        $(document).mousemove(f);
        f({pageX: 0, pageY: 0});
    }
    
})(jQuery)





















//returns the distance from the edge of the square of the given width and centre C to the
//point P. If the distance is negative, the mouse in within the square
function distToSqEdge(sqWidth, cx, cy, px, py){

    // Fix issues with hitting the (0,0) point
    if (cx == px)
      cx--;
    if (cy == py)
      cy--;

    //length of line from point to centre
    var pl = Math.sqrt((cx - px) * (cx - px) +
    (cy - py) *
    (cy - py));
    
    //the x and y length of the line
    vx = px - cx;
    vy = py - cy;
    
    //determine the unit vector to the side the line intersects the square
    var Xx = 0;
    var Xy = 0;
    if (vx > vy) {
        if (vx > -vy) {
            Xx = 1;
        }
        else {
            Xy = 1;
        }
    }
    else {
        if (vx > -vy) {
            Xy = -1;
        }
        else {
            Xx = -1;
        }
    }
    
    // determine the unit vector of line to mouse point
    vlength = Math.sqrt((vx * vx) + (vy * vy));
    
    vux = vx / vlength;
    
    vuy = vy / vlength;
    
    cosA = vux * Xx + vuy * Xy;
    
    //distance from centre to the edge of the square
    centreToSqEdge = Math.abs((0.5 * sqWidth) / cosA);
    
    mouseToSquareEdge = vlength - centreToSqEdge;
    return mouseToSquareEdge;
    
}
