<div class="visitor-chat" data-visitor-chat data-start-url="<?php echo e(route('chat.start')); ?>" data-base-url="<?php echo e(url('/chat')); ?>">
    <div class="chat-invite" data-chat-invite>
        <button class="visitor-chat-button" type="button" aria-label="Chat with Shishi Footsteps">
            <span class="chat-mascot" aria-hidden="true"><img src="<?php echo e(asset('images/brand/shishi-paw-white.png')); ?>" alt=""></span>
            <span class="chat-invite-copy"><strong>LET'S CHAT</strong><small><i></i>Online now</small></span>
            <span class="chat-compact-icon"><i data-lucide="message-circle"></i></span>
        </button>
        <button class="chat-invite-close" type="button" data-chat-invite-close aria-label="Minimize chat invitation"><i data-lucide="x"></i></button>
    </div>
    <section class="visitor-chat-panel" hidden><header><span class="chat-agent-mark">SF</span><div><strong>Shishi Footsteps</strong><small><i></i>Safari specialist online</small></div><button type="button" data-chat-close><i data-lucide="x"></i></button></header><div class="visitor-chat-messages" data-chat-messages><div class="visitor-message agent">Hello! Tell us where in Africa you dream of travelling, and we’ll help you shape the journey.</div></div><form data-chat-form><div class="chat-identity" data-chat-identity><input name="name" placeholder="Your name"><input type="email" name="email" placeholder="Email address"></div><div class="chat-compose"><textarea name="message" placeholder="Type your message…" required></textarea><button aria-label="Send message"><i data-lucide="send"></i></button></div></form><small class="chat-privacy">Replies appear here. Your conversation stays on this device.</small></section>
</div>
<?php /**PATH C:\shishifootsteps\safari\resources\views/components/public/chat-widget.blade.php ENDPATH**/ ?>