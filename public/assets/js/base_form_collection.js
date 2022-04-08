
$(document).ready(function(){$('.add-entry').click(function(){
    var $collection = $(this).prev('.collection');
 $collection.show();
    var index = $collection.data('index') || 0;
    var prototype = $collection.data('prototype');
    var $newForm = $(prototype.replace(/__name__/g, index));
    $newForm.find('legend').remove();
    
    $collection.append($newForm);

    $collection.data('index', index + 1);
    return false;
    });
});

$(document).on('click', '.remove-collection-entry', function(){
    var $row = $(this).parents('.item');
    var $container = $row.parents('.collection');
    if($(this).attr('href') != '#'){
        $.ajax({
            url : $(this).attr('href')
        }).done(function(){
            $row.remove();
            if(!$container.find('.item').length) $container.hide();
        });
    }else{
        $row.remove();
        if(!$container.find('.item').length) $container.hide();
    }
    return false;
});
