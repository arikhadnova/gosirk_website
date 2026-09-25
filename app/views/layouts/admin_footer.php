    </div>
    <!-- /#page-content-wrapper -->
</div>
<!-- /#wrapper -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Ctrl+K: jump to any page, tab or setting -->
<div class="modal fade admin-search-modal" id="adminSearchModal" tabindex="-1" aria-label="Cari menu" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="margin-top: 12vh;">
        <div class="modal-content">
            <input type="search" class="form-control search-input" id="adminSearchInput" placeholder="Cari halaman, bagian, atau pengaturan… (mis. whatsapp, logo, SEO home)" autocomplete="off">
            <div class="admin-search-results" id="adminSearchResults"></div>
        </div>
    </div>
</div>
<script>
(function () {
    const index = <?= json_encode(AdminNav::searchIndex(($_SESSION['user_role'] ?? '') === 'admin'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?>;
    const modalEl = document.getElementById('adminSearchModal');
    const input = document.getElementById('adminSearchInput');
    const list = document.getElementById('adminSearchResults');
    const norm = (t) => t.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    let shown = [], selected = 0;

    function render() {
        const words = norm(input.value).split(/\s+/).filter(Boolean);
        shown = index.filter(([label, hint, url, kw]) => {
            const hay = norm(label + ' ' + hint + ' ' + kw);
            return words.every((w) => hay.includes(w));
        }).slice(0, 40);
        selected = 0;
        list.innerHTML = shown.length
            ? shown.map(([label, hint, url], i) => `<a href="${url}" class="${i === 0 ? 'selected' : ''}"><span></span><small></small></a>`).join('')
            : '<div class="admin-search-empty">Tidak ditemukan.</div>';
        list.querySelectorAll('a').forEach((a, i) => { a.firstChild.textContent = shown[i][0]; a.lastChild.textContent = shown[i][1]; });
    }
    function move(d) {
        const links = list.querySelectorAll('a');
        if (!links.length) return;
        links[selected].classList.remove('selected');
        selected = (selected + d + links.length) % links.length;
        links[selected].classList.add('selected');
        links[selected].scrollIntoView({ block: 'nearest' });
    }
    function open() { bootstrap.Modal.getOrCreateInstance(modalEl).show(); }

    modalEl.addEventListener('shown.bs.modal', () => { input.value = ''; render(); input.focus(); });
    input.addEventListener('input', render);
    input.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowDown') { e.preventDefault(); move(1); }
        if (e.key === 'ArrowUp') { e.preventDefault(); move(-1); }
        if (e.key === 'Enter' && shown[selected]) { e.preventDefault(); location.href = shown[selected][2]; }
    });
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); open(); }
    });
    document.getElementById('adminSearchOpen')?.addEventListener('click', open);

    // Phones: the tab row scrolls, so bring the active tab into view
    const tabs = document.querySelector('.hub-tabs'), activeTab = tabs?.querySelector('.hub-tab.active');
    if (tabs && activeTab && tabs.scrollWidth > tabs.clientWidth) {
        tabs.scrollLeft = activeTab.offsetLeft - tabs.offsetLeft - (tabs.clientWidth - activeTab.offsetWidth) / 2;
    }
})();
</script>

<?php // Show any pending notification (e.g. validation errors on create/edit pages that don't render it themselves)
Flasher::flash(); ?>

