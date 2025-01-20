const form = document.querySelector(".typing-area"),
  incoming_id = form.querySelector(".incoming_id").value,
  inputField = form.querySelector(".input-field"),
  sendBtn = form.querySelector("button"),
  chatBox = document.querySelector(".chat-box"),
  fileInput = form.querySelector("input[type='file']");

let typingTimer;

// Prevent the form from submitting normally
form.onsubmit = (e) => {
  e.preventDefault();
};

// Detect input changes for typing indicator
inputField.addEventListener("keyup", () => {
  if (inputField.value.trim() !== "") {
    sendBtn.classList.add("active");
    sendTypingStatus(true); // Send "typing..." status
  } else {
    sendBtn.classList.remove("active");
    sendTypingStatus(false); // Clear "typing..." status
  }
  clearTimeout(typingTimer);
  typingTimer = setTimeout(() => sendTypingStatus(false), 3000);
});

// Send message or image when the send button is clicked
sendBtn.onclick = () => {
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "php/insert-chat.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      inputField.value = "";
      fileInput.value = "";
      sendBtn.classList.remove("active");
      scrollToBottom();
    }
  };
  const formData = new FormData(form);
  xhr.send(formData);
};

// Fetch chat messages periodically
setInterval(() => {
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "php/get-chat.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      const data = xhr.response;
      chatBox.innerHTML = data;
      if (!chatBox.classList.contains("active")) {
        scrollToBottom();
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("incoming_id=" + incoming_id);
}, 500);

// Typing status indicator
function sendTypingStatus(isTyping) {
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "php/typing-status.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send(incoming_id=${incoming_id}&typing=${isTyping});
}

// Smooth scrolling to the bottom of the chat box
function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}

// Keep the chat box scrollable when hovered
chatBox.onmouseenter = () => {
  chatBox.classList.add("active");
};

chatBox.onmouseleave = () => {
  chatBox.classList.remove("active");
};