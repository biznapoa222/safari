<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['placeholder' => 'Search itineraries...', 'endpoint' => route('public.search.itineraries'), 'showAllOnFocus' => true, 'extra' => '', 'name' => 'q']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['placeholder' => 'Search itineraries...', 'endpoint' => route('public.search.itineraries'), 'showAllOnFocus' => true, 'extra' => '', 'name' => 'q']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="search-dropdown" data-endpoint="<?php echo e($endpoint); ?>" data-show-all="<?php echo e($showAllOnFocus ? '1' : '0'); ?>">
    <label class="ops-search" style="flex:1;min-width:240px">
        <i data-lucide="search"></i>
        <input
            type="text"
            class="search-dropdown-input"
            placeholder="<?php echo e($placeholder); ?>"
            autocomplete="off"
            <?php echo e($name ? 'name='.$name : ''); ?>

        />
    </label>
    <?php echo e($extra); ?>

    <div class="search-dropdown-results hidden" role="listbox" aria-label="Search results" style="position:absolute;left:0;right:0;top:100%;margin-top:6px;background:#fff;border:1px solid #d9d0c1;border-radius:12px;box-shadow:0 12px 28px rgba(0,0,0,.08);z-index:999;max-height:340px;overflow-y:auto"></div>
</div>

<?php if (! $__env->hasRenderedOnce('278eb2d8-cc20-45f4-9305-f1f8048a50c1')): $__env->markAsRenderedOnce('278eb2d8-cc20-45f4-9305-f1f8048a50c1'); ?>
<?php $__env->startPush('styles'); ?>
<style>
.search-dropdown { position:relative; }
.search-dropdown .ops-search { width:100%; }
.search-dropdown-results .sdr-item { display:flex;gap:12px;align-items:center;padding:10px 14px;border-bottom:1px solid #ede8df;cursor:pointer;font-size:10px;color:#3a3530;text-decoration:none;transition:background .15s ease; }
.search-dropdown-results .sdr-item:last-child { border-bottom:none; }
.search-dropdown-results .sdr-item:hover, .search-dropdown-results .sdr-item.is-active { background:#faf6ec; }
.search-dropdown-results .sdr-item img { width:46px;height:46px;object-fit:cover;border-radius:6px;flex-shrink:0; }
.search-dropdown-results .sdr-title { font-weight:700;color:#234A36;font-size:10px;line-height:1.2; }
.search-dropdown-results .sdr-subtitle { font-size:8px;color:#6b6b6b;margin-top:2px; }
.search-dropdown-results .sdr-hint { padding:14px;color:#6b6b6b;font-size:9px;text-align:center; }
.search-dropdown-results .sdr-meta { padding:8px 14px;font-size:7px;color:#6b6b6b;text-transform:uppercase;letter-spacing:.4px;background:#faf6ec;border-bottom:1px solid #ede8df; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
    function render(items, hint){
        return (items || []).map(function(it){
            var img = it.image ? '<img src="' + it.image + '" alt="" loading="lazy">' : '<span style="width:46px;height:46px;background:#ede8df;border-radius:6px;display:inline-block;flex-shrink:0"></span>';
            return '<a class="sdr-item" role="option" href="' + (it.url || '#') + '">' + img +
                   '<div style="min-width:0;flex:1"><div class="sdr-title">' + (it.title || '') + '</div>' +
                   (it.subtitle ? '<div class="sdr-subtitle">' + it.subtitle + '</div>' : '') + '</div></a>';
        }).join('');
    }

    function bindDropdowns() {
        document.querySelectorAll('.search-dropdown').forEach(function(dd){
            if (dd.dataset.bound === '1') return;
            dd.dataset.bound = '1';
            var input = dd.querySelector('.search-dropdown-input');
            var results = dd.querySelector('.search-dropdown-results');
            var endpoint = dd.dataset.endpoint;
            var showAll = dd.dataset.showAll === '1';
            var timer = null;
            var lastTerm = '';

            function placeholderHtml(msg){ return '<div class="sdr-hint">' + msg + '</div>'; }

            function fetch(term, cb){
                var url = endpoint + (term ? (endpoint.includes('?') ? '&' : '?') + 'term=' + encodeURIComponent(term) : (showAll ? (endpoint.includes('?') ? '&' : '?') + 'all=1' : ''));
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                    .then(function(r){ return r.json(); })
                    .then(function(data){ cb(data); })
                    .catch(function(){ cb({ items: [] }); });
            }

            function open(){ results.classList.remove('hidden'); }
            function close(){ results.classList.add('hidden'); results.innerHTML=''; }

            function showResults(data, term){
                var html = '';
                if (term === '' && showAll) {
                    html += '<div class="sdr-meta">All itineraries · pick one to view the route</div>';
                } else if (term !== '') {
                    html += '<div class="sdr-meta">' + (data.items ? data.items.length : 0) + ' match(es) for &ldquo;' + term + '&rdquo;</div>';
                }
                if (!data.items || !data.items.length) {
                    // Never say "no match" – show a helpful prompt instead.
                    html += placeholderHtml(term === '' ? 'Start typing to filter itineraries' : 'Showing live results below the table');
                } else {
                    html += render(data.items);
                }
                results.innerHTML = html;
            }

            input.addEventListener('focus', function(){
                open();
                if (showAll && input.value.trim() === '') {
                    fetch('', function(d){ showResults(d, ''); lastTerm=''; });
                } else if (input.value.trim() !== '') {
                    fetch(input.value.trim(), function(d){ showResults(d, input.value.trim()); lastTerm=input.value.trim(); });
                }
            });
            input.addEventListener('input', function(){
                var term = input.value.trim();
                clearTimeout(timer);
                timer = setTimeout(function(){
                    fetch(term, function(d){ showResults(d, term); lastTerm=term; open(); });
                }, 200);
            });
            input.addEventListener('keydown', function(e){
                if (e.key === 'Escape') { input.value=''; input.dispatchEvent(new Event('input')); close(); }
                if (e.key === 'Enter') {
                    if (lastTerm) {
                        // Trigger the page filter (existing live-search behaviour).
                        input.blur();
                    }
                }
            });
            document.addEventListener('click', function(e){
                if (!dd.contains(e.target)) close();
            });
            results.addEventListener('click', function(e){
                var item = e.target.closest('.sdr-item');
                if (item && item.getAttribute('href')) {
                    window.location.href = item.getAttribute('href');
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindDropdowns);
    } else {
        bindDropdowns();
    }
    // Re-bind when views are dynamically replaced (AJAX partials).
    document.addEventListener('search:refresh', bindDropdowns);
})();
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH C:\shishifootsteps\safari\resources\views/components/public/search-with-dropdown.blade.php ENDPATH**/ ?>