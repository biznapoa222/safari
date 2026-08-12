import './bootstrap';
import './row-actions';
import './hero-video';

document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        window.lucide.createIcons({ attrs: { 'stroke-width': 1.8 } });
    }

    document.querySelectorAll('.public-body main h1, .public-body main h2, .public-body .reference-hero-copy h1, .public-body .dream-planner h2, .public-body .country-mega h2, .public-body .country-mega h3, .public-body .public-footer h2').forEach((heading) => {
        if (heading.childElementCount || heading.classList.contains('mixed-type-heading')) return;
        const words = heading.textContent.trim().split(/\s+/);
        if (words.length < 2) return;
        const accent = words.pop();
        heading.textContent = `${words.join(' ')} `;
        const script = document.createElement('span');
        script.className = 'heading-script';
        script.textContent = accent;
        heading.appendChild(script);
        heading.classList.add('mixed-type-heading');
    });

    document.querySelectorAll('.nav-toggle').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const group = toggle.closest('.nav-group');
            const isOpen = group.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });

    document.querySelectorAll('[data-language-trigger]').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            event.stopPropagation();
            const switcher = trigger.closest('.language-switcher');
            document.querySelectorAll('.language-switcher.is-open').forEach((openSwitcher) => {
                if (openSwitcher !== switcher) openSwitcher.classList.remove('is-open');
            });
            switcher.classList.toggle('is-open');
            trigger.setAttribute('aria-expanded', switcher.classList.contains('is-open') ? 'true' : 'false');
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.language-switcher.is-open').forEach((switcher) => switcher.classList.remove('is-open'));
    });

    document.querySelectorAll('[data-sidebar-toggle]').forEach((button) => {
        button.addEventListener('click', () => document.body.classList.toggle('sidebar-open'));
    });

    document.querySelectorAll('.edit-popover').forEach((popover) => {
        popover.addEventListener('toggle', () => {
            if (!popover.open) return;
            document.querySelectorAll('.edit-popover[open]').forEach((other) => {
                if (other !== popover) other.removeAttribute('open');
            });
            document.body.classList.add('popover-open');
        });
        popover.querySelectorAll('.popover-close').forEach((button) => {
            button.addEventListener('click', () => popover.removeAttribute('open'));
        });
    });

    document.addEventListener('toggle', (event) => {
        if (event.target.matches?.('.edit-popover') && !document.querySelector('.edit-popover[open]')) {
            document.body.classList.remove('popover-open');
        }
    }, true);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            document.querySelectorAll('.edit-popover[open]').forEach((popover) => popover.removeAttribute('open'));
        }
    });

    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = button.closest('div').querySelector('input');
            input.type = input.type === 'password' ? 'text' : 'password';
            button.innerHTML = input.type === 'password'
                ? '<i data-lucide="eye"></i>'
                : '<i data-lucide="eye-off"></i>';
            if (window.lucide) window.lucide.createIcons({ attrs: { 'stroke-width': 1.8 } });
        });
    });

    document.querySelectorAll('[data-user-form-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const form = document.querySelector('[data-user-form]');
            if (!form) return;
            form.hidden = !form.hidden;
            if (!form.hidden) form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    const publicMenu = document.querySelector('[data-public-menu]');
    if (publicMenu) {
        publicMenu.addEventListener('click', () => {
            const isOpen = document.body.classList.toggle('public-menu-open');
            publicMenu.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.querySelectorAll('.public-nav a').forEach((link) => {
            link.addEventListener('click', () => {
                document.body.classList.remove('public-menu-open');
                publicMenu.setAttribute('aria-expanded', 'false');
            });
        });
    }

    const publicHeader = document.querySelector('[data-public-header]');
    if (publicHeader) {
        const updateHeader = () => publicHeader.classList.toggle('is-scrolled', window.scrollY > 40);
        window.addEventListener('scroll', updateHeader, { passive: true });
        updateHeader();
    }

    const tripPlanner = document.querySelector('[data-trip-planner]');
    if (tripPlanner) {
        const openPlanner = () => {
            if (typeof tripPlanner.showModal === 'function') tripPlanner.showModal();
            else tripPlanner.setAttribute('open', '');
            document.body.classList.add('trip-planner-open');
            window.setTimeout(() => tripPlanner.querySelector('select, input')?.focus(), 120);
        };
        const closePlanner = () => {
            if (typeof tripPlanner.close === 'function') tripPlanner.close();
            else tripPlanner.removeAttribute('open');
            document.body.classList.remove('trip-planner-open');
        };
        document.querySelectorAll('[data-trip-planner-open]').forEach((button) => button.addEventListener('click', openPlanner));
        tripPlanner.querySelectorAll('[data-trip-planner-close]').forEach((button) => button.addEventListener('click', closePlanner));
        tripPlanner.addEventListener('click', (event) => { if (event.target === tripPlanner) closePlanner(); });
        tripPlanner.addEventListener('close', () => document.body.classList.remove('trip-planner-open'));
    }

    const visitorChat = document.querySelector('[data-visitor-chat]');
    if (visitorChat) {
        const panel = visitorChat.querySelector('.visitor-chat-panel');
        const openButton = visitorChat.querySelector('.visitor-chat-button');
        const invite = visitorChat.querySelector('[data-chat-invite]');
        const inviteClose = visitorChat.querySelector('[data-chat-invite-close]');
        const closeButton = visitorChat.querySelector('[data-chat-close]');
        const form = visitorChat.querySelector('[data-chat-form]');
        const messagesBox = visitorChat.querySelector('[data-chat-messages]');
        const identity = visitorChat.querySelector('[data-chat-identity]');
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
        let token = localStorage.getItem('shishi_chat_token');
        let poller;

        const renderMessages = (messages = []) => {
            messagesBox.innerHTML = '';
            messages.forEach((message) => {
                const bubble = document.createElement('div');
                bubble.className = `visitor-message ${message.sender === 'admin' ? 'agent' : 'visitor'}`;
                bubble.textContent = message.body;
                messagesBox.appendChild(bubble);
            });
            messagesBox.scrollTop = messagesBox.scrollHeight;
        };
        const loadMessages = async () => {
            if (!token) return;
            const response = await fetch(`${visitorChat.dataset.baseUrl}/${token}`, { headers: { Accept: 'application/json' } });
            if (!response.ok) { localStorage.removeItem('shishi_chat_token'); token = null; return; }
            const data = await response.json(); renderMessages(data.messages); identity.hidden = true;
        };
        const startPolling = () => { clearInterval(poller); if (token) poller = setInterval(loadMessages, 5000); };
        openButton.addEventListener('click', () => { panel.hidden = false; invite.hidden = true; loadMessages(); startPolling(); });
        closeButton.addEventListener('click', () => { panel.hidden = true; invite.hidden = false; clearInterval(poller); });
        inviteClose?.addEventListener('click', (event) => {
            event.stopPropagation();
            invite.classList.add('is-compact');
            inviteClose.hidden = true;
        });
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const payload = Object.fromEntries(new FormData(form));
            if (!payload.message?.trim()) return;
            const endpoint = token ? `${visitorChat.dataset.baseUrl}/${token}` : visitorChat.dataset.startUrl;
            const response = await fetch(endpoint, { method: 'POST', headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify(payload) });
            if (!response.ok) return;
            const data = await response.json();
            if (data.token) { token = data.token; localStorage.setItem('shishi_chat_token', token); identity.hidden = true; }
            renderMessages(data.messages); form.querySelector('textarea').value = ''; startPolling();
        });
        if (token) identity.hidden = true;
    }

    const animatedSections = document.querySelectorAll('.public-body section > *, .destination-card, .experience-card, .safari-card, .blog-card, .itinerary-row, .home-golf-card, .course-card, .golf-package-card, .principle-grid article, .beyond-grid article, .responsible-section article');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        animatedSections.forEach((element, index) => {
            element.classList.add('reveal-on-scroll');
            element.style.setProperty('--reveal-delay', `${Math.min(index % 4, 3) * 90}ms`);
            observer.observe(element);
        });
    }

    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll('[data-tilt-card]').forEach((card) => {
            card.addEventListener('pointermove', (event) => {
                const box = card.getBoundingClientRect();
                const x = (event.clientX - box.left) / box.width;
                const y = (event.clientY - box.top) / box.height;
                card.style.setProperty('--pointer-x', `${x * 100}%`);
                card.style.setProperty('--pointer-y', `${y * 100}%`);
                card.style.transform = `perspective(900px) rotateX(${(0.5 - y) * 3}deg) rotateY(${(x - 0.5) * 3}deg) translateY(-5px)`;
            });
            card.addEventListener('pointerleave', () => { card.style.transform = ''; });
        });
    }
});
