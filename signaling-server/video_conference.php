<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Conference</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <style>
        body { display: flex; flex-direction: column; align-items: center; }
        video { width: 300px; height: auto; margin: 10px; }
    </style>
</head>
<body>
    <h1>Video Conference</h1>
    <div id="video-container"></div>
    <button id="start" class="btn btn-primary">Start Conference</button>

    <script src="/socket.io/socket.io.js"></script>
    <script>
        const socket = io('http://localhost:3000');
        const videoContainer = document.getElementById('video-container');
        const startButton = document.getElementById('start');
        let localStream;
        const peers = {};

        startButton.onclick = async () => {
            const room = prompt('Enter room name:');
            socket.emit('join', room);

            localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            const localVideo = document.createElement('video');
            localVideo.srcObject = localStream;
            localVideo.autoplay = true;
            videoContainer.appendChild(localVideo);

            socket.on('user-connected', (userId) => {
                createPeerConnection(userId, room);
            });
        };

        function createPeerConnection(userId, room) {
            const peerConnection = new RTCPeerConnection();
            peers[userId] = peerConnection;

            localStream.getTracks().forEach(track => {
                peerConnection.addTrack(track, localStream);
            });

            peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    socket.emit('signal', { signal: event.candidate, room });
                }
            };

            peerConnection.ontrack = (event) => {
                const remoteVideo = document.createElement('video');
                remoteVideo.srcObject = event.streams[0];
                remoteVideo.autoplay = true;
                videoContainer.appendChild(remoteVideo);
            };

            socket.on('signal', async (data) => {
                if (data.from === userId) {
                    await peerConnection.addIceCandidate(data.signal);
                }
            });

            peerConnection.createOffer().then((offer) => {
                return peerConnection.setLocalDescription(offer);
            }).then(() => {
                socket.emit('signal', { signal: peerConnection.localDescription, room });
            });
        }
    </script>
</body>
</html>
