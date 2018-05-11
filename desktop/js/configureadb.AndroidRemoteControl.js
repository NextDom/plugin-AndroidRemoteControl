$(document).ready(function () {
    var navListItems = $('div.setup-panel div a'),
    allWells = $('.setup-content'),
    allNextBtn = $('.nextBtn'),
    allReturnBtn = $('.returnBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
        $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
        curStepBtn = curStep.attr("id"),
        nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
        curInputs = curStep.find("input[type='text'],input[type='url']"),
        isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
        nextStepWizard.removeAttr('disabled').trigger('click');
    });

    allReturnBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
        curStepBtn = curStep.attr("id"),
        returnStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a"),
        curInputs = curStep.find("input[type='text'],input[type='url']"),
        isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }
    });
    $('div.setup-panel div a.btn-primary').trigger('click');
});
$("#toStep2").click(function(){
    document.getElementById('ip_address').value = document.getElementById('ip_address_found').value;
});

$("#toStep5").click(function(){
    var deviceIp = document.getElementById('ip_address').value;
    $.post({
        url: 'plugins/AndroidRemoteControl/core/ajax/AndroidRemoteControl.ajax.php',
        data: {
            action: 'connect',
            params: deviceIp
        },
        dataType: 'json',
        success: function (data, status) {
            // Test si l'appel a échoué
            if (data.state !== 'ok' || status !== 'success') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
            }
        },
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        }
    });
});

$("#closeConfigureAdb").click(function(){
    $('#md_modal').dialog( "close" );
});