<script>
    function toggleSidebar() {
        const wrapper = document.getElementById("wrapper");
        const overlay = document.getElementById("sidebar-overlay");
        
        // Toggle the wrapper class
        wrapper.classList.toggle("toggled");
        const isOpen = wrapper.classList.contains("toggled");
        
        // Handle overlay and body scroll
        if (overlay) {
            if (isOpen) {
                overlay.classList.remove("d-none");
                overlay.classList.add("show");
                if (window.innerWidth < 768) {
                    document.body.classList.add("sidebar-open");
                }
            } else {
                overlay.classList.add("d-none");
                overlay.classList.remove("show");
                document.body.classList.remove("sidebar-open");
            }
        }
        
        // Toggle icon on menu-toggle
        const btn = document.getElementById("menu-toggle");
        if (btn) {
            const icon = btn.querySelector('i');
            if (icon) {
                if (isOpen) {
                    icon.classList.remove('fa-indent');
                    icon.classList.add('fa-outdent');
                } else {
                    icon.classList.remove('fa-outdent');
                    icon.classList.add('fa-indent');
                }
            }
        }
    }

    // Attach listeners
    document.getElementById("menu-toggle").addEventListener("click", function(e) {
        e.preventDefault();
        toggleSidebar();
    });

    const overlay = document.getElementById("sidebar-overlay");
    if (overlay) {
        overlay.addEventListener("click", function(e) {
            e.preventDefault();
            toggleSidebar();
        });
    }

    const closeBtn = document.getElementById("sidebar-close");
    if (closeBtn) {
        closeBtn.addEventListener("click", function(e) {
            e.preventDefault();
            toggleSidebar();
        });
    }

    // Long sidebar menu: bring the active item into view
    (function () {
        const list = document.querySelector('#sidebar-wrapper .list-group');
        const active = list && list.querySelector('.list-group-item.active');
        // only when it is hidden below the fold (keeps the top of the menu visible otherwise)
        if (active && active.offsetTop + active.offsetHeight > list.clientHeight) {
            list.scrollTop = active.offsetTop - list.clientHeight / 2 + active.offsetHeight / 2;
        }
    })();

    // Header title follows the page's own heading (controller titles differ in wording/language)
    (function () {
        const h1 = document.querySelector('.admin-header-section h1, #page-content-wrapper h1');
        const target = document.querySelector('.admin-topbar-title');
        if (h1 && target && h1.textContent.trim()) target.textContent = h1.textContent.trim();
    })();

    // ---- Form validation helpers (rules come from FormRules via HTML attributes) ----
    const uploadLimit = <?= (int) Upload::maxBytes('img') ?>;
    const uploadLimitPdf = <?= (int) Upload::maxBytes('pdf') ?>;
    const postLimit = <?= (int) Upload::maxPostBytes() ?>;

    // Rich-text (CKEditor) fields: the textarea is hidden, so read the editor's visible text
    const editorText = (ta) => {
        const editable = ta.nextElementSibling && ta.nextElementSibling.querySelector('.ck-editor__editable');
        return (editable ? editable.innerText : ta.value.replace(/<[^>]*>/g, '')).trim();
    };
    const fmtMB = (b) => (b / 1048576).toFixed(1).replace('.0', '') + ' MB';

    // Indonesian message for a field that fails the browser's constraint checks
    const validationMessage = (el) => {
        const label = el.dataset.label || 'Kolom ini';
        const v = el.validity;
        if (v.valueMissing) return el.type === 'file' ? `${label} wajib diupload.` : (el.tagName === 'SELECT' ? `${label} wajib dipilih.` : `${label} wajib diisi.`);
        if (v.tooShort) return `${label} minimal ${el.minLength} karakter (sekarang ${el.value.length}).`;
        if (v.tooLong) return `${label} maksimal ${el.maxLength} karakter.`;
        if (v.typeMismatch && el.type === 'email') return `${label} harus berupa alamat email yang valid.`;
        if (v.typeMismatch && el.type === 'url') return `${label} harus berupa URL yang valid, contoh: https://contoh.com`;
        if (v.rangeUnderflow) return `${label} tidak boleh kurang dari ${el.min}.`;
        if (v.badInput || v.stepMismatch) return `${label} harus berupa angka.`;
        if (v.patternMismatch) return `Format ${label} tidak valid.`;
        return el.validationMessage;
    };

    // Label element that belongs to a field
    const labelFor = (el) => {
        if (el.id) {
            const byFor = document.querySelector(`label[for="${el.id}"]`);
            if (byFor) return byFor;
        }
        const wrap = el.closest('.mb-0, .mb-2, .mb-3, .mb-4, [class*="col-"]');
        return wrap ? wrap.querySelector('label.form-label, label') : null;
    };

    const showFieldError = (el, msg) => {
        el.classList.add('is-invalid');
        const host = el.closest('.input-group') || el;
        let fb = host.parentElement.querySelector(':scope > .invalid-feedback.js-feedback');
        if (!fb) {
            fb = document.createElement('div');
            fb.className = 'invalid-feedback js-feedback d-block';
            host.insertAdjacentElement('afterend', fb);
        }
        fb.textContent = msg;
    };

    const clearFieldError = (el) => {
        el.classList.remove('is-invalid');
        const host = el.closest('.input-group') || el;
        const fb = host.parentElement && host.parentElement.querySelector(':scope > .invalid-feedback.js-feedback');
        if (fb) fb.remove();
    };

    document.querySelectorAll('form[method="POST"], form[method="post"]').forEach((form) => {
        const fields = form.querySelectorAll('input, textarea, select');

        // Red asterisk on labels of required fields
        fields.forEach((el) => {
            if ((!el.required && !el.hasAttribute('data-editor-required')) || el.type === 'checkbox' || el.type === 'radio' || el.type === 'hidden') return;
            const label = labelFor(el);
            if (label && !label.querySelector('.required-mark')) {
                label.insertAdjacentHTML('beforeend', ' <span class="required-mark text-danger" title="Wajib diisi">*</span>');
            }
        });

        // Show our own inline messages instead of the browser's bubbles
        form.setAttribute('novalidate', 'novalidate');
        form.addEventListener('submit', (e) => {
            const invalidEls = [...form.querySelectorAll('input, textarea, select')].filter((el) => !el.disabled && el.offsetParent !== null && !el.checkValidity());
            form.querySelectorAll('.is-invalid').forEach(clearFieldError);
            const errors = invalidEls.map((el) => ({ el, msg: validationMessage(el) }));

            // Required / minimum-length rich-text editors
            form.querySelectorAll('textarea[data-editor-required], textarea[data-editor-min]').forEach((ta) => {
                const text = editorText(ta);
                const label = ta.dataset.label || 'Konten';
                const min = parseInt(ta.dataset.editorMin || '0', 10);
                const box = ta.nextElementSibling && ta.nextElementSibling.classList.contains('ck-editor') ? ta.nextElementSibling : ta;
                if (ta.hasAttribute('data-editor-required') && !text) errors.push({ el: box, msg: `${label} wajib diisi.` });
                else if (text && min && text.length < min) errors.push({ el: box, msg: `${label} minimal ${min} karakter (sekarang ${text.length}).` });
            });

            // All files together must fit in one request
            let total = 0;
            form.querySelectorAll('input[type="file"]').forEach((el) => { for (const f of el.files || []) total += f.size; });
            if (postLimit && total > postLimit * 0.95) {
                errors.push({ el: null, msg: `Total ukuran semua file (${fmtMB(total)}) melebihi batas server ${fmtMB(postLimit)} per simpan. Simpan dengan sebagian file dulu, lalu tambahkan sisanya lewat edit.` });
            }

            if (!errors.length) return;

            e.preventDefault();
            e.stopImmediatePropagation();
            const messages = errors.map(({ el, msg }) => { if (el) showFieldError(el, msg); return msg; });
            const firstEl = errors.find((x) => x.el);
            const invalid = firstEl ? [firstEl.el] : [form];
            invalid[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (invalid[0].focus) setTimeout(() => invalid[0].focus({ preventScroll: true }), 300);
            Swal.fire({
                icon: 'error',
                title: 'Data belum lengkap',
                html: '<ul style="text-align:left;margin:0;padding-left:1.2rem;font-size:14px;">' + messages.map((m) => `<li>${m.replace(/</g, '&lt;')}</li>`).join('') + '</ul>',
                confirmButtonColor: '#0D4A7C'
            });
        }, true);

        form.addEventListener('input', (e) => { if (e.target.classList.contains('is-invalid') && e.target.checkValidity()) clearFieldError(e.target); });
        form.addEventListener('keyup', (e) => { const box = e.target.closest && e.target.closest('.ck-editor.is-invalid'); if (box) clearFieldError(box); });
        form.addEventListener('change', (e) => { if (e.target.classList.contains('is-invalid') && e.target.checkValidity()) clearFieldError(e.target); });
    });

    // Refill the form after the server rejected a save (values kept in the session once)
    const oldInput = <?= json_encode((object) Flasher::takeOldInput(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    if (Object.keys(oldInput).length) {
        const form = [...document.querySelectorAll('form[method="POST"], form[method="post"]')]
            .find((f) => Object.keys(oldInput).some((k) => f.querySelector(`[name="${CSS.escape(k)}"]`)));
        if (form) {
            Object.entries(oldInput).forEach(([name, value]) => {
                form.querySelectorAll(`[name="${CSS.escape(name)}"]`).forEach((el) => {
                    if (el.type === 'file' || el.type === 'password' || el.type === 'hidden') return;
                    if (el.type === 'checkbox' || el.type === 'radio') { el.checked = el.value === value || (el.type === 'checkbox' && value === 'on'); return; }
                    el.value = value;
                    // Rich-text editors may already be running: push the value into them too
                    const setEditor = (tries) => {
                        const editable = el.nextElementSibling && el.nextElementSibling.querySelector('.ck-editor__editable');
                        if (editable && editable.ckeditorInstance) editable.ckeditorInstance.setData(value);
                        else if (el.tagName === 'TEXTAREA' && tries > 0) setTimeout(() => setEditor(tries - 1), 200);
                    };
                    if (el.tagName === 'TEXTAREA') setEditor(10);
                });
            });
            // Browsers can't refill file inputs: ask for the file again where it is required
            form.querySelectorAll('input[type="file"][required]').forEach((el) => showFieldError(el, `${el.dataset.label || 'File'}: silakan pilih ulang file.`));
            // Unchecked checkboxes are not submitted at all
            form.querySelectorAll('input[type="checkbox"][name]').forEach((cb) => { if (!(cb.name in oldInput)) cb.checked = false; });
            form.querySelectorAll('select, input[type="checkbox"]').forEach((el) => el.dispatchEvent(new Event('change', { bubbles: true })));
        }
    }

    // Add https:// to URL fields typed without a scheme
    document.addEventListener('change', function (e) {
        const el = e.target;
        if (el.type === 'url' && el.value.trim() && !/^[a-z][a-z0-9+.-]*:\/\//i.test(el.value.trim())) {
            el.value = 'https://' + el.value.trim().replace(/^\/+/, '');
        }
    }, true);

    // Reject files over the server limit before uploading
    document.addEventListener('change', function (e) {
        const el = e.target;
        if (el.type !== 'file' || !el.files) return;
        for (const f of el.files) {
            const limit = /\.pdf$/i.test(f.name) ? uploadLimitPdf : uploadLimit;
            if (f.size > limit) {
                el.value = '';
                Swal.fire({
                    icon: 'error',
                    title: 'File terlalu besar',
                    html: `<strong>${f.name}</strong> berukuran ${fmtMB(f.size)}.<br>Maksimal ${fmtMB(limit)} per file.`,
                    confirmButtonColor: '#0D4A7C'
                });
                return;
            }
        }
    }, true);

    // Global Delete Confirmation with SweetAlert2
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-delete-confirm')) {
            e.preventDefault();
            const button = e.target.closest('.btn-delete-confirm');
            const url = button.getAttribute('href');
            const message = button.getAttribute('data-confirm-message') || "Data ini akan dihapus permanen!";
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    });
</script>
</body>
</html>
