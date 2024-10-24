const express = require('express');
const http = require('http');
const socketIo = require('socket.io');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

io.on('connection', (socket) => {
    console.log('A user connected');

    socket.on('join', (room) => {
        socket.join(room);
        socket.to(room).emit('user-connected', socket.id);
    });

    socket.on('signal', (data) => {
        io.to(data.room).emit('signal', {
            signal: data.signal,
            from: socket.id,
        });
    });

    socket.on('disconnect', () => {
        console.log('User disconnected');
    });
});

server.listen(3000, () => {
    console.log('Signaling server running on http://localhost:3000');
});
