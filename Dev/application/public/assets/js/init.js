var client_notify = function(message, type, size)
{
    if(type === 'error') {
        type = 'danger';
    }
    if(typeof size === 'undefined') {
        size = 4;
    }

    $.notify({
        message: message
    },{
        type: type,
        position: 'fixed',
        allow_dismiss: true,
        placement: {
            from: 'bottom',
            align: 'right'
        },
        animate: {
            enter: 'animated fadeInRight',
            exit: 'animated fadeOutRight'
        },
        delay: 5000,
        template: '<div data-notify="container" class="event-notification col-xs-11 col-sm-' + size + ' alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message" style="font-family:monospace">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>'
    });
};

var client_load = function(path)
{
    $('#content').load(path);
    return false;
};

var setup_page = function() {
    if($('.site-content').length > 0) {
        if($('.site-content-right').length > 0) {
            $('.site-content-right').css('min-height', '0');
        }
        if($('.site-content-left').length > 0) {
            $('.site-content-left').css('min-height', '0');
        }
        if($('.site-content-main').length > 0) {
            $('.site-content-main').css('min-height', ($(window).height()-160) + 'px');
            if($('.site-content-left').length > 0) {
                $('.site-content-main').width(($('.site-content').width() - $('.site-content-left').width())-10);
            }
            if($('.site-content-right').length > 0) {
                $('.site-content-main').width($('.site-content').width() - $('.site-content-right').width());
            }
        }
        if($('.site-content-right').length > 0) {
            $('.site-content-right').css('min-height', $('.site-content').height() + 'px');
        }
        if($('.site-content-left').length > 0) {
            //$('.site-content-left').height($('.site-content').height());
            $('.site-content-left').css('min-height', $('.site-content').height() + 'px');
        }
    }
}

$(document).ready(function() {

    $( document ).ajaxComplete(function( event, xhr, settings ) {
        try {
            var resp = JSON.parse(xhr.responseText);
            if (typeof resp.error !== 'undefined' && resp.error === 'session_expired') {
                window.location = '/login/?err=' + resp.code;
            }
        }
        catch(err) {

        }
    });

    var enforce_desktop = function(min_width)
    {
        if($(window).width() < min_width) {
            $('body').hide();
        } else {
            $('body').show();
        }
    };

    $(window).resize(function() {
        setup_page();
        setTimeout(function() {
            setup_page();
        });
        enforce_desktop(1200);
    });
    enforce_desktop(1200);
    $(window).on('sing-app:content-resize', function() {
        setup_page();
        setTimeout(function() {
            setup_page();
        });
    });

});