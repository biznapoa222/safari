<?php $__env->startSection('title', 'Itinerary Templates'); ?>
<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.top-bar','data' => ['title' => 'Itinerary Templates','description' => 'Manage itinerary templates','addLabel' => 'Create Template','addRoute' => ''.e(route('admin.itinerary-templates.create')).'','search' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.top-bar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Itinerary Templates','description' => 'Manage itinerary templates','addLabel' => 'Create Template','addRoute' => ''.e(route('admin.itinerary-templates.create')).'','search' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf)): ?>
<?php $attributes = $__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf; ?>
<?php unset($__attributesOriginalad67f78bf768badc17b2fc4005a4f8bf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad67f78bf768badc17b2fc4005a4f8bf)): ?>
<?php $component = $__componentOriginalad67f78bf768badc17b2fc4005a4f8bf; ?>
<?php unset($__componentOriginalad67f78bf768badc17b2fc4005a4f8bf); ?>
<?php endif; ?>

<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div id="templateFilters" style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;align-items:center;position:relative">
    <div style="flex:1;min-width:260px;position:relative">
        <label class="ops-search" style="flex:1;min-width:240px">
            <i data-lucide="search"></i>
            <input id="templateLiveSearch" type="text" value="<?php echo e(request('search')); ?>" placeholder="Search templates by name, trip name or destination..." autocomplete="off">
        </label>
        <div id="templateDropdownResults" class="hidden" style="position:absolute;left:0;right:0;top:100%;margin-top:6px;background:#fff;border:1px solid #d9d0c1;border-radius:10px;box-shadow:0 12px 28px rgba(0,0,0,.1);z-index:999;max-height:320px;overflow-y:auto"></div>
    </div>
    <select id="statusFilter" style="min-width:120px;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none">
        <option value="">All Statuses</option>
        <option value="active" <?php if(request('status') === 'active'): echo 'selected'; endif; ?>>Active</option>
        <option value="inactive" <?php if(request('status') === 'inactive'): echo 'selected'; endif; ?>>Inactive</option>
    </select>
    <select id="categoryFilter" style="min-width:120px;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none">
        <option value="">All Categories</option>
        <?php $__currentLoopData = \App\Models\ItineraryTemplate::categories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($val); ?>" <?php if(request('category') === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <button id="resetFilters" type="button" class="button button-secondary" style="font-size:9px;height:38px">Reset</button>
</div>

<section class="ops-panel">
    <div class="table-wrap">
        <table class="ops-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Destination</th>
                    <th>Duration</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Days</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="templateTableBody">
                <?php echo $__env->make('admin.itinerary-templates.partials._table_rows', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </tbody>
        </table>
    </div>
    <div id="templatePagination" class="ops-pagination">
        <?php if($templates->hasPages()): ?><?php echo e($templates->links()); ?><?php endif; ?>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var search = document.getElementById('templateLiveSearch');
    var statusFilter = document.getElementById('statusFilter');
    var categoryFilter = document.getElementById('categoryFilter');
    var resetBtn = document.getElementById('resetFilters');
    var tableBody = document.getElementById('templateTableBody');
    var pagination = document.getElementById('templatePagination');
    var dropdownResults = document.getElementById('templateDropdownResults');
    var timer;
    var dropdownTimer;
    var firstRun = true;

    var baseUrl = "<?php echo e(route('admin.itinerary-templates.search')); ?>";

    function renderDropdown(items, term) {
        if (!dropdownResults) return;
        var meta = '<div class="sdr-meta">' + (term ? items.length + ' match(es) for "' + term + '"' : 'All templates — click to open') + '</div>';
        if (!items || !items.length) {
            dropdownResults.innerHTML = meta + '<div class="sdr-hint">Showing live results in the table below as you type.</div>';
            return;
        }
        var html = items.map(function(it){
            return '<a class="sdr-item" href="/admin/itinerary-templates/' + it.id + '/edit">' +
                '<i data-lucide="map" style="width:14px;height:14px;color:#234A36"></i>' +
                '<div><strong>' + (it.trip_name || it.name) + '</strong><br><span style="color:#6b6b6b;font-size:8px">' + (it.duration_days ? it.duration_days + ' days' : '') + '</span></div>' +
                '</a>';
        }).join('');
        dropdownResults.innerHTML = meta + html;
        if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
    }

    function fetchDropdown(term) {
        clearTimeout(dropdownTimer);
        dropdownTimer = setTimeout(function() {
            var url = baseUrl + (term ? '?term=' + encodeURIComponent(term) : '');
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(function(r){ return r.json(); })
                .then(function(data){
                    renderDropdown(Array.isArray(data) ? data : [], term);
                    dropdownResults.classList.remove('hidden');
                })
                .catch(function(){ dropdownResults.classList.add('hidden'); });
        }, 200);
    }

    function fetchRows(page) {
        var params = new URLSearchParams();
        if (search.value.trim()) params.set('search', search.value.trim());
        if (statusFilter.value) params.set('status', statusFilter.value);
        if (categoryFilter.value) params.set('category', categoryFilter.value);
        if (page) params.set('page', page);

        var url = window.location.pathname + '?' + params.toString();
        if (!firstRun) history.replaceState(null, '', url);
        firstRun = false;

        tableBody.style.opacity = '0.5';

        fetch(url + (url.includes('?') ? '&' : '?') + 'ajax=1', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            tableBody.innerHTML = data.html || '';
            pagination.innerHTML = data.pagination || '';
            tableBody.style.opacity = '1';
            if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
        })
        .catch(function() {
            tableBody.style.opacity = '1';
        });
    }

    function debounce() {
        clearTimeout(timer);
        timer = setTimeout(function() { fetchRows(); }, 250);
    }

    if (search) {
        search.addEventListener('focus', function() { fetchDropdown(this.value.trim()); });
        search.addEventListener('input', function() {
            fetchDropdown(this.value.trim());
            debounce();
        });
        search.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                search.value = '';
                fetchDropdown('');
                fetchRows();
                dropdownResults.classList.add('hidden');
            }
        });
    }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#templateFilters')) dropdownResults.classList.add('hidden');
    });

    statusFilter?.addEventListener('change', function() { fetchRows(); });
    categoryFilter?.addEventListener('change', function() { fetchRows(); });

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            search.value = '';
            statusFilter.value = '';
            categoryFilter.value = '';
            dropdownResults.classList.add('hidden');
            fetchRows();
        });
    }

    pagination.addEventListener('click', function(e) {
        var link = e.target.closest('a');
        if (!link) return;
        var href = link.getAttribute('href');
        if (!href) return;
        e.preventDefault();
        var pageMatch = href.match(/[?&]page=(\d+)/);
        fetchRows(pageMatch ? pageMatch[1] : 1);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\itinerary-templates\index.blade.php ENDPATH**/ ?>