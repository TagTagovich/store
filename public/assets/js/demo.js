// Auto update layout
(function() {
  window.layoutHelpers.setAutoUpdate(true);
})();

// Collapse menu
(function() {
  if ($('#layout-sidenav').hasClass('sidenav-horizontal') || window.layoutHelpers.isSmallScreen()) {
    return;
  }

  try {
    window.layoutHelpers.setCollapsed(
      localStorage.getItem('layoutCollapsed') === 'true',
      false
    );
  } catch (e) {}
})();

$(function() {
  // Initialize sidenav
  $('#layout-sidenav').each(function() {
    new SideNav(this, {
      orientation: $(this).hasClass('sidenav-horizontal') ? 'horizontal' : 'vertical'
    });
  });

  // Initialize sidenav togglers
  $('body').on('click', '.layout-sidenav-toggle', function(e) {
    e.preventDefault();
    window.layoutHelpers.toggleCollapsed();
    if (!window.layoutHelpers.isSmallScreen()) {
      try { localStorage.setItem('layoutCollapsed', String(window.layoutHelpers.isCollapsed())); } catch (e) {}
    }
  });

  if ($('html').attr('dir') === 'rtl') {
    $('#layout-navbar .dropdown-menu').toggleClass('dropdown-menu-right');
  }
  $('.fa-times').each(function(){
	$(this).parent().click(function(){
		var url = $(this).attr('href');
		Swal.fire({
          title: 'Вы уверены?', 
          text: 'Подтвердите, что действительно хотите удалить запись!',
          icon: 'error',
          showCancelButton: true,
          customClass: {
            confirmButton: 'btn btn-danger btn-lg',
            cancelButton: 'btn btn-default btn-lg'
          }
        }).then(function(result){
		    if (result.value) {
		        document.location.href = url;  
		    }
		});
		return false;
	}); 
  });
  
    
$('.add-entry').click(function(){
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

$(document).on('change', '#trigger_event', function(){
	if($(this).val() == 'application_created'){
		$('#trigger_action option[value="change_status"]').show();
	}
	if($(this).val() == 'application_status_changed'){
		$('#trigger_action option[value="change_status"]').show();
	}
	if($(this).val() == 'application_responsible'){
		$('#trigger_action option[value="change_status"]').show();
	}
	if($(this).val() == 'application_processed'){
		$('#trigger_action option[value="change_status"]').show();
	}
	if($(this).val() == 'all_report_processed'){
		$('#trigger_action option[value="change_status"]').show();
	}
	if($(this).val() == 'report_processed'){
		$('#trigger_action option[value="change_status"]').show();
	}
	if($(this).val() == 'user_created'){
		$('#trigger_action option[value="change_status"]').hide();
		$('#trigger_action').val('send_email');
		$('#trigger_action').change();
	}
	if($(this).val() == 'user_changes'){
		$('#trigger_action option[value="change_status"]').hide();
		$('#trigger_action').val('send_email');
		$('#trigger_action').change();
	}
});

$(document).on('change', '#trigger_action', function(){
	if($(this).val() == 'send_email'){
		$('#trigger_status').parent().hide();
		$('#trigger_template').parent().show();
	}
	if($(this).val() == 'change_status'){
		$('#trigger_status').parent().show();
		$('#trigger_template').parent().hide();
	}
});
$('#trigger_event').change();
$('#trigger_action').change();
$('a.photo').each(function(){
	const $link = $(this);
	$link.click(function(){
		return false;
	});
	const img = new Image();
	img.onload = function() {
	    if(img.width > $link.parents('.swiper-container').width() || this.height > $link.parents('.swiper-container').height()){
	        $link.zoom();
	    }
	}
	img.src = $link.attr('href');
});
$('.datepicker').datepicker({
	format: 'mm.dd.yyyy'
});