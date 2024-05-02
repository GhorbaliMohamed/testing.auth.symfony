document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    const chatInput = document.getElementById('chat-input');
    const chatSendButton = document.getElementById('chat-send');

    // Fetch and display the initial chat messages


    // Set up the EventSource connection to listen for new messages
    const eventSource = new EventSource('/chat/publish');
    eventSource.addEventListener('message', function(event) {
        const message = JSON.parse(event.data);
        const messageElement = document.createElement('div');
        messageElement.textContent = message.message;
        chatMessages.appendChild(messageElement);
    });

    // Handle the "Send" button click event
    chatSendButton.addEventListener('click', function() {
        const message = chatInput.value;
        fetch('/chat/publish', {
            method: 'POST',
            body: new URLSearchParams({ message: message })
        })
        .then(() => {
            chatInput.value = '';
        });
    });
});