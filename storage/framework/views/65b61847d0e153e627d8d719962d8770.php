<?php $__env->startSection('title', 'Itineraries | Shishi Footsteps'); ?>
<?php $__env->startSection('description', 'Detailed luxury safari itineraries published from the Shishi Footsteps ERP.'); ?>

<?php $__env->startSection('content'); ?>
<?php $hero = asset('images/itineraries/botswana-luxury-cover.webp'); $cms=fn($key,$fallback='')=>\App\Models\CmsContentBlock::value('itineraries',$key,$fallback); $countries = ['Kenya','Tanzania','Uganda','Rwanda','South Africa','Namibia','Botswana']; ?>

<?php if (isset($component)) { $__componentOriginal7667e390c55120bda1fc27c0189959e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7667e390c55120bda1fc27c0189959e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.page-hero','data' => ['label' => 'Itineraries','title' => $cms('hero_title'),'subtitle' => $cms('hero_subtitle'),'image' => \App\Support\MediaPath::publicUrl($cms('hero_image',$hero))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Itineraries','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_title')),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cms('hero_subtitle')),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Support\MediaPath::publicUrl($cms('hero_image',$hero)))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7667e390c55120bda1fc27c0189959e9)): ?>
<?php $attributes = $__attributesOriginal7667e390c55120bda1fc27c0189959e9; ?>
<?php unset($__attributesOriginal7667e390c55120bda1fc27c0189959e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7667e390c55120bda1fc27c0189959e9)): ?>
<?php $component = $__componentOriginal7667e390c55120bda1fc27c0189959e9; ?>
<?php unset($__componentOriginal7667e390c55120bda1fc27c0189959e9); ?>
<?php endif; ?>

<section class="content-band itinerary-list">
    <div class="safari-filters" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;justify-content:space-between;margin:0 0 22px;position:relative">
        <div style="flex:1;min-width:280px;position:relative">
            <?php if (isset($component)) { $__componentOriginalc9d3b75455031f3ceee8129e8f7cc9cb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc9d3b75455031f3ceee8129e8f7cc9cb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.search-with-dropdown','data' => ['placeholder' => 'Search itineraries by title, country or highlight...','endpoint' => route('public.search.itineraries')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.search-with-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placeholder' => 'Search itineraries by title, country or highlight...','endpoint' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('public.search.itineraries'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc9d3b75455031f3ceee8129e8f7cc9cb)): ?>
<?php $attributes = $__attributesOriginalc9d3b75455031f3ceee8129e8f7cc9cb; ?>
<?php unset($__attributesOriginalc9d3b75455031f3ceee8129e8f7cc9cb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc9d3b75455031f3ceee8129e8f7cc9cb)): ?>
<?php $component = $__componentOriginalc9d3b75455031f3ceee8129e8f7cc9cb; ?>
<?php unset($__componentOriginalc9d3b75455031f3ceee8129e8f7cc9cb); ?>
<?php endif; ?>
        </div>
        <select id="itineraryCountryFilter" style="min-width:180px;height:42px;padding:0 12px;border:1px solid #d9d0c1;border-radius:9px;font-size:10px;background:#F8F5EF;color:var(--text);outline:none">
            <option value="">All countries</option>
            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($c); ?>" <?php if(request('country') === $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button id="itineraryReset" type="button" class="button button-secondary" style="height:42px;font-size:10px">Reset</button>
    </div>

    <div id="itineraryListWrap">
        <?php echo $__env->make('public.partials._itinerary_rows', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.querySelector('.search-dropdown');
    var search = dropdown ? dropdown.querySelector('.search-dropdown-input') : null;
    var country = document.getElementById('itineraryCountryFilter');
    var reset = document.getElementById('itineraryReset');
    var wrap = document.getElementById('itineraryListWrap');
    var timer;
    var firstRun = true;

    function fetchList(page) {
        var params = new URLSearchParams();
        if (search && search.value.trim()) params.set('search', search.value.trim());
        if (country && country.value) params.set('country', country.value);
        if (page) params.set('page', page);
        var url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        if (!firstRun) history.replaceState(null, '', url);
        firstRun = false;

        wrap.style.opacity = '0.55';
        fetch(url + (url.includes('?') ? '&' : '?') + 'ajax=1', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                wrap.innerHTML = data.html || '';
                wrap.style.opacity = '1';
                if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
            })
            .catch(function() { wrap.style.opacity = '1'; });
    }

    function debounce() { clearTimeout(timer); timer = setTimeout(function(){ fetchList(); }, 250); }

    search?.addEventListener('input', debounce);
    country?.addEventListener('change', function(){ fetchList(); });
    reset?.addEventListener('click', function(){
        if (search) search.value='';
        country.value='';
        fetchList();
    });

    wrap.addEventListener('click', function(e){
        var a = e.target.closest('.pagination-wrap a');
        if (!a) return;
        var href = a.getAttribute('href');
        if (!href) return;
        e.preventDefault();
        var pageMatch = href.match(/[?&]page=(\d+)/);
        fetchList(pageMatch ? pageMatch[1] : 1);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\public\itineraries.blade.php ENDPATH**/ ?>