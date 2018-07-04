window.onload = function () {
    var form = document.getElementById('message-form');
    var messageField = document.getElementById('message');
    var messagesList = document.getElementById('messages');
    var socketStatus = document.getElementById('status');
    var connectBtn = document.getElementById('connect');
    var closeBtn = document.getElementById('close');

    var socket = null;
    var userName = null;

    connectBtn.onclick = function (event) {
        event.preventDefault();

        socketStatus.innerHTML = 'Connecting...';

        socket = new WebSocket('ws://127.0.0.1:2346/');

        socket.onopen = function (event) {
            socketStatus.innerHTML = 'Connected to: ' + event.currentTarget.url;
            socketStatus.className = 'open';
        };

        socket.onerror = function (error) {
            console.log('WebSocket Error: ' + error);
        };

        socket.onmessage = function (event) {
            var message = event.data;
            messagesList.innerHTML += '<li class="received"><span>Received:</span>'+message+'</li>';
        };

        socket.onclose = function (event) {
            socketStatus.innerHTML = 'Disconnected from WebSocket';
            socketStatus.className = 'closed';
        };

        return false;
    };

    form.onsubmit = function (e) {
        e.preventDefault();

        var message = messageField.value;

        socket.send(message);

        messagesList.innerHTML += '<li class="sent"><span>' + userName + ' sent:</span>'+message+'</li>';

        return false;
    };

    closeBtn.onclick = function (event) {
        event.preventDefault();

        socket.close();

        return false;
    };
};
