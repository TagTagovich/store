 
   let canvas = new fabric.Canvas("canvas");
   var W = canvas.width;
   var H = canvas.height;
   
   // Initiate a Rect instance
   let rectangle = new fabric.Rect({
      width: 200,
      height: 100,
      fill: "",
      stroke: 'black',
      strokeWidth: 1,
      opacity: .3
   });
         
   function setFillRect(left, top, w, h)
   {
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

   var imgUrl = vichImg.getAttribute("data-path-vich-img");
   canvas.add(rectangle);
   canvas.setWidth(document.body.scrollWidth);
   canvas.setHeight(1000);
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
      //console.log(eventObj.leftObj, eventObj.topObj, eventObj.widthObj, eventObj.heightObj);
      //console.log(movingObject.get('left'), movingObject.get('top'));
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
      //console.log(objectScaleWidth);
      $.ajax({
         url : $("#path-to-controller").data("href"),
         type : "GET",
         data : {
         'startX' : objectScaleLeft,
         'startY' : objectScaleTop,
         'width'  : objectScaleWidth,
         'height' : objectScaleHeight
         }
      }).done(function() {
            alert('Область c координатами наложения макета:\n\r' + ' StartX - ' + objectScaleLeft + '\n\r StartY - ' + objectScaleTop + '\n\r Width - ' + objectScaleWidth + '\n\r Height - ' + objectScaleHeight + '\n\rУспешно обновлена!');
         });   
   }      
      
            
            