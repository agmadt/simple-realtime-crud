const app = require('express')();
const server = require('http').createServer(app);
const io = require('socket.io')(server, {
  cors: {
    origin: "http://localhost:8000",
    methods: ["GET", "POST"],
    allowEIO3: true // false by default
  }
});

server.listen(3000, () => {
  console.log('listening on port:3000');
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