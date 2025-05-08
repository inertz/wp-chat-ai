/**
 * WordPress AI Chat - Frontend JavaScript
 */
(function($) {
    'use strict';
    
    // Initialize the chat functionality
    function initAIChat() {
        const containers = document.querySelectorAll('.wp-ai-chat-container');
        const buttons = document.querySelectorAll('.wp-ai-chat-button');
        const closeButtons = document.querySelectorAll('.wp-ai-chat-close');
        const sendButtons = document.querySelectorAll('.wp-ai-chat-send');
        const inputs = document.querySelectorAll('.wp-ai-chat-input');
        
        // Initialize chat buttons
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const container = this.closest('.wp-ai-chat-button-container');
                container.classList.toggle('open');
                
                // Scroll messages to bottom
                const messagesContainer = container.querySelector('.wp-ai-chat-messages');
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
                
                // Focus the input
                const input = container.querySelector('.wp-ai-chat-input');
                if (input) {
                    setTimeout(() => {
                        input.focus();
                    }, 300);
                }
            });
        });
        
        // Initialize close buttons
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const buttonContainer = this.closest('.wp-ai-chat-button-container');
                if (buttonContainer) {
                    buttonContainer.classList.remove('open');
                }
            });
        });
        
        // Initialize send buttons
        sendButtons.forEach(button => {
            button.addEventListener('click', function() {
                const container = this.closest('.wp-ai-chat-container');
                const input = container.querySelector('.wp-ai-chat-input');
                sendMessage(container, input);
            });
        });
        
        // Initialize inputs
        inputs.forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const container = this.closest('.wp-ai-chat-container');
                    sendMessage(container, this);
                }
            });
        });
        
        // Apply primary color to elements
        containers.forEach(container => {
            const primaryColor = container.dataset.primaryColor || '#3B82F6';
            const header = container.querySelector('.wp-ai-chat-header');
            const sendButton = container.querySelector('.wp-ai-chat-send');
            
            if (header) header.style.background = primaryColor;
            if (sendButton) sendButton.style.background = primaryColor;
        });
    }
    
    // Send message function
    function sendMessage(container, input) {
        const message = input.value.trim();
        if (!message) return;
        
        const messagesContainer = container.querySelector('.wp-ai-chat-messages');
        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        // Add user message
        appendMessage(messagesContainer, message, 'user', time);
        
        // Clear input
        input.value = '';
        
        // Add loading indicator
        const loadingId = 'loading-' + Date.now();
        appendLoadingIndicator(messagesContainer, loadingId);
        
        // Send to server
        $.ajax({
            url: wpAiChat.ajaxUrl,
            type: 'POST',
            data: {
                action: 'wp_ai_chat_send_message',
                message: message,
                nonce: wpAiChat.nonce
            },
            success: function(response) {
                // Remove loading indicator
                removeLoadingIndicator(messagesContainer, loadingId);
                
                if (response.success) {
                    // Add AI response
                    appendMessage(messagesContainer, response.data.response, 'ai', time);
                } else {
                    // Add error message
                    appendMessage(
                        messagesContainer, 
                        'Sorry, there was an error processing your request.', 
                        'ai', 
                        time
                    );
                }
            },
            error: function() {
                // Remove loading indicator
                removeLoadingIndicator(messagesContainer, loadingId);
                
                // Add error message
                appendMessage(
                    messagesContainer, 
                    'Sorry, there was an error connecting to the server.', 
                    'ai', 
                    time
                );
            }
        });
    }
    
    // Append message to the chat
    function appendMessage(container, message, sender, time) {
        const messageEl = document.createElement('div');
        messageEl.className = `wp-ai-chat-message wp-ai-chat-message-${sender}`;
        
        if (container.closest('.wp-ai-chat-container').dataset.animate === 'true') {
            messageEl.style.opacity = '0';
            messageEl.style.transform = 'translateY(10px)';
        }
        
        messageEl.innerHTML = `
            <div class="wp-ai-chat-message-content">${message}</div>
            <div class="wp-ai-chat-message-time">${time}</div>
        `;
        
        container.appendChild(messageEl);
        
        // Scroll to bottom
        container.scrollTop = container.scrollHeight;
        
        // Animate in
        if (container.closest('.wp-ai-chat-container').dataset.animate === 'true') {
            setTimeout(() => {
                messageEl.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                messageEl.style.opacity = '1';
                messageEl.style.transform = 'translateY(0)';
            }, 10);
        }
    }
    
    // Append loading indicator
    function appendLoadingIndicator(container, id) {
        const loadingEl = document.createElement('div');
        loadingEl.id = id;
        loadingEl.className = 'wp-ai-chat-loading';
        loadingEl.innerHTML = `
            <div class="wp-ai-chat-loading-dots">
                <span></span><span></span><span></span>
            </div>
        `;
        
        container.appendChild(loadingEl);
        
        // Scroll to bottom
        container.scrollTop = container.scrollHeight;
    }
    
    // Remove loading indicator
    function removeLoadingIndicator(container, id) {
        const loadingEl = document.getElementById(id);
        if (loadingEl) {
            loadingEl.remove();
        }
    }
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        initAIChat();
    });
    
})(jQuery);