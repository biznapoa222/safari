(function () {
    function buildDropdown(container) {
        if (container.dataset.rowActionsReady === '1') return;
        container.dataset.rowActionsReady = '1';

        var items = Array.from(container.children).filter(function (el) {
            return el.tagName === 'A' || el.tagName === 'BUTTON' || el.tagName === 'FORM';
        });
        if (items.length === 0) return;

        var dropdown = document.createElement('div');
        dropdown.className = 'row-actions-dropdown';

        var trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'row-actions-trigger';
        trigger.setAttribute('aria-label', 'Actions');
        trigger.innerHTML = '<i data-lucide="more-horizontal"></i>';
        container.appendChild(trigger);

        var menu = document.createElement('div');
        menu.className = 'row-actions-menu';
        dropdown.appendChild(menu);

        items.forEach(function (item) {
            container.removeChild(item);
            if (item.tagName === 'FORM') {
                var submit = item.querySelector('button, [type="submit"]');
                var label = (submit ? submit.textContent.trim() : 'Action').replace(/\s+/g, ' ');
                if (!label) label = item.querySelector('[title]')?.getAttribute('title') || 'Action';
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'row-actions-item row-actions-item--form';
                btn.innerHTML = '<i data-lucide="trash-2"></i><span>' + escape(label) + '</span>';
                btn.addEventListener('click', function () {
                    if (confirm('Confirm this action?')) item.submit();
                });
                menu.appendChild(btn);
            } else {
                var a = item;
                var label = (a.getAttribute('title') || a.textContent.trim() || a.getAttribute('aria-label') || 'Action').replace(/\s+/g, ' ');
                var icon = a.querySelector('[data-lucide]')?.outerHTML || '';
                var link = document.createElement('a');
                link.href = a.href || '#';
                link.className = 'row-actions-item';
                link.innerHTML = (icon || '<i data-lucide="circle"></i>') + '<span>' + escape(label) + '</span>';
                menu.appendChild(link);
            }
        });

        container.appendChild(dropdown);

        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            closeAll();
            dropdown.classList.toggle('is-open');
        });
    }

    function escape(s) {
        return String(s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function closeAll() {
        document.querySelectorAll('.row-actions-dropdown.is-open').forEach(function (d) {
            d.classList.remove('is-open');
        });
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.row-actions-dropdown')) closeAll();
    });

    function initAll() {
        document.querySelectorAll('.ops-actions').forEach(buildDropdown);
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    // Re-run when content is updated via AJAX
    document.addEventListener('row-actions:refresh', initAll);
    document.addEventListener('search:refresh', initAll);
})();
