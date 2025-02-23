const express = require('express');
const WebSocket = require('ws');
const bodyParser = require('body-parser');
const cors = require('cors');

const app = express();
const port = 3000;

app.use(cors());
app.use(bodyParser.json());

let users = {}; // Хранение пользователей
let messages = []; // Хранение сообщений

// Авторизация
app.post('/login', (req, res) => {
    const { username, password } = req.body;
    if (username && password) {
        users[username] = password; // Простой подход, не безопасно для продакшена
        return res.json({ success: true });
    }
    return res.status(400).json({ success: false, message: 'Invalid credentials' });
});

// Создание WebSocket сервера
const wss = new WebSocket.Server({ noServer: true });

wss.on('connection', (ws) => {
    console.log('Client connected');
    
    ws.on('message', (message) => {
        messages.push(message);
        // Рассылаем сообщение всем подключенным клиентам
        wss.clients.forEach(client => {
            if (client.readyState === WebSocket.OPEN) {
                client.send(message);
            }
        });
    });

    ws.on('close', () => {
        console.log('Client disconnected');
    });
});

// Обработка HTTP запросов
const server = app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});

// Подключаем WebSocket к HTTP серверу
server.on('upgrade', (request, socket, head) => {
    wss.handleUpgrade(request, socket, head, (ws) => {
        wss.emit('connection', ws, request);
    });
});
