$(function() {
  $('.smartwizard-example').smartWizard({
    autoAdjustHeight: false,
    backButtonSupport: false,
    enableURLhash: false
  }).find('> .toolbar .btn:not(.btn-primary)').addClass('btn-secondary');

  // Change markup for vertical wizards
  //

  $('#smartwizard-4 > .toolbar').appendTo($('#smartwizard-4 > .tab-content'));
  $('#smartwizard-5 > .toolbar').appendTo($('#smartwizard-5 > .tab-content'));
});

// With validation
$(function() {
  var $form = $('#smartwizard-6');
  var $btnFinish = $('<button class="btn-finish btn btn-primary d-none mr-2" type="button">Finish</button>');

  // Set up validator
  $form.validate({
    errorPlacement: function errorPlacement(error, element) {
      $(element).parents('.form-group').append(
        error.addClass('invalid-feedback small d-block')
      )
    },
    highlight: function(element) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function(element) {
      $(element).removeClass('is-invalid');
    },
    rules: {
      'wizard-confirm': {
        equalTo: 'input[name="wizard-password"]'
      }
    }
  });

  // Initialize wizard
  $form
    .smartWizard({
      autoAdjustHeight: false,
      backButtonSupport: false,
      enableURLhash: false,
      toolbarSettings: {
        toolbarExtraButtons: [$btnFinish]
      }
    })
    .on('leaveStep', function(e, anchorObject, currentStepIndex, nextStepIndex, stepDirection) {
      var $finishBtn = $form.find('.btn-finish');

      // stepDirection === 'forward' :- this condition allows to do the form validation
      // only on forward navigation, that makes easy navigation on backwards still do the validation when going next
      if (stepDirection === 'forward'){
        var isValid = $form.valid();

        if (!isValid) {
          anchorObject.addClass('danger');
        } else {
          anchorObject.removeClass('danger');

          if (nextStepIndex === 3) {
            $finishBtn.removeClass('d-none');
          }
        }

        return isValid;
      } else {
        $finishBtn.addClass('d-none');
      }

      return true;
    })
    .find('> .toolbar .btn:not(.btn-primary)').addClass('btn-secondary');

  // Click on finish button
  $form.find('.btn-finish').on('click', function(){
    if (!$form.valid()){ return; }

    // Submit form
    alert("Great! We're ready to submit form.");
    return false;
  });
});
