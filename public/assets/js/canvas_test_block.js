 
   let canvas = new fabric.Canvas("canvas");
   //var W = canvas.width;
   //var H = canvas.height;
   let objectRenderCoords = {};
   $.ajax({
      url : $("#path-to-controller-get").data("href"),
      type : "GET",
      async: false,
      }).done(function(json) {
            objectRenderCoords.left = json.left;
            objectRenderCoords.top = json.top;
            objectRenderCoords.width = json.width;
            objectRenderCoords.height = json.height;
      }); 

   console.log(objectRenderCoords.is);
   if (objectRenderCoords.is) {
      
      var shapeStartX = objectRenderCoords.left;
      var shapeStartY = objectRenderCoords.top;
      var shapeWidth = objectRenderCoords.width;
      var shapeHeight = objectRenderCoords.height;

   } else {
      var shapeStartX = 0;
      var shapeStartY = 0;
      var shapeWidth = 300;
      var shapeHeight = 200;
   
   }
   console.log(shapeHeight);
   // Initiate a Rect instance
   let rectangle = new fabric.Rect({
      left: shapeStartX,
      top: shapeStartY,
      width: shapeWidth,
      height: shapeHeight,
      fill: "",
      stroke: 'black',
      strokeWidth: 1,
      opacity: .3
   });
   
         
   /*function setFillRect(left, top, w, h)
   {
      let rectangle = new fabric.Rect({
      width: w,
      height: h,
      fill: "black",
      stroke: 'black',
      strokeWidth: 1,
      opacity: .3
      });
      
      //canvas.add(rectangle);
   } */

   var imgUrl = vichImg.getAttribute("data-path-vich-img");
   canvas.setWidth(document.body.scrollWidth);
   canvas.setHeight(1000);
   canvas.add(rectangle);
   canvas.setActiveObject(canvas.item(0));
   canvas.setBackgroundImage(imgUrl, canvas.renderAll.bind(canvas));
   
   var beginObj = canvas.getActiveObject().get('width');
   var obj = canvas.getActiveObject();
   var startX = obj.left;
   var startY = obj.top;                   
   var width = obj.width;

   var eventObj = {};
   var moveHandler = function (evt)
   {
      var movingObject = evt.target;
      //movingObject.setCoords();
      var arrCoords = movingObject.getBoundingRect();

      eventObj.leftObj = arrCoords.left;
      eventObj.topObj = arrCoords.top;
      eventObj.widthObj = arrCoords.width;
      eventObj.heightObj = arrCoords.height;
   };

   canvas.on('object:selected', moveHandler);
   canvas.on('object:modified', moveHandler);
   //canvas.on('object:scaling', scaleHandler);
   //canvas.on('object:modified', scaleHandler);

   saveOverlace.onclick = function()
   {
      var objectScaleLeft = eventObj.leftObj;
      var objectScaleTop = eventObj.topObj;
      var objectScaleWidth = eventObj.widthObj;
      var objectScaleHeight = eventObj.heightObj;

      $.ajax({
         url : $("#path-to-controller-save").data("href"),
         type : "GET",
         data : {
         'getting': false,
         'startX' : objectScaleLeft,
         'startY' : objectScaleTop,
         'width'  : objectScaleWidth,
         'height' : objectScaleHeight
         }
      }).done(function() {
            const errorCoord = 'Пусто';
            const empty = undefined
            alert(
               'Область c координатами наложения макета:\n\r' + 
               ' StartX - ' + (objectScaleLeft == undefined ? errorCoord : objectScaleLeft)  + 
               '\n\r StartY - ' + (objectScaleTop == undefined ? errorCoord : objectScaleTop) + 
               '\n\r Width - ' + (objectScaleWidth == undefined ? errorCoord : objectScaleWidth) + 
               '\n\r Height - ' + (objectScaleHeight == undefined ? errorCoord : objectScaleHeight) +
               (objectScaleWidth == undefined ?
                  '\n\rОшибка! Необходимо выбрать и установить область, затем снова попытаться сохранить.' :
                  '\n\rУспешно обновлена!'));
         });   
   }     
      
            
            