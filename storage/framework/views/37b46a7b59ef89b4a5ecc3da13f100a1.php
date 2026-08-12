<div id="clientDropdown" style="position:relative">
    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Client</label>

    <input type="text" id="clientSearch"
           style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF"
           placeholder="Search existing clients..." autocomplete="off">

    <input type="hidden" name="client_id" id="clientId" value="<?php echo e(old('client_id', $request->client_id ?? '')); ?>">
    <input type="hidden" name="client_name" id="clientNameInput" value="<?php echo e(old('client_name', $request->client_name ?? '')); ?>">
    <meta name="search-clients-url" content="<?php echo e(route('admin.requests.search-clients')); ?>">
    <meta name="store-client-url" content="<?php echo e(route('admin.requests.store-client')); ?>">

    <div id="clientSelected" class="<?php echo e(old('client_id') ? '' : 'hidden'); ?>" style="margin-top:8px">
        <span style="display:inline-flex;align-items:center;gap:6px;background:#ede8df;padding:6px 10px;border-radius:6px;font-size:9px">
            <span id="selectedClientName"></span>
            <button type="button" id="clearClient" style="background:none;border:none;cursor:pointer;padding:2px;color:var(--text-muted)">
                <i data-lucide="x" style="width:12px;height:12px"></i>
            </button>
        </span>
    </div>

    <div id="clientResults" class="hidden"
         style="position:absolute;top:100%;left:0;right:0;z-index:999;margin-top:4px;background:#fff;border:1px solid #d9d0c1;border-radius:7px;box-shadow:0 8px 24px rgba(0,0,0,.1);max-height:320px;overflow-y:auto">
    </div>
</div>


<?php
    $initialTemplate = null;
    if (!empty($request->itinerary_template_id)) {
        $initialTemplate = \App\Models\ItineraryTemplate::find($request->itinerary_template_id);
    }
