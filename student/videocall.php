<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Call</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .container-fluid {
            height: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }
        .video-section {
            flex: 2;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        video {
            width: 90%;
            height: auto;
            background-color: black;
        }
        .controls {
            position: absolute;
            bottom: 20px;
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .controls button {
            margin: 0 10px;
        }
        .chat-section {
            flex: 1;
            background-color: #fafafa;
            display: flex;
            flex-direction: column;
            border-left: 1px solid #ddd;
        }
        .chat-box {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
            background-color: #f7f7f7;
            border-bottom: 1px solid #ddd;
        }
        .chat-message {
            margin-bottom: 10px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border-radius: 20px;
            max-width: 70%;
            word-wrap: break-word;
            align-self: flex-start;
        }
        .chat-message.user {
            background-color: #6c757d;
            align-self: flex-end;
        }
        .chat-input {
            display: flex;
            padding: 10px;
            background-color: #fff;
            border-top: 1px solid #ddd;
        }
        .chat-input input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            outline: none;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        .chat-input button {
            margin-left: 10px;
            padding: 10px 20px;
            border-radius: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .chat-input button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <!-- Video Section -->
    <div class="video-section">
        <video id="videoElement" autoplay></video>
        <div class="controls">
            <button id="cameraToggle" class="btn btn-danger">Camera Off</button>
            <button id="micToggle" class="btn btn-warning">Mic Off</button>
            <button id="endCall" class="btn btn-dark">End Call</button> <!-- End Call Button -->
        </div>
    </div>

    <!-- Chat Section -->
    <div class="chat-section">
        <div class="chat-box" id="chatBox">
            <!-- Chat messages will appear here -->
        </div>
        <div class="chat-input">
            <input type="text" id="chatMessage" placeholder="Type a message...">
            <button id="sendMessage">Send</button>
        </div>
    </div>
</div>

<script>
    // Get elements
    const videoElement = document.getElementById('videoElement');
    const cameraToggle = document.getElementById('cameraToggle');
    const micToggle = document.getElementById('micToggle');
    const endCall = document.getElementById('endCall');
    const chatBox = document.getElementById('chatBox');
    const chatMessage = document.getElementById('chatMessage');
    const sendMessage = document.getElementById('sendMessage');

    let cameraOn = true;
    let micOn = true;

    // Start video stream
    async function startVideo() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            videoElement.srcObject = stream;
            window.localStream = stream;
        } catch (error) {
            console.error('Error accessing camera or microphone:', error);
        }
    }
    startVideo();

    // Toggle Camera
    cameraToggle.addEventListener('click', () => {
        cameraOn = !cameraOn;
        const videoTracks = window.localStream.getVideoTracks();
        videoTracks[0].enabled = cameraOn;
        cameraToggle.textContent = cameraOn ? 'Camera Off' : 'Camera On';
    });

    // Toggle Mic
    micToggle.addEventListener('click', () => {
        micOn = !micOn;
        const audioTracks = window.localStream.getAudioTracks();
        audioTracks[0].enabled = micOn;
        micToggle.textContent = micOn ? 'Mic Off' : 'Mic On';
    });

    // End Call
    endCall.addEventListener('click', () => {
        // Stop all media tracks
        window.localStream.getTracks().forEach(track => track.stop());

        // Display end call message (or redirect, as needed)
        videoElement.style.display = 'none'; // Hide video
        document.querySelector('.video-section').innerHTML = '<h2>Call Ended</h2>';
    });

    // Send chat message
    sendMessage.addEventListener('click', () => {
        const message = chatMessage.value;
        if (message.trim()) {
            const messageElement = document.createElement('div');
            messageElement.textContent = message;
            messageElement.classList.add('chat-message', 'user');
            chatBox.appendChild(messageElement);
            chatMessage.value = ''; // Clear input
            chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
        }
    });

    // Allow Enter key to send chat message
    chatMessage.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            sendMessage.click();
        }
    });
</script>

</body>
</html>
