require('dotenv').config()
const app = require('express')();
const server = require('http').createServer(app);
const io = require('socket.io')(server, {
  cors: {
    origin: "*",
    methods: ["GET", "POST"],
    allowEIO3: true // false by default
  }
});

const serverPort = process.env.PORT || 3000;

server.listen(serverPort, () => {
  console.log('listening on port:' + serverPort);
});

io.on('connection', (socket) => {
    console.log('a user connected');
    
    socket.on("teams:added", (data) => {
        socket.broadcast.emit('teams:added', data);
    });

    socket.on('disconnect', () => {
        console.log('user disconnected');
    });
});