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


async function sendMessage() {
    const text = chatbotInput.value.trim();
    if (!text) return;

    // Hiển thị tin nhắn người dùng
    const userMsg = document.createElement("div");
    userMsg.classList.add("user-message");
    userMsg.textContent = text;
    chatbotBody.appendChild(userMsg);
    chatbotInput.value = "";
    chatbotBody.scrollTop = chatbotBody.scrollHeight;

    // Hiển thị bot đang gõ...
    const typingMsg = document.createElement("div");
    typingMsg.classList.add("bot-message");
    typingMsg.textContent = "Đang xử lý...";
    chatbotBody.appendChild(typingMsg);
    chatbotBody.scrollTop = chatbotBody.scrollHeight;

    try {
        // Gọi API Laravel
        console.log("Sending message to /api/chat:", text);
        const response = await fetch("/api/chat", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ message: text }),
        });

        const data = await response.json();

        // Xóa "Đang xử lý..."
        chatbotBody.removeChild(typingMsg);

        // Thêm tin nhắn bot
        const botMsg = document.createElement("div");
        botMsg.classList.add("bot-message");
        botMsg.textContent = data.reply || "Xin lỗi, tôi không thể hiểu yêu cầu của bạn.";
        chatbotBody.appendChild(botMsg);
        chatbotBody.scrollTop = chatbotBody.scrollHeight;
    } catch (error) {
        console.error(error);
        typingMsg.textContent = "Lỗi kết nối máy chủ.";
    }
}
