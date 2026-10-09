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
    const centerTab = () => {
        if (tabs && activeTab && tabs.scrollWidth > tabs.clientWidth) {
            tabs.scrollLeft = activeTab.offsetLeft - (tabs.clientWidth - activeTab.offsetWidth) / 2; // .hub-tabs is the offsetParent
        }
    };
    centerTab();
    window.addEventListener('load', centerTab); // again once fonts/icons have their final width
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

    // Edit modals filled by JS: set the English field and what it was (so unchanged English is kept on save)
    window.fillEn = function (form, field, idValue, enValue) {
        const set = (name, v) => { const el = form.querySelector(`[name="${name}"]`); if (el) el.value = v || ''; };
        set(field + '_en', enValue); set(field + '_en_was', enValue); set(field + '_id_was', idValue);
        form.querySelector(`[name="${field}_en"]`)?.dispatchEvent(new Event('input'));
    };

    // One ID | EN toggle for the whole page (app/core/EnField.php), shown in the page header:
    // in EN mode every translatable field shows its English version in the same place.
    // Runs after the page's own DOMContentLoaded handlers, so pages that follow the toggle (Teks) are ready
    document.addEventListener('DOMContentLoaded', function () {
        const ens = [...document.querySelectorAll('.en-input')];
        // pages with their own bilingual editor listen to the "admin-lang" event instead (data-lang-aware)
        if (!ens.length && !document.querySelector('[data-lang-aware]')) return;
        const norm = (t) => (t || '').replace(/<[^>]*>/g, ' ').replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
        const pairs = ens.map((en) => {
            const form = en.closest('form');
            const id = form && form.querySelector(`[name="${en.dataset.idName || en.dataset.enFor + '_id'}"]`);
            const idUi = () => (id && id.nextElementSibling && id.nextElementSibling.classList && id.nextElementSibling.classList.contains('ck-editor')) ? id.nextElementSibling : id;
            const idText = () => { const ui = idUi(), ed = ui !== id && ui.querySelector('.ck-editor__editable'); return ed && ed.ckeditorInstance ? ed.ckeditorInstance.getData() : (id ? id.value : ''); };
            return { en, id, idUi, idText, editor: null };
        });
        const enText = (p) => p.editor ? p.editor.getData() : p.en.value;
        const setEn = (p, v) => { if (p.editor) p.editor.setData(v); else p.en.value = v; };
        const setId = (p, v) => { const ui = p.idUi(), ed = ui !== p.id && ui.querySelector('.ck-editor__editable'); if (ed && ed.ckeditorInstance) ed.ckeditorInstance.setData(v); else if (p.id) p.id.value = v; };
        // fields inside a closed modal (e.g. "Tambah" forms) are not counted
        const visible = (p) => !p.en.closest('.modal') || p.en.closest('.modal').classList.contains('show');
        // English equal to the Indonesian counts as untranslated, unless the translator itself gave that text back
        // (names, words that are the same in both languages): p.sameOk holds the text it confirmed
        const sameConfirmed = (p) => p.sameOk !== undefined && norm(p.sameOk) === norm(p.idText());
        const missing = () => pairs.filter((p) => visible(p) && norm(p.idText()) !== '' && (norm(enText(p)) === '' || (norm(enText(p)) === norm(p.idText()) && !sameConfirmed(p))));

        // In a page hub the toggle sits in the header; elsewhere above the page content
        let bar = document.getElementById('hubLang');
        if (!bar) {
            bar = document.createElement('div');
            bar.className = 'hub-lang mb-3';
            (document.querySelector('.admin-header-section') || document.querySelector('#page-content-wrapper > .container-fluid')).after(bar);
        }
        bar.classList.remove('d-none');
        bar.innerHTML = '<div class="btn-group btn-group-sm" role="group" aria-label="Bahasa teks yang diedit" title="Bahasa teks yang diedit di halaman ini">'
            + '<button type="button" class="btn btn-dark" data-lang="id">ID</button><button type="button" class="btn btn-light" data-lang="en">EN</button></div>'
            + '<button type="button" class="btn btn-outline-primary btn-sm lang-translate"></button>'
            + '<button type="button" class="btn btn-link btn-sm p-0 lang-review d-none"></button>'
            + '<span class="badge en-mode-badge d-none"><i class="fas fa-language me-1"></i> Sedang mengedit versi Inggris</span>';
        const enBadge = bar.querySelector('.en-mode-badge');
        const translateBtn = bar.querySelector('.lang-translate');
        const reviewBtn = bar.querySelector('.lang-review');
        let lang = 'id';
        const langKey = 'gosirk_admin_lang:' + location.pathname + location.search;

        // What the field held when the page opened (hidden *_was inputs from EnField, refreshed by fillEn in modals)
        const sameText = (a, b) => norm(a) === norm(b);
        const was = (p, side) => { const el = p.en.form && p.en.form.querySelector(`[name="${p.en.dataset.enFor}_${side}_was"]`); return el ? el.value : null; };
        // Indonesian changed but English left as it was: the admin should look at the English too
        const stale = (p) => { const idWas = was(p, 'id'), enWas = was(p, 'en');
            return visible(p) && idWas !== null && norm(p.idText()) !== '' && !sameText(p.idText(), idWas) && enWas !== null && sameText(enText(p), enWas); };
        // Repeated rows (highlight captions): an English input [data-en-row] next to its Indonesian one in the
        // same row. Looked up each time, so rows added after the page loaded are included in the bar button.
        const rowPairs = () => [...document.querySelectorAll('[data-en-row]')].map((en) => {
            const id = en.parentElement.querySelector('.caption-id');
            return id && { en, id, idUi: () => id, idText: () => id.value, editor: null, enWasEl: en.parentElement.querySelector('.caption-en-was') };
        }).filter((p) => p && visible(p));
        const needed = () => { const m = missing(); return pairs.filter((p) => m.includes(p) || stale(p))
            .concat(rowPairs().filter((p) => norm(p.idText()) !== '' && (norm(enText(p)) === '' || (sameText(enText(p), p.idText()) && !sameConfirmed(p))))); };
        const translatable = () => pairs.concat(rowPairs()).filter((p) => visible(p) && norm(p.idText()) !== '');
        // The other way round (ID mode): Indonesian fields that are empty or still hold the English text
        const neededId = () => pairs.concat(rowPairs()).filter((p) => visible(p) && norm(enText(p)) !== '' && (norm(p.idText()) === '' || sameText(p.idText(), enText(p))));
        const translatableId = () => pairs.concat(rowPairs()).filter((p) => visible(p) && norm(enText(p)) !== '');

        // A label above every English field, so text is never typed into the English version by mistake,
        // with a button to translate just that field from the Indonesian text
        pairs.forEach((p) => {
            p.en.insertAdjacentHTML('beforebegin', '<div class="en-field-label d-none"><i class="fas fa-language me-1"></i> Versi Inggris (EN)'
                + '<span class="en-stale-hint d-none"> · teks ID berubah, cek lagi</span>'
                + '</div><div class="id-field-tools d-none"><button type="button" class="id-translate-one en-translate-one" title="Ganti teks EN dengan terjemahan teks ID"><i class="fas fa-sync-alt me-1"></i>Terjemahkan dari ID</button></div>');
            // same right-aligned button row as the Indonesian fields, above the English field
            p.enTools = p.en.previousElementSibling;
            p.label = p.enTools.previousElementSibling;
            // In ID mode each Indonesian field gets the reverse button: translate the English text into it
            if (p.id) {
                p.id.insertAdjacentHTML('beforebegin', '<div class="id-field-tools"><button type="button" class="id-translate-one" title="Ganti teks ID dengan terjemahan bahasa Indonesia dari teks EN"><i class="fas fa-sync-alt me-1"></i>Terjemahkan dari EN</button></div>');
                p.idTools = p.id.previousElementSibling;
                p.idTools.querySelector('.id-translate-one').addEventListener('click', async (e) => {
                    const btn = e.currentTarget;
                    if (norm(enText(p)) === '') { Swal.fire({ icon: 'info', title: 'Teks EN masih kosong', text: 'Isi teks bahasa Inggris dulu.' }); return; }
                    if (norm(p.idText()) !== '' && !sameText(p.idText(), enText(p))) {
                        const r = await Swal.fire({ icon: 'question', title: 'Ganti teks ID?', text: 'Teks bahasa Indonesia di kolom ini akan diganti dengan terjemahan dari teks EN.',
                            showCancelButton: true, confirmButtonText: 'Ya, ganti', cancelButtonText: 'Batal', confirmButtonColor: '#0D4A7C' });
                        if (!r.isConfirmed) return;
                    }
                    btn.disabled = true;
                    const ok = await translateToId(p);
                    btn.disabled = false;
                    refresh();
                    if (!ok) Swal.fire({ icon: 'warning', title: 'Belum diterjemahkan', text: 'Layanan terjemahan sedang sibuk. Coba lagi sebentar lagi, atau tulis versi Indonesianya sendiri.' });
                });
            }
            p.enTools.querySelector('.en-translate-one').addEventListener('click', async (e) => {
                const btn = e.currentTarget;
                if (norm(p.idText()) === '') { Swal.fire({ icon: 'info', title: 'Teks ID masih kosong', text: 'Isi teks bahasa Indonesia dulu.' }); return; }
                if (!(await confirmReplaceEn(p))) return;
                btn.disabled = true;
                const ok = await translateOne(p);
                btn.disabled = false;
                refresh();
                if (!ok) Swal.fire({ icon: 'warning', title: 'Belum diterjemahkan', text: 'Layanan terjemahan sedang sibuk. Coba lagi sebentar lagi, atau tulis versi Inggrisnya sendiri.' });
            });
        });

        // Note under an Indonesian field that changed while its English did not (created when first needed,
        // after the rich-text editor so it sits below it)
        const noteFor = (p) => {
            if (p.note) return p.note;
            const ui = p.idUi();
            if (!ui) return null;
            ui.insertAdjacentHTML('afterend', '<div class="id-change-note d-none" role="status"><i class="fas fa-exclamation-circle me-1"></i>'
                + 'Teks ID berubah, versi EN-nya belum kamu cek. Kalau dibiarkan, EN diterjemahkan ulang otomatis saat disimpan. '
                + '<button type="button" class="btn btn-link btn-sm p-0 align-baseline note-translate">Terjemahkan sekarang</button> · '
                + '<button type="button" class="btn btn-link btn-sm p-0 align-baseline note-view">Lihat EN</button></div>');
            p.note = ui.nextElementSibling;
            p.note.querySelector('.note-view').addEventListener('click', () => { show('en'); p.label.scrollIntoView({ behavior: 'smooth', block: 'center' }); });
            p.note.querySelector('.note-translate').addEventListener('click', async (e) => {
                e.currentTarget.disabled = true;
                const ok = await translateOne(p);
                refresh();
                if (ok) { show('en'); p.label.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
                else Swal.fire({ icon: 'warning', title: 'Belum diterjemahkan', text: 'Layanan terjemahan sedang sibuk. Coba lagi sebentar lagi.' });
            });
            return p.note;
        };

        const refresh = () => {
            const todo = needed();
            pairs.forEach((p) => {
                const isStale = stale(p);
                p.label.querySelector('.en-stale-hint').classList.toggle('d-none', !isStale);
                const note = isStale ? noteFor(p) : p.note;
                if (note) {
                    note.classList.toggle('d-none', !isStale || lang !== 'id');
                    note.querySelector('.note-translate').disabled = false;
                }
            });
            reviewBtn.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i>${todo.length} kolom EN perlu dicek`;
            reviewBtn.classList.toggle('d-none', todo.length === 0);
            // The bar button translates into the language being edited
            translateBtn.innerHTML = lang === 'en' ? '<i class="fas fa-language me-1"></i> Terjemahkan ID → EN' : '<i class="fas fa-language me-1"></i> Terjemahkan EN → ID';
            translateBtn.classList.toggle('d-none', (lang === 'en' ? translatable() : translatableId()).length === 0);
        };

        // English -> Indonesian into the ID field. The English was the source, so it is right as it is: mark it as
        // the admin's own text so saving keeps it (Admin::en) instead of translating the new Indonesian back
        async function translateToId(p) {
            try {
                const fd = new FormData(); fd.append('text', enText(p)); fd.append('to', 'id');
                const res = await fetch('<?= BASE_URL ?>admin/translate', { method: 'POST', body: fd }).then((r) => r.json());
                if (res.status !== 'success') return false;
                setId(p, res.text);
                const enWas = p.enWasEl || (p.en.form && p.en.form.querySelector(`[name="${p.en.dataset.enFor}_en_was"]`));
                if (enWas) enWas.value = '';
                return true;
            } catch (e) { return false; }
        }

        // Before a single-field translation replaces English that differs from the Indonesian (likely written by hand)
        const confirmReplaceEn = async (p) => {
            if (norm(enText(p)) === '' || sameText(enText(p), p.idText())) return true;
            const r = await Swal.fire({ icon: 'question', title: 'Ganti teks EN?', text: 'Teks bahasa Inggris di kolom ini akan diganti dengan terjemahan dari teks ID.',
                showCancelButton: true, confirmButtonText: 'Ya, ganti', cancelButtonText: 'Batal', confirmButtonColor: '#0D4A7C' });
            return r.isConfirmed;
        };

        async function translateOne(p) {
            try {
                const fd = new FormData(); fd.append('text', p.idText());
                const res = await fetch('<?= BASE_URL ?>admin/translate', { method: 'POST', body: fd }).then((r) => r.json());
                if (res.status !== 'success') return false;
                if (norm(res.text) === norm(p.idText())) p.sameOk = p.idText();
                setEn(p, res.text);
                p.en.dispatchEvent(new Event('input', { bubbles: true }));
                return true;
            } catch (e) { return false; }
        }

        // The translate button sits on the field label's line (CSS, from 576px); where that line has no room
        // left (long label, EN badge, narrow column) it drops to a row of its own instead of covering the text
        const textRect = (el) => { const r = document.createRange(); r.selectNodeContents(el); return r.getBoundingClientRect(); };
        const placeTools = () => {
            pairs.forEach((p) => [p.idTools, p.enTools].forEach((tools) => {
                if (!tools || tools.classList.contains('d-none')) return;
                tools.classList.remove('stacked');
                const btn = tools.firstElementChild.getBoundingClientRect();
                let el = tools.previousElementSibling;
                for (let i = 0; el && i < 2; i++, el = el.previousElementSibling) {
                    if (el.offsetParent === null || !['LABEL', 'DIV', 'SPAN'].includes(el.tagName)) continue;
                    // the EN badge is a coloured box: its padding counts; for labels only the text does
                    const t = el.classList.contains('en-field-label') ? el.getBoundingClientRect() : textRect(el);
                    if (t.width && t.right + 8 > btn.left && t.bottom > btn.top && t.top < btn.bottom) { tools.classList.add('stacked'); return; }
                }
            }));
        };
        let placeTimer = null;
        window.addEventListener('resize', () => { clearTimeout(placeTimer); placeTimer = setTimeout(placeTools, 100); });

        const show = (l) => {
            lang = l;
            try { sessionStorage.setItem(langKey, l); } catch (e) {}
            enBadge.classList.toggle('d-none', l !== 'en');
            bar.querySelectorAll('[data-lang]').forEach((b) => { b.classList.toggle('btn-dark', b.dataset.lang === l); b.classList.toggle('btn-light', b.dataset.lang !== l); });
            document.body.classList.toggle('lang-en-mode', l === 'en');
            document.dispatchEvent(new CustomEvent('admin-lang', { detail: l }));
            pairs.forEach((p) => {
                p.label.classList.toggle('d-none', l !== 'en');
                p.enTools.classList.toggle('d-none', l !== 'en');
                if (p.idTools) p.idTools.classList.toggle('d-none', l === 'en');
                const idEl = p.idUi();
                if (idEl) idEl.classList.toggle('d-none', l === 'en');
                if (p.en.classList.contains('en-editor')) {
                    if (l === 'en' && !p.editor && window.ClassicEditor) {
                        p.en.classList.remove('d-none');
                        ClassicEditor.create(p.en, { toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo'] })
                            .then((e) => { p.editor = e; e.model.document.on('change:data', refresh); });
                    } else if (p.editor) {
                        p.editor.ui.view.element.classList.toggle('d-none', l !== 'en');
                    }
                } else {
                    p.en.classList.toggle('d-none', l !== 'en');
                }
            });
            refresh();
            placeTools();
        };
        bar.querySelectorAll('[data-lang]').forEach((b) => b.addEventListener('click', () => show(b.dataset.lang)));
        reviewBtn.addEventListener('click', () => show('en'));
        // Translate buttons of the repeated caption rows (rows can be added later, so one listener for all)
        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('.caption-translate');
            if (!btn) return;
            const p = rowPairs().find((r) => r.en.parentElement === btn.closest('.caption-tools').parentElement);
            if (!p) return;
            const toEn = btn.dataset.dir === 'en';
            if (norm(toEn ? p.idText() : enText(p)) === '') { Swal.fire({ icon: 'info', title: toEn ? 'Keterangan ID masih kosong' : 'Keterangan EN masih kosong' }); return; }
            if (toEn ? !(await confirmReplaceEn(p)) : (norm(p.idText()) !== '' && !sameText(p.idText(), enText(p)) && !(await Swal.fire({ icon: 'question', title: 'Ganti teks ID?',
                text: 'Keterangan bahasa Indonesia akan diganti dengan terjemahan dari keterangan EN.', showCancelButton: true, confirmButtonText: 'Ya, ganti', cancelButtonText: 'Batal', confirmButtonColor: '#0D4A7C' })).isConfirmed)) return;
            btn.disabled = true;
            const ok = await (toEn ? translateOne(p) : translateToId(p));
            btn.disabled = false;
            refresh();
            if (!ok) Swal.fire({ icon: 'warning', title: 'Belum diterjemahkan', text: 'Layanan terjemahan sedang sibuk. Coba lagi sebentar lagi.' });
        });
        // Typing in any field (inputs, textareas and the rich-text editors) updates the notes
        let pending = false;
        document.addEventListener('input', () => { if (pending) return; pending = true; requestAnimationFrame(() => { pending = false; refresh(); }); });
        document.addEventListener('shown.bs.modal', () => { refresh(); placeTools(); });
        document.addEventListener('hidden.bs.modal', refresh);

        // Translate from the Indonesian text: only the fields that need it, or every field (replacing the English)
        translateBtn.addEventListener('click', async () => {
            const toEn = lang === 'en';
            const all = toEn ? translatable() : translatableId(), todo = toEn ? needed() : neededId();
            if (!all.length) return;
            const [from, to] = toEn ? ['ID', 'EN'] : ['EN', 'ID'];
            const r = await Swal.fire({
                icon: 'question',
                title: `Terjemahkan teks ${from} ke ${to}?`,
                html: todo.length
                    ? (toEn ? `<b>${todo.length}</b> kolom EN masih kosong, sama dengan teks ID, atau belum menyesuaikan teks ID yang berubah.`
                            : `<b>${todo.length}</b> kolom ID masih kosong atau masih berisi teks EN.`)
                      + `<br>Pilih <b>Semua kolom</b> untuk mengganti juga teks ${to} yang sudah ada.`
                    : `Semua kolom ${to} sudah terisi. Terjemahan baru akan <b>mengganti</b> teks ${to} yang ada di ${all.length} kolom.`,
                showDenyButton: todo.length > 0,
                showCancelButton: true,
                confirmButtonText: todo.length ? `Yang perlu saja (${todo.length})` : `Ganti semua (${all.length})`,
                denyButtonText: `Semua kolom (${all.length})`,
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0D4A7C',
                denyButtonColor: '#6c757d'
            });
            if (!r.isConfirmed && !r.isDenied) return;
            const list = r.isConfirmed && todo.length ? todo : all;
            translateBtn.disabled = true;
            let failed = 0;
            for (const [i, p] of list.entries()) {
                translateBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Menerjemahkan ${i + 1}/${list.length}…`;
                if (!(await (toEn ? translateOne(p) : translateToId(p)))) failed++;
            }
            translateBtn.disabled = false;
            refresh();
            if (failed) Swal.fire({ icon: 'warning', title: 'Sebagian belum diterjemahkan', text: 'Layanan terjemahan sedang sibuk. Coba lagi sebentar lagi, atau tulis terjemahannya sendiri.' });
            else Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: `${list.length} kolom diterjemahkan, cek hasilnya`, showConfirmButton: false, timer: 2500 });
        });
        // Pages open in Indonesian: remembering EN across pages made admins type Indonesian text into the
        // English fields. Only a refresh of the same page keeps the language it was showing.
        let reloaded = false;
        try { reloaded = performance.getEntriesByType('navigation')[0].type === 'reload'; } catch (e) {}
        let saved = null;
        try { saved = sessionStorage.getItem(langKey); } catch (e) {}
        show(reloaded && saved === 'en' ? 'en' : 'id');

        // Saving with the Indonesian field empty but the English one filled: almost always text typed in EN mode
        new Set(pairs.map((p) => p.en.closest('form')).filter(Boolean)).forEach((form) => {
            form.addEventListener('submit', (e) => {
                const swapped = pairs.filter((p) => p.en.closest('form') === form && visible(p) && norm(p.idText()) === '' && norm(enText(p)) !== '');
                if (!swapped.length) return;
                e.preventDefault();
                e.stopImmediatePropagation();
                Swal.fire({
                    icon: 'warning',
                    title: 'Versi Indonesia masih kosong',
                    text: 'Ada ' + swapped.length + ' kolom yang hanya terisi di versi Inggris (EN). Kalau teks itu sebenarnya bahasa Indonesia, pindahkan ke versi Indonesia lalu simpan lagi; versi Inggrisnya akan diterjemahkan otomatis.',
                    showCancelButton: true,
                    confirmButtonText: 'Pindahkan ke versi Indonesia',
                    cancelButtonText: 'Periksa dulu',
                    confirmButtonColor: '#0D4A7C'
                }).then((r) => {
                    if (!r.isConfirmed) return;
                    swapped.forEach((p) => { setId(p, enText(p)); setEn(p, ''); });
                    show('id');
                    Swal.fire({ icon: 'success', title: 'Sudah dipindahkan', text: 'Periksa teksnya, lalu klik simpan lagi.', confirmButtonColor: '#0D4A7C' });
                });
            }, true);
        });
        // rich-text editors of the Indonesian fields are created a moment later: apply again once ready
        window.addEventListener('load', () => setTimeout(() => {
            show(lang);
            // their changes update the notes too (typing in them does not always reach the input listener)
            pairs.forEach((p) => { const ui = p.idUi(), ed = ui !== p.id && ui.querySelector('.ck-editor__editable');
                if (ed && ed.ckeditorInstance) ed.ckeditorInstance.model.document.on('change:data', refresh); });
        }, 300));
    });

    // Buttons that only hold an icon (edit/delete/view) share one round style (see .btn-icon in admin.css)
    document.querySelectorAll('#page-content-wrapper > .container-fluid .btn').forEach((b) => {
        if (b.classList.contains('btn-link') || b.textContent.trim() !== '') return;
        if (b.querySelector('i, svg')) b.classList.add('btn-icon');
    });

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
