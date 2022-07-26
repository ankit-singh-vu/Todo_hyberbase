var socket_server, socket = {};

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

(function() {

    socket_server = io.connect('https://<?=$domain?>:8443');
    socket_server.on('access_token', function() {
        socket_server.emit('access_token', { key: getCookie('access_token') });
    });
    socket_server.on('terminate', function() {
        window.location = '/system/logout';
    })
    socket_server.on('demo', function(params) {
        console.log(params);
    });
    socket.on = function(event, callback) {
        socket_server.on(event, callback);
    };
    socket.emit = function(event, parameters) {
        if(typeof parameters === 'undefined') {
            parameters = {};
        }
        parameters['key'] = getCookie('access_token');
        socket_server.emit(event, parameters);
    };

    socket.on('update_element', function(params) {
        var elem = typeof params.elemId !== 'undefined'?$('#' + params.elemId):$('.' + params.elemClass)
        if(elem.length > 0) {
            elem.load(elem.attr('origin'));
        }
        return true;
    });

    socket.on('update_content_element', function(params) {
        var current_source = $('#content').attr('source');
        console.log(params);
        console.log(current_source);
        console.log(current_source.indexOf(params.source));
        if(current_source.indexOf(params.source) !== -1) {
            client_load(current_source);
        }
    });

})();