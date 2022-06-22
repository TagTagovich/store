 
      
        // Initiate a Canvas instance
        let canvas = new fabric.Canvas("canvas");
  
        // Initiate a Rect instance
        let rectangle = new fabric.Rect({
            width: 200,
            height: 100,
            fill: "",
            stroke: 'black',
            strokeWidth: 1,
            opacity: .3
        });
         
         function setFillRect(left, top, w, h) {
            let rectangle = new fabric.Rect({
            width: w,
            height: h,
            fill: "black",
            stroke: 'black',
            strokeWidth: 1,
            opacity: .3
        });
            canvas.add(rectangle);
         } 

         /*function backFillRect() {
            let rectangle = new fabric.Rect({
            width: 200,
            height: 100,
            fill: "",
            stroke: 'black',
            strokeWidth: 1,
            opacity: .3
        });
            canvas.add(rectangle);
         } */
         /*var eventObj = {};
         var moveHandler = function (evt) {
         var movingObject = evt.target;
         //movingObject.setCoords();
         var arrrr = movingObject.getBoundingRect();
         eventObj.widthObj = arrrr.width;
         console.log(eventObj.widthObj);
         //console.log(movingObject.get('left'), movingObject.get('top'));
         //return arrrr;
         };

         canvas.on('object:moving', moveHandler);*/


        // Render the Rect in canvas
         var imgUrl = vichImg.getAttribute("data-path-vich-img");
         canvas.add(rectangle);
         canvas.setWidth(document.body.scrollWidth);
         canvas.setHeight(1000);
         canvas.setActiveObject(canvas.item(0));
         //constgetElementByClass
         canvas.setBackgroundImage(imgUrl, canvas.renderAll.bind(canvas));
         //fabric.Object.prototype.selectionBackgroundColor = 'rgba(45,207,171,0.25)';

         /*canvas.on('object:selected', function (e) {
         e.target.transparentCorners = false;
         e.target.borderColor = '#cccccc';
         e.target.cornerColor = '#0CB7F0';
         e.target.minScaleLimit = 2;
         e.target.cornerStrokeColor = '#0CB7F0';
         e.target.cornerStyle = 'circle';
         e.target.minScaleLimit = 0;
         e.target.lockScalingFlip = true;
         e.target.padding = 5;
         e.target.selectionDashArray = [10, 5];
         e.target.borderDashArray = [10, 5];
         });*/

         var beginObj = canvas.getActiveObject().get('width');
         //var beginObj = this.getActiveObject().get('left');
         var obj = canvas.getActiveObject();
         var startX = obj.left;
         var startY = obj.top;                   
         var width = obj.width;
         
         //var ob = canvas.getActiveObject();
         //var coordik = ob.calcCoords();
         //alert(var left = coords.tl.x);
         
         //canvas.on('object:moving', function())
         
         /*canvas.on("object:moving", function(e) {
         var actObj = e.target;
         var coords = actObj.calcCoords(); 
         // calcCoords returns an object of corner points like this 
         //{bl:{x:val, y:val},tl:{x:val, y:val},br:{x:val, y:val},tr:{x:val, y:val}}
         var left = coords.tl.x;
         var top = coords.tl.y;
         return {left:left,top:top};
         });
         
         alert(left, top);*/
         /*canvas.on('object:moving', function(options) {
         obj.setCoords();
         console.log(obj.get('width'));
         });*/


         /*canvas.on("object:moving", function(e) {
         var actObj = e.target;
         var coords = actObj.calcCoords(); 
         // calcCoords returns an object of corner points like this 
         //{bl:{x:val, y:val},tl:{x:val, y:val},br:{x:val, y:val},tr:{x:val, y:val}}
         var left = coords.tl.x;
         var top = coords.tl.y;
         alert(left);
         return {left:left,top:top};
         })*/
               
         //;
         /*var moveHandler = function (evt) {
         var movingObject = evt.target;
         //movingObject.setCoords();
         let arrrr = movingObject.getBoundingRect();
         return arrrr;
         
         };*/

         //canvas.on('object:selected', moveHandler);

         var eventObj = {};
         var moveHandler = function (evt) {
         var movingObject = evt.target;
         //movingObject.setCoords();
         var arrrr = movingObject.getBoundingRect();
         //eventObj.startXObj = arrrr.
         eventObj.leftObj = arrrr.left;
         eventObj.topObj = arrrr.top;
         eventObj.widthObj = arrrr.width;
         eventObj.heightObj = arrrr.height;
         console.log(eventObj.topObj);
         //console.log(movingObject.get('left'), movingObject.get('top'));
         //return arrrr;
         };
         canvas.on('object:selected', moveHandler);
         canvas.on('object:modified', moveHandler);

         
         saveOverlace.onclick = function(){
            

               var objectScaleLeft = eventObj.leftObj;
               var objectScaleTop = eventObj.topObj;
               var objectScaleWidth = eventObj.widthObj;
               var objectScaleHeight = eventObj.heightObj;
               //console.log(objectScaleWidth);
               $.ajax({
                  url : $("#path-to-controller").data("href"),
                  type: "GET",
                  data : {
                  'startX' : objectScaleLeft,
                  'startY' : objectScaleTop,
                  'width'  : objectScaleWidth,
                  'height' : objectScaleHeight
                  }  
               });             
            }; 
