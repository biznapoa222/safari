const RequestsModule = {
    baseUrl: (document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '').replace(/\/+$/, ''),

    url(path) {
        return this.baseUrl + path;
    },
    init() {
        this.initStatusTabs();
        this.initFilters();
        this.initStarRating();
        this.initFlagPicker();
        this.initSellerNotes();
        this.initBulkActions();
        this.initConvertToQuote();
        this.initDateCalculations();
        this.initTaskManagement();
        this.initFileUploads();
        this.initTimeline();
    },

    initStatusTabs() {
        document.querySelectorAll('[data-status-tab]').forEach(function (tab) {
            tab.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelectorAll('[data-status-tab]').forEach(function (t) { t.classList.remove('active-tab', 'is-active'); });
                this.classList.add('active-tab', 'is-active');
                var status = this.dataset.statusTab;
                var statusFilter = document.querySelector('#requestsFilterForm [name="status"]');
                if (statusFilter) statusFilter.value = status;
                var url = new URL(window.location);
                if (status) url.searchParams.set('status', status);
                else url.searchParams.delete('status');
                window.history.pushState({}, '', url);
                RequestsModule.loadTable();
            });
        });
    },

    loadTable() {
        var tableBody = document.getElementById('tableBody');
        if (!tableBody) {
            var container = document.getElementById('requestsTableContainer');
            if (!container) return;
            tableBody = container.querySelector('tbody');
            if (!tableBody) return;
        }
        var form = document.getElementById('requestsFilterForm');
        var formData = form ? new FormData(form) : new FormData();
        var params = new URLSearchParams(formData);
        var activeTab = document.querySelector('[data-status-tab].active-tab');
        if (activeTab && activeTab.dataset.statusTab) {
            params.set('status', activeTab.dataset.statusTab);
        }
        axios.post(this.url('/admin/requests/filters'), params)
            .then(function (response) {
                var data = response.data;
                tableBody.innerHTML = data.html || data;
                var tableWrapper = document.getElementById('requestsTableWrapper');
                if (data.total !== undefined && tableWrapper) {
                    var info = tableWrapper.querySelector('.table-info');
                    if (info) info.textContent = 'Showing ' + data.from + ' to ' + data.to + ' of ' + data.total + ' results';
                }
                if (window.lucide) window.lucide.createIcons();
                RequestsModule.initStarRating();
                RequestsModule.initFlagPicker();
                RequestsModule.initBulkActions();
                RequestsModule.initConvertToQuote();
                RequestsModule.initSellerNotes();
            });
    },

    initFilters() {
        var filterForm = document.getElementById('requestsFilterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function (e) {
                e.preventDefault();
                RequestsModule.loadTable();
            });
            filterForm.querySelectorAll('select').forEach(function (select) {
                select.addEventListener('change', function () {
                    if (select.name === 'status') {
                        document.querySelectorAll('[data-status-tab]').forEach(function (tab) { tab.classList.remove('active-tab', 'is-active'); });
                    }
                    RequestsModule.loadTable();
                });
            });
            filterForm.querySelectorAll('input[type="date"]').forEach(function (input) {
                input.addEventListener('change', function () {
                    RequestsModule.loadTable();
                });
            });
            filterForm.querySelectorAll('input[type="checkbox"]').forEach(function (input) {
                input.addEventListener('change', function () {
                    RequestsModule.loadTable();
                });
            });
        }
        document.querySelectorAll('.filter-reset').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var form = document.getElementById('requestsFilterForm');
                if (form) form.reset();
                RequestsModule.loadTable();
            });
        });
        document.querySelectorAll('.filter-apply').forEach(function (btn) {
            btn.addEventListener('click', function () {
                RequestsModule.loadTable();
            });
        });
    },

    initStarRating() {
        document.querySelectorAll('.star-rating').forEach(function (container) {
            container.querySelectorAll('.star').forEach(function (star) {
                star.addEventListener('click', function () {
                    var rating = parseInt(this.dataset.rating);
                    var requestId = container.dataset.requestId;
                    container.querySelectorAll('.star').forEach(function (s) {
                        s.classList.toggle('text-[#c8a96a]', parseInt(s.dataset.rating) <= rating);
                        s.classList.toggle('text-[#5a6b62]', parseInt(s.dataset.rating) > rating);
                    });
                    axios.put('/admin/requests/' + requestId + '/rating', { rating: rating }).catch(function () {});
                });
            });
        });
    },

    initFlagPicker() {
        document.querySelectorAll('[data-flag-trigger]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var requestId = this.dataset.flagTrigger;
                var existingPicker = document.querySelector('.flag-picker-popup');
                if (existingPicker) existingPicker.remove();
                var colors = ['red', 'yellow', 'blue', 'green', 'purple', 'orange'];
                var picker = document.createElement('div');
                picker.className = 'flag-picker-popup absolute top-full left-0 mt-1 bg-[#0f1a14] border border-[#1f3028] rounded-lg shadow-xl p-2 flex gap-1 z-50';
                picker.style.display = 'flex';
                var colorMap = { red: '#ef4444', yellow: '#eab308', blue: '#3b82f6', green: '#22c55e', purple: '#a855f7', orange: '#f97316' };

                colors.forEach(function (color) {
                    var dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'w-6 h-6 rounded-full border border-[#1f3028] hover:scale-110 transition-transform';
                    dot.style.backgroundColor = colorMap[color];
                    dot.setAttribute('data-flag-color', color);
                    dot.addEventListener('click', function (e) {
                        e.stopPropagation();
                        var selectedColor = this.dataset.flagColor;
                        axios.put('/admin/requests/' + requestId + '/flag', { flag_color: selectedColor })
                            .then(function () {
                                var flagIcon = btn.querySelector('[data-lucide]');
                                if (flagIcon) flagIcon.style.color = colorMap[selectedColor] || selectedColor;
                                picker.remove();
                            })
                            .catch(function () { picker.remove(); });
                    });
                    picker.appendChild(dot);
                });

                var clearBtn = document.createElement('button');
                clearBtn.type = 'button';
                clearBtn.className = 'w-6 h-6 rounded-full border border-[#1f3028] flex items-center justify-center text-[#5a6b62] hover:text-[#ece8e0] hover:scale-110 transition-transform text-xs';
                clearBtn.textContent = '\u2715';
                clearBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    axios.put('/admin/requests/' + requestId + '/flag', { flag_color: null })
                        .then(function () {
                            var flagIcon = btn.querySelector('[data-lucide]');
                            if (flagIcon) flagIcon.style.color = '';
                            picker.remove();
                        })
                        .catch(function () { picker.remove(); });
                });
                picker.appendChild(clearBtn);
                btn.style.position = 'relative';
                btn.appendChild(picker);
                var closePicker = function (e) {
                    if (!picker.contains(e.target) && e.target !== btn) {
                        picker.remove();
                        document.removeEventListener('click', closePicker);
                    }
                };
                setTimeout(function () { document.addEventListener('click', closePicker); }, 0);
            });
        });
    },

    initSellerNotes() {
        document.querySelectorAll('[data-notes-trigger]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var requestId = this.dataset.notesTrigger;
                var panel = document.getElementById('notesSlidePanel');
                if (!panel) return;
                panel.classList.remove('hidden');
                var body = panel.querySelector('.notes-panel-body');
                if (body) {
                    body.innerHTML = '<div class="flex items-center justify-center p-8"><div class="animate-spin w-6 h-6 border-2 border-[#c8a96a] border-t-transparent rounded-full"></div></div>';
                }
                axios.get('/admin/requests/' + requestId + '/notes')
                    .then(function (response) {
                        if (!body) return;
                        var data = response.data;
                        if (!data || data.length === 0) {
                            body.innerHTML = '<div class="p-4 text-[#5a6b62] text-sm">No notes yet.</div>';
                        } else {
                            body.innerHTML = data.map(function (note) {
                                var userName = note.user ? note.user.name : 'Unknown';
                                var noteText = note.body || note.note || note.content || '';
                                return '<div class="p-3 border-b border-[#1f3028]">\
                                        <div class="flex items-center gap-2 mb-1">\
                                            <span class="text-[#c8a96a] text-xs font-medium">' + RequestsModule.escapeHtml(userName) + '</span>\
                                            <span class="text-[#5a6b62] text-xs">' + (note.created_at || '') + '</span>\
                                        </div>\
                                        <div class="text-[#ece8e0] text-sm">' + RequestsModule.escapeHtml(noteText) + '</div>\
                                    </div>';
                            }).join('');
                        }
                    })
                    .catch(function () {
                        if (body) body.innerHTML = '<div class="p-4 text-red-400 text-sm">Failed to load notes.</div>';
                    });

                var closeBtn = panel.querySelector('.notes-panel-close');
                if (closeBtn) {
                    closeBtn.onclick = function () { panel.classList.add('hidden'); };
                }

                var form = panel.querySelector('.notes-panel-form');
                if (form) {
                    form.onsubmit = function (e) {
                        e.preventDefault();
                        var textarea = form.querySelector('textarea');
                        var noteBody = textarea ? textarea.value.trim() : '';
                        if (!noteBody) return;
                        var submitBtn = form.querySelector('button[type="submit"]');
                        if (submitBtn) submitBtn.disabled = true;
                        axios.post('/admin/requests/' + requestId + '/notes', { body: noteBody })
                            .then(function () {
                                if (textarea) textarea.value = '';
                                RequestsModule.loadTable();
                                RequestsModule.initSellerNotes();
                            })
                            .catch(function () { alert('Failed to save note'); })
                            .finally(function () { if (submitBtn) submitBtn.disabled = false; });
                    };
                }
            });
        });
    },

    initBulkActions() {
        var selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                document.querySelectorAll('.request-checkbox').forEach(function (cb) { cb.checked = this.checked; }.bind(this));
                RequestsModule.updateBulkActionsVisibility();
            });
        }
        document.querySelectorAll('.request-checkbox').forEach(function (cb) {
            cb.addEventListener('change', function () { RequestsModule.updateBulkActionsVisibility(); });
        });
        var bulkActionBtn = document.getElementById('bulkActionApply');
        if (bulkActionBtn) {
            bulkActionBtn.addEventListener('click', function () {
                var checked = document.querySelectorAll('.request-checkbox:checked');
                var ids = Array.from(checked).map(function (cb) { return cb.value; });
                var action = document.getElementById('bulkActionSelect') ? document.getElementById('bulkActionSelect').value : '';
                if (ids.length === 0) { alert('Select at least one request.'); return; }
                if (!action) { alert('Select an action.'); return; }
                if (action === 'delete' && !confirm('Delete ' + ids.length + ' request(s)?')) return;
                axios.post(this.url('/admin/requests/bulk-action'), { ids: ids, action: action })
                    .then(function () {
                        RequestsModule.loadTable();
                        RequestsModule.updateBulkActionsVisibility();
                    })
                    .catch(function (error) {
                        alert((error.response && error.response.data && error.response.data.message) || 'Bulk action failed');
                    });
            });
        }
    },

    updateBulkActionsVisibility() {
        var checked = document.querySelectorAll('.request-checkbox:checked').length;
        var bar = document.getElementById('bulkActionsBar');
        if (bar) bar.classList.toggle('hidden', checked === 0);
        var countEl = document.getElementById('selectedCount');
        if (countEl) countEl.textContent = checked + ' selected';
    },

    initConvertToQuote() {
        document.querySelectorAll('[data-convert-quote]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!confirm('Convert this request to a quote?')) return;
                var requestId = this.dataset.convertQuote;
                axios.post('/admin/requests/' + requestId + '/convert-to-quote')
                    .then(function (response) {
                        var data = response.data;
                        window.location.href = data.redirect || '/admin/quotations/' + data.quotation_id;
                    })
                    .catch(function (error) {
                        alert((error.response && error.response.data && error.response.data.message) || 'Error converting to quote');
                    });
            });
        });
    },

    initDateCalculations() {
        var arrival = document.getElementById('arrival_date');
        var departure = document.getElementById('departure_date');
        var nights = document.getElementById('nights');
        if (arrival && departure && nights) {
            var calc = function () {
                if (arrival.value && departure.value) {
                    var a = new Date(arrival.value);
                    var d = new Date(departure.value);
                    var diff = Math.round((d - a) / (1000 * 60 * 60 * 24));
                    nights.value = diff > 0 ? diff : 0;
                }
            };
            arrival.addEventListener('change', calc);
            departure.addEventListener('change', calc);
        }
    },

    initTaskManagement() {
        var taskForm = document.getElementById('taskForm');
        if (taskForm) {
            taskForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var requestId = this.dataset.requestId;
                var formData = new FormData(this);
                var data = {};
                formData.forEach(function (value, key) { data[key] = value; });
                var btn = this.querySelector('button[type="submit"]');
                if (btn) btn.disabled = true;
                axios.post('/admin/requests/' + requestId + '/tasks', data)
                    .then(function () {
                        taskForm.reset();
                        RequestsModule.loadTasks(requestId);
                    })
                    .catch(function (error) {
                        alert((error.response && error.response.data && error.response.data.message) || 'Failed to add task');
                    })
                    .finally(function () { if (btn) btn.disabled = false; });
            });
        }
        document.addEventListener('click', function (e) {
            var toggle = e.target.closest('[data-task-complete]');
            if (toggle) {
                var requestId = toggle.dataset.requestId;
                var taskId = toggle.dataset.taskComplete;
                axios.put('/admin/requests/' + requestId + '/tasks/' + taskId + '/complete')
                    .then(function () { RequestsModule.loadTasks(requestId); })
                    .catch(function () { alert('Failed to update task'); });
            }
        });
    },

    loadTasks(requestId) {
        var container = document.getElementById('tasksContainer');
        if (!container) return;
        axios.get('/admin/requests/' + requestId + '/tasks')
            .then(function (response) {
                var data = response.data;
                if (!data || data.length === 0) {
                    container.innerHTML = '<div class="text-[#5a6b62] text-sm p-4">No tasks.</div>';
                } else {
                    container.innerHTML = data.map(function (task) {
                        var taskTitle = task.title || task.name || '';
                        var completed = task.status === 'completed' || task.completed;
                        return '<div class="flex items-center gap-2 p-2 border-b border-[#1f3028] ' + (completed ? 'opacity-50' : '') + '">\
                                <input type="checkbox" data-task-complete="' + task.id + '" data-request-id="' + requestId + '" ' + (completed ? 'checked' : '') + ' class="rounded border-[#1f3028] bg-[#0f1a14] text-[#c8a96a]">\
                                <span class="text-sm text-[#ece8e0] ' + (completed ? 'line-through' : '') + '">' + RequestsModule.escapeHtml(taskTitle) + '</span>\
                                ' + (task.due_date ? '<span class="text-xs text-[#5a6b62] ml-auto">' + task.due_date + '</span>' : '') + '\
                            </div>';
                    }).join('');
                }
            });
    },

    initFileUploads() {
        var fileInput = document.getElementById('fileInput');
        var uploadBtn = document.getElementById('fileUploadBtn');
        if (!fileInput || !uploadBtn) return;
        uploadBtn.addEventListener('click', function () { fileInput.click(); });
        fileInput.addEventListener('change', function () {
            var requestId = this.dataset.requestId;
            var files = this.files;
            if (files.length === 0) return;
            var formData = new FormData();
            for (var i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }
            var progressBar = document.getElementById('uploadProgress');
            if (progressBar) progressBar.classList.remove('hidden');
            axios.post('/admin/requests/' + requestId + '/files', formData, {
                headers: { 'Content-Type': 'multipart/form-data' },
                onUploadProgress: function (progressEvent) {
                    var percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    var bar = document.getElementById('uploadProgressBar');
                    if (bar) { bar.style.width = percent + '%'; bar.textContent = percent + '%'; }
                }
            })
                .then(function () {
                    RequestsModule.loadFiles(requestId);
                    if (fileInput) fileInput.value = '';
                })
                .catch(function (error) {
                    alert((error.response && error.response.data && error.response.data.message) || 'Upload failed');
                })
                .finally(function () {
                    if (progressBar) progressBar.classList.add('hidden');
                    var bar = document.getElementById('uploadProgressBar');
                    if (bar) { bar.style.width = '0%'; bar.textContent = ''; }
                });
        });
    },

    loadFiles(requestId) {
        var container = document.getElementById('filesContainer');
        if (!container) return;
        axios.get('/admin/requests/' + requestId + '/files')
            .then(function (response) {
                var data = response.data;
                if (!data || data.length === 0) {
                    container.innerHTML = '<div class="text-[#5a6b62] text-sm p-4">No files uploaded.</div>';
                } else {
                    container.innerHTML = data.map(function (file) {
                        var fileName = file.name || file.original_name || 'File';
                        var fileUrl = file.url || '#';
                        var fileSize = file.size ? Math.round(file.size / 1024) + ' KB' : '';
                        return '<div class="flex items-center gap-2 p-2 border-b border-[#1f3028]">\
                                <i data-lucide="file" class="w-4 h-4 text-[#5a6b62]"></i>\
                                <a href="' + fileUrl + '" target="_blank" class="text-sm text-[#c8a96a] hover:underline">' + RequestsModule.escapeHtml(fileName) + '</a>\
                                <span class="text-xs text-[#5a6b62] ml-auto">' + fileSize + '</span>\
                            </div>';
                    }).join('');
                    if (window.lucide) window.lucide.createIcons();
                }
            });
    },

    initTimeline() {
        var timelineBtn = document.getElementById('loadTimeline');
        if (!timelineBtn) return;
        timelineBtn.addEventListener('click', function () {
            var requestId = this.dataset.requestId;
            var container = document.getElementById('timelineContainer');
            if (!container) return;
            container.innerHTML = '<div class="flex items-center justify-center p-8"><div class="animate-spin w-6 h-6 border-2 border-[#c8a96a] border-t-transparent rounded-full"></div></div>';
            axios.get('/admin/requests/' + requestId + '/timeline')
                .then(function (response) {
                    var events = response.data;
                    if (!events || events.length === 0) {
                        container.innerHTML = '<div class="text-[#5a6b62] text-sm p-4">No timeline events.</div>';
                    } else {
                        container.innerHTML = events.map(function (event) {
                            var icon = event.type === 'status' ? 'refresh-cw' :
                                event.type === 'note' ? 'message-square' :
                                event.type === 'task' ? 'check-circle' :
                                event.type === 'file' ? 'paperclip' : 'circle';
                            var desc = event.description || '';
                            var createdAt = event.created_at || '';
                            var userName = event.user ? event.user.name : '';
                            return '<div class="flex gap-3 p-3 border-l-2 border-[#1f3028] ml-3 relative">\
                                    <div class="absolute -left-[9px] top-3 w-4 h-4 rounded-full bg-[#0f1a14] border-2 border-[#c8a96a] flex items-center justify-center">\
                                        <i data-lucide="' + icon + '" class="w-2.5 h-2.5 text-[#c8a96a]"></i>\
                                    </div>\
                                    <div class="ml-4 flex-1">\
                                        <div class="text-[#ece8e0] text-sm">' + RequestsModule.escapeHtml(desc) + '</div>\
                                        <div class="text-[#5a6b62] text-xs mt-1">' + createdAt + (userName ? ' by ' + RequestsModule.escapeHtml(userName) : '') + '</div>\
                                    </div>\
                                </div>';
                        }).join('');
                        if (window.lucide) window.lucide.createIcons();
                    }
                })
                .catch(function () {
                    container.innerHTML = '<div class="text-red-400 text-sm p-4">Failed to load timeline.</div>';
                });
        });
    }
};

document.addEventListener('DOMContentLoaded', function () {
    RequestsModule.init();
});
