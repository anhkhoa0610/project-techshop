<div class="chatbot-container">
    <div id="chatbot-button"><img src="{{ asset('images/icons/chatbot.png') }}" alt=""></div>
    <div id="chatbot-window">
        <div class="chatbot-header">
            <div class="chat-avatar"><img src="{{ asset('images/icons/chatbot.png') }}" alt=""></div>
            <div class="chat-info">
                <strong>Chatbot hỗ trợ</strong>
                <span id="current-date-display"></span>
            </div>
            <button class="chat-close" id="chatbot-close">&times;</button>
        </div>
        <div class="chatbot-body">
            <div class="bot-message">Xin chào 👋! Tôi có thể giúp gì cho bạn?</div>
        </div>
        <div class="chatbot-footer">
            <input type="text" id="chatbot-input" placeholder="Nhập tin nhắn..." />
            <button id="chatbot-send"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const dateElement = document.getElementById('current-date-display');
        if (dateElement) {
            const now = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = now.toLocaleDateString('en-US', options);
            dateElement.textContent = formattedDate;
        }
    });
</script>