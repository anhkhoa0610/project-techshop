const chatbotButton = document.getElementById("chatbot-button");
const chatbotWindow = document.getElementById("chatbot-window");
const chatbotClose = document.getElementById("chatbot-close");
const chatbotInput = document.getElementById("chatbot-input");
const chatbotSend = document.getElementById("chatbot-send");
const chatbotBody = document.querySelector(".chatbot-body");

// Mở / đóng chatbot
chatbotButton.addEventListener("click", () => {
    chatbotWindow.style.display =
        chatbotWindow.style.display === "flex" ? "none" : "flex";
    chatbotButton.style.display = "none";
});

chatbotClose.addEventListener("click", () => {
    chatbotWindow.style.display = "none";
    chatbotButton.style.display = "flex";
});

// Gửi tin nhắn
chatbotSend.addEventListener("click", sendMessage);
chatbotInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") sendMessage();
});

function sendMessage() {
    const text = chatbotInput.value.trim();
    if (!text) return;

    const userMsg = document.createElement("div");
    userMsg.classList.add("user-message");
    userMsg.textContent = text;
    chatbotBody.appendChild(userMsg);
    chatbotInput.value = "";

    // Giả lập phản hồi bot
    setTimeout(() => {
        const botMsg = document.createElement("div");
        botMsg.classList.add("bot-message");
        botMsg.textContent = "Bot: Tôi đã nhận được tin nhắn của bạn!";
        chatbotBody.appendChild(botMsg);
        chatbotBody.scrollTop = chatbotBody.scrollHeight;
    }, 500);

    chatbotBody.scrollTop = chatbotBody.scrollHeight;
}