?>
<div id="templateSearch" style="margin-top:16px;border-top:1px solid var(--line);padding-top:16px;position:relative">
    <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Itinerary Template</label>
    <input type="text" id="templateSearchInput"
           value="<?php echo e(old('itinerary_template_name', $initialTemplate?->trip_name ?: $initialTemplate?->name)); ?>"
           placeholder="Search itinerary templates by name..."
           autocomplete="off"
           style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text);outline:none"
           onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#d9d0c1'">
    <input type="hidden" name="itinerary_template_id" id="templateId" value="<?php echo e(old('itinerary_template_id', $request->itinerary_template_id ?? '')); ?>">
    <input type="hidden" name="itinerary_template_name" id="templateName" value="<?php echo e(old('itinerary_template_name', $initialTemplate?->trip_name ?: $initialTemplate?->name)); ?>">
    <meta name="template-search-url" content="<?php echo e(route('admin.itinerary-templates.search')); ?>">
    <div id="templateResults" class="hidden"
         style="position:absolute;top:100%;left:0;right:0;z-index:998;margin-top:4px;background:#fff;border:1px solid #d9d0c1;border-radius:7px;box-shadow:0 8px 24px rgba(0,0,0,.08);max-height:320px;overflow-y:auto">
    </div>
    <div id="templateSelected" class="<?php echo e($initialTemplate ? '' : 'hidden'); ?>" style="margin-top:8px">
        <span style="display:inline-flex;align-items:center;gap:6px;background:#dfece0;padding:6px 10px;border-radius:6px;font-size:9px;color:#234A36">
            <i data-lucide="map" style="width:12px;height:12px"></i>
            <span id="selectedTemplateLabel"><?php echo e(trim(($initialTemplate?->trip_name ?: $initialTemplate?->name) . ($initialTemplate?->duration_days ? ' ('.$initialTemplate->duration_days.' days)' : ''))); ?></span>
            <button type="button" id="clearTemplate" style="background:none;border:none;cursor:pointer;padding:2px;color:#234A36">
                <i data-lucide="x" style="width:12px;height:12px"></i>
            </button>
        </span>
    </div>
    <small style="display:block;font-size:9px;color:var(--text-muted);margin-top:6px">Click the field to see every template, or type to filter — picking one auto-fills destination, nights, dates and itinerary notes.</small>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('clientSearch');
    var clientId = document.getElementById('clientId');
    var clientNameInput = document.getElementById('clientNameInput');
    var results = document.getElementById('clientResults');
    var selected = document.getElementById('clientSelected');
    var selectedName = document.getElementById('selectedClientName');
    var clearBtn = document.getElementById('clearClient');
    var searchTimeout;

    function selectClient(id, name) {
        clientId.value = id;
        clientNameInput.value = name;
        if (selectedName) selectedName.textContent = name;
        if (selected) selected.classList.remove('hidden');
        if (results) results.classList.add('hidden');
        if (searchInput) { searchInput.value = name; searchInput.readOnly = true; }
    }

    function clearSelected() {
        clientId.value = '';
        clientNameInput.value = '';
        if (selected) selected.classList.add('hidden');
        if (searchInput) { searchInput.readOnly = false; searchInput.value = ''; searchInput.focus(); }
    }

    if (clearBtn) clearBtn.addEventListener('click', clearSelected);

    function showDropdown(html) {
        results.innerHTML = html;
        results.classList.remove('hidden');
    }

    function hideDropdown() {
        results.classList.add('hidden');
    }

    function getInlineCreateForm(term) {
        return '<div class="inline-create-form" style="padding:12px">' +
            '<div style="font-size:9px;font-weight:700;color:#234A36;margin-bottom:8px">Add New Client</div>' +
            '<input type="text" class="inline-name" value="' + term.replace(/"/g, '&quot;') + '" placeholder="Full Name *" style="width:100%;height:34px;padding:0 10px;border:1px solid #d9d0c1;border-radius:6px;font-size:9px;margin-bottom:6px;background:#fff;box-sizing:border-box">' +
            '<input type="email" class="inline-email" placeholder="Email" style="width:100%;height:34px;padding:0 10px;border:1px solid #d9d0c1;border-radius:6px;font-size:9px;margin-bottom:6px;background:#fff;box-sizing:border-box">' +
            '<input type="text" class="inline-phone" placeholder="Phone" style="width:100%;height:34px;padding:0 10px;border:1px solid #d9d0c1;border-radius:6px;font-size:9px;margin-bottom:6px;background:#fff;box-sizing:border-box">' +
            '<input type="text" class="inline-country" placeholder="Country" style="width:100%;height:34px;padding:0 10px;border:1px solid #d9d0c1;border-radius:6px;font-size:9px;margin-bottom:8px;background:#fff;box-sizing:border-box">' +
            '<button type="button" class="save-client-btn" style="width:100%;height:34px;background:#234A36;color:#fff;border:none;border-radius:6px;font-size:9px;font-weight:700;cursor:pointer">Save Client</button>' +
            '</div>';
    }

    function bindDropdown() {
        document.querySelectorAll('.client-result').forEach(function(el) {
            el.addEventListener('click', function() {
                selectClient(this.dataset.id, this.dataset.name);
            });
        });
        var addTrigger = document.getElementById('addNewClientTrigger');
        if (addTrigger) {
            addTrigger.addEventListener('click', function() {
                showDropdown(getInlineCreateForm(searchInput.value));
                bindSaveClient();
            });
        }
        bindSaveClient();
    }

    function bindSaveClient() {
        document.querySelectorAll('.save-client-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var container = this.closest('.inline-create-form');
                if (!container) return;
                var inputs = container.querySelectorAll('input');
                var name = inputs[0] ? inputs[0].value.trim() : '';
                var email = inputs[1] ? inputs[1].value.trim() : '';
                var phone = inputs[2] ? inputs[2].value.trim() : '';
                var country = inputs[3] ? inputs[3].value.trim() : '';
                if (!name) { alert('Name is required'); return; }
                this.disabled = true;
                this.textContent = 'Saving...';
                var btnEl = this;
                var storeUrl = document.querySelector('meta[name="store-client-url"]')?.getAttribute('content') || '/admin/requests/store-client';
                fetch(storeUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({ name: name, email: email, phone: phone, country: country })
                })
                .then(function(r) {
                    if (!r.ok) { return r.json().then(function(e) { throw e; }); }
                    return r.json();
                })
                .then(function(client) {
                    selectClient(client.id, client.name);
                })
                .catch(function(err) {
                    var msg = (err.errors ? Object.values(err.errors).flat().join(', ') : (err.message || 'Error saving client'));
                    alert(msg);
                    btnEl.disabled = false;
                    btnEl.textContent = 'Save Client';
                });
            });
        });
    }

    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            if (clientId.value) { hideDropdown(); return; }
            var q = this.value.trim();
            if (q.length >= 2) {
                triggerSearch(q);
            } else {
                showDropdown('<div style="padding:10px 12px;text-align:center;font-size:9px;color:#6b6b6b">Type at least 2 characters to search<br><br><strong id="addNewClientTrigger" style="color:#234A36;cursor:pointer">+ Add New Client</strong></div>');
                bindDropdown();
            }
        });

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            var q = this.value.trim();
            if (q.length < 2) {
                hideDropdown();
                return;
            }
            triggerSearch(q);
        });

        document.addEventListener('click', function(e) {
            if (!document.getElementById('clientDropdown').contains(e.target)) {
                hideDropdown();
            }
        });
    }

    function triggerSearch(q) {
        var searchUrl = document.querySelector('meta[name="search-clients-url"]')?.getAttribute('content') || '/admin/requests/search-clients';
        searchTimeout = setTimeout(function() {
            fetch(searchUrl + '?term=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.length > 0) {
                    var html = data.map(function(c) {
                        return '<div class="client-result" data-id="' + c.id + '" data-name="' + c.name.replace(/'/g, "&apos;") + '" style="padding:8px 12px;cursor:pointer;border-bottom:1px solid #ede8df;font-size:9px">' +
                               '<strong style="color:#234A36">' + c.name + '</strong>' +
                               (c.email ? '<span style="color:#6b6b6b;margin-left:8px">' + c.email + '</span>' : '') +
                               (c.phone ? '<span style="color:#6b6b6b;margin-left:8px">' + c.phone + '</span>' : '') +
                               '</div>';
                    }).join('');
                    html += '<div id="addNewClientTrigger" style="padding:10px 12px;text-align:center;cursor:pointer;border-top:2px solid #234A36;color:#234A36;font-weight:700;font-size:9px">+ Add New Client</div>';
                    showDropdown(html);
                } else {
                    showDropdown(getInlineCreateForm(q));
                }
                bindDropdown();
            });
        }, 300);
    }

    if (clientId.value) {
        searchInput.readOnly = true;
    }

    // Template search & autofill
    var templateInput = document.getElementById('templateSearchInput');
    var templateId = document.getElementById('templateId');
    var templateName = document.getElementById('templateName');
    var templateResults = document.getElementById('templateResults');
    var templateSelected = document.getElementById('templateSelected');
    var templateSelectedLabel = document.getElementById('selectedTemplateLabel');
    var templateClear = document.getElementById('clearTemplate');
    var templateTimeout;

    function renderTemplateList(items, term) {
        var meta = '<div style="padding:8px 12px;font-size:8px;color:#6b6b6b;text-transform:uppercase;letter-spacing:.4px;background:#faf6ec;border-bottom:1px solid #ede8df">' +
                   (term === '' ? 'All itinerary templates — pick one' : (items.length || 0) + ' match(es) for &ldquo;' + (term || '') + '&rdquo;') + '</div>';
        if (!items.length) {
            templateResults.innerHTML = meta + '<div style="padding:14px 12px;text-align:center;font-size:9px;color:#6b6b6b">No templates in the catalogue yet. Create one in <em>Itinerary Templates</em> to link it here.</div>';
            templateResults.classList.remove('hidden');
            return;
        }
        var html = items.map(function(t) {
            var title = t.trip_name || t.name;
            var dur = t.duration_days ? t.duration_days + ' days' : '';
            var safeTitle = (title || '').replace(/'/g, '&apos;');
            return '<div class="template-result" data-id="' + t.id + '" data-name="' + safeTitle + '" data-duration="' + (t.duration_days || 0) + '" style="padding:9px 12px;cursor:pointer;border-bottom:1px solid #ede8df;font-size:9px;display:flex;align-items:center;gap:8px">' +
                   '<i data-lucide="map" style="width:14px;height:14px;color:#234A36"></i>' +
                   '<div style="flex:1"><strong style="color:#234A36">' + title + '</strong><br>' + (dur ? '<span style="color:#6b6b6b;font-size:9px">' + dur + '</span>' : '') + '</div>' +
                   '</div>';
        }).join('');
        templateResults.innerHTML = meta + html;
        templateResults.classList.remove('hidden');
        if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
    }

    function hideTemplateResults() { templateResults.classList.add('hidden'); }

    function selectTemplate(id, name, duration) {
        templateId.value = id;
        templateName.value = name;
        if (templateSelectedLabel) templateSelectedLabel.textContent = name + (duration ? ' (' + duration + ' days)' : '');
        if (templateSelected) templateSelected.classList.remove('hidden');
        if (templateInput) templateInput.value = name;
        hideTemplateResults();
        autoFillFromTemplate(id);
    }

    function clearSelectedTemplate() {
        templateId.value = '';
        templateName.value = '';
        if (templateSelected) templateSelected.classList.add('hidden');
        if (templateInput) { templateInput.value = ''; templateInput.focus(); }
    }

    function autoFillFromTemplate(id) {
        if (!id) return;
        var daysUrl = '<?php echo e(route("admin.itinerary-templates.days", ["template" => "TPLID"])); ?>'.replace('TPLID', id);
        fetch(daysUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(days) {
                if (!days || !days.length) return;
                var destInput = document.querySelector('[name="destination"]');
                var nightsInput = document.getElementById('nights');
                var arrivalInput = document.getElementById('arrivalDate');
                var departureInput = document.getElementById('departureDate');
                var notesInput = document.querySelector('[name="internal_notes"]');
                var dayTitles = days.map(function(d, i) {
                    return 'Day ' + (i + 1) + ': ' + (d.title || d.description || '').substring(0, 120);
                });
                if (destInput && days[0].destination_name) destInput.value = days[0].destination_name;
                if (nightsInput) {
                    nightsInput.value = days.length;
                    nightsInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
                if (arrivalInput && departureInput && !arrivalInput.value && !departureInput.value) {
                    var start = new Date();
                    var end = new Date();
                    end.setDate(start.getDate() + days.length);
                    arrivalInput.value = start.toISOString().slice(0, 10);
                    departureInput.value = end.toISOString().slice(0, 10);
                    arrivalInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
                if (notesInput && !notesInput.value.trim()) {
                    notesInput.value = 'Selected itinerary (' + (templateName.value || 'template') + ') – ' + days.length + ' days\n\n' + dayTitles.join('\n');
                }
            });
    }

    if (templateInput) {
        templateInput.addEventListener('focus', function() {
            triggerTemplateSearch(this.value || '');
        });
        templateInput.addEventListener('click', function() {
            triggerTemplateSearch(this.value || '');
        });
        templateInput.addEventListener('input', function() {
            if (templateId.value && this.value !== templateName.value) {
                templateId.value = '';
                templateName.value = '';
                if (templateSelected) templateSelected.classList.add('hidden');
            }
            triggerTemplateSearch(this.value);
        });
        document.addEventListener('click', function(e) {
            if (!document.getElementById('templateSearch').contains(e.target)) {
                hideTemplateResults();
            }
        });
    }

    if (templateClear) templateClear.addEventListener('click', clearSelectedTemplate);

    function bindTemplateResultClicks() {
        document.querySelectorAll('#templateResults .template-result').forEach(function(el) {
            el.addEventListener('click', function() {
                selectTemplate(this.dataset.id, this.dataset.name, this.dataset.duration);
            });
        });
    }
    bindTemplateResultClicks();

    function triggerTemplateSearch(term) {
        var url = document.querySelector('meta[name="template-search-url"]')?.getAttribute('content') || '/admin/itinerary-templates/search';
        clearTimeout(templateTimeout);
        templateTimeout = setTimeout(function() {
            var fetchUrl = url + '?term=' + encodeURIComponent(term || '');
            fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    renderTemplateList(Array.isArray(data) ? data : [], term || '');
                    bindTemplateResultClicks();
                })
                .catch(function() {
                    hideTemplateResults();
                });
        }, 150);
    }

    // If a template was prefilled (from redirect/validation), reflect in input
    if (templateId.value && templateInput && !templateInput.value) {
        templateInput.value = templateName.value || '';
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\user\Documents\xamp\htdocs\safari\resources\views\admin\requests\partials\_client_search.blade.php ENDPATH**/ ?>