
/**
 * Loading dots.
 * 
 * @returns {$}
 */
var getLoadingDots = function() {
    return $('<div class="browser-screen-loading-content"><div class="loading-dots dark-gray">    <i></i>    <i></i>    <i></i>    <i></i>  </div></div>');
};

$(document).ready(function() {

    $(document).on('submit', '#loginSection form, #signupSection form', function() {
        $('<div class="overlaybox"></div>').html(getLoadingDots()).fadeIn().appendTo($(this).closest('section'));
    });

    $(document).on('click', '#language-selector li', function() {
        $.ajax({
            url: '/lang',
            method: 'PUT',
            data: { 'language': $(this).attr('data-value') },
            success: function(response) {
                window.location.reload(false);
            }
        })
    });

    $(document).on('click', '[data-method]', function(e) {
        e.preventDefault();

        var confirmed = $(this).attr('data-confirm') ? confirm($(this).attr('data-confirm')) : true;

        if (confirmed) {
            $.ajax({
                url: $(this).attr('href'),
                method: $(this).attr('data-method'),
                data: {},
                success: function (response) {
                    window.location.reload(false);
                }
            });
        }
    });

    $(document).on('click', '.disclaimer-popup .footer button', function(e) {
        e.preventDefault();
        
        var currentStep = $(this).closest('.step').attr('data-step');
        var lastStep = $('.disclaimer-popup .step[data-step]:last').attr('data-step');

        if (parseInt(currentStep) !== parseInt(lastStep)) {
            $(this).closest('.step').hide(200);

            $('.disclaimer-popup .step[data-step="'+(parseInt(currentStep)+1)+'"]').show(200);
        } else {
            $.ajax({
                url: '/accept-disclaimer',
                method: 'POST',
                data: {},
                success: function (response) {
                    $('.overlay').hide(100);
                    $('.disclaimer-popup').hide(200);
                }
            });
        }
    });


});