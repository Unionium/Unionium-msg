<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <style>
        #messages {
            border: 1px solid #ccc;
            height: 300px;
            overflow-y: scroll;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Chat</h1>
    <div id="messages"></div>
    <input type="text" id="message" placeholder="enter message">
    <button id="send">Send</button>

    <script>
        const ws = new WebSocket('ws://your_domain:3000');

        ws.onmessage = function(event) {
            const messagesDiv = document.getElementById('messages');
            const reader = new FileReader();
            if (event.data instanceof Blob) {
                reader.onload = function() {
                    messagesDiv.innerHTML += '<div>' + reader.result + '</div>';
                    messagesDiv.scrollTop = messagesDiv.scrollHeight; 
                };
                reader.readAsText(event.data); // Чтение Blob как текст
            } else {
                messagesDiv.innerHTML += '<div>' + event.data + '</div>';
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }
        };

        document.getElementById('send').onclick = function() {
            const messageInput = document.getElementById('message');
            const message = '<?php echo $_SESSION['username']; ?>: ' + messageInput.value;
            ws.send(message);
            messageInput.value = ''; // Очистка поля ввода
        };
    </script>
</body>
</html>
