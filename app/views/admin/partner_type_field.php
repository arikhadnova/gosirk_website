<?php
// "Tipe Partner (Badge)" of the portfolio form: a dropdown whose types are managed in place
// (⋮ next to a type = edit / delete, "Tambah tipe" at the bottom). Expects $partner_types and $selectedType.
$partner_types = $partner_types ?? [];
$selectedType = $selectedType ?? ($partner_types[0]->code ?? '');
?>
<label class="form-label fw-bold small text-dark">Tipe Partner (Badge)</label>
<div class="dropdown partner-type-picker">
    <input type="hidden" name="partner_type" value="<?= htmlspecialchars($selectedType) ?>">
    <button type="button" class="form-select text-start pt-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <span class="pt-current"></span>
    </button>
    <div class="dropdown-menu w-100 p-1 shadow-sm pt-menu">
        <div class="pt-list"></div>
        <div class="dropdown-divider my-1"></div>
        <button type="button" class="dropdown-item rounded-2 text-primary fw-semibold pt-add"><i class="fas fa-plus me-2"></i>Tambah tipe</button>
    </div>
</div>
<small class="text-muted extra-small d-block mt-1">Klik <i class="fas fa-ellipsis-v mx-1"></i> di samping tipe untuk mengubah atau menghapusnya.</small>

<style>
.partner-type-picker .pt-menu { max-height: 320px; overflow-y: auto; }
.partner-type-picker .pt-row { position: relative; }
.partner-type-picker .pt-choose { white-space: normal; }
.partner-type-picker .pt-more { width: 32px; flex-shrink: 0; color: #64748b; }
.partner-type-picker .pt-actions { position: absolute; right: 36px; top: 50%; transform: translateY(-50%); z-index: 2; background: #fff; border: 1px solid #e2e8f0; border-radius: .5rem; box-shadow: 0 6px 18px rgba(15, 23, 42, .12); padding: .25rem; display: flex; gap: .25rem; }
.partner-type-picker .pt-actions button { font-size: .8rem; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const picker = document.querySelector('.partner-type-picker');
    if (!picker) return;
    const input = picker.querySelector('input[name="partner_type"]');
    const toggle = picker.querySelector('.pt-toggle');
    const list = picker.querySelector('.pt-list');
    let types = <?= json_encode(array_values($partner_types), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    // the stored code may differ in case from the type's code (older rows): use the type's own spelling
    const match = types.find((t) => t.code.toLowerCase() === input.value.toLowerCase());
    if (match) input.value = match.code;
    const esc = (t) => String(t ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    const render = () => {
        const current = types.find((t) => t.code === input.value);
        picker.querySelector('.pt-current').textContent = current ? current.label_id : (input.value || 'Pilih tipe');
        list.innerHTML = types.map((t) => `
            <div class="pt-row d-flex align-items-center">
                <button type="button" class="dropdown-item rounded-2 pt-choose${t.code === input.value ? ' active' : ''}" data-code="${esc(t.code)}">
                    ${esc(t.label_id)} <small class="${t.code === input.value ? '' : 'text-muted'}">· ${esc(t.label_en)}</small>
                </button>
                <button type="button" class="btn btn-sm btn-link p-0 pt-more" data-code="${esc(t.code)}" title="Edit atau hapus" aria-label="Edit atau hapus ${esc(t.label_id)}"><i class="fas fa-ellipsis-v"></i></button>
            </div>`).join('') || '<div class="px-3 py-2 small text-muted">Belum ada tipe.</div>';
    };

    const post = async (url, data) => {
        const fd = new FormData();
        Object.entries(data).forEach(([k, v]) => fd.append(k, v));
        const res = await fetch(url, { method: 'POST', body: fd }).then((r) => r.json());
        if (res.status !== 'success') throw new Error(res.message || 'Gagal menyimpan.');
        return res;
    };

    // Name in Indonesian and English (English empty = translated automatically)
    const editType = async (type) => {
        const r = await Swal.fire({
            title: type ? 'Ubah tipe partner' : 'Tambah tipe partner',
            html: `<input id="ptLabelId" class="swal2-input" placeholder="Nama (ID), mis. Pemerintah" maxlength="100" value="${esc(type ? type.label_id : '')}">`
                + `<input id="ptLabelEn" class="swal2-input" placeholder="Nama (EN), kosongkan untuk terjemahan otomatis" maxlength="100" value="${esc(type ? type.label_en : '')}">`,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0D4A7C',
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                const labelId = document.getElementById('ptLabelId').value.trim();
                if (!labelId) { Swal.showValidationMessage('Nama (ID) wajib diisi.'); return false; }
                try {
                    return await post('<?= BASE_URL ?>admin/partner_types_save', { code: type ? type.code : '', label_id: labelId, label_en: document.getElementById('ptLabelEn').value.trim() });
                } catch (err) { Swal.showValidationMessage(err.message); return false; }
            }
        });
        if (!r.isConfirmed) return;
        types = r.value.types;
        if (!type) input.value = r.value.code; // a new type is chosen right away
        render();
    };

    const deleteType = async (type) => {
        const r = await Swal.fire({ icon: 'warning', title: 'Hapus tipe "' + type.label_id + '"?', text: 'Tipe yang masih dipakai portofolio tidak bisa dihapus.',
            showCancelButton: true, confirmButtonText: 'Hapus', cancelButtonText: 'Batal', confirmButtonColor: '#dc3545' });
        if (!r.isConfirmed) return;
        try {
            types = (await post('<?= BASE_URL ?>admin/partner_types_delete', { code: type.code })).types;
            if (input.value === type.code) input.value = types[0] ? types[0].code : '';
            render();
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Tidak bisa dihapus', text: err.message });
        }
    };

    const closeActions = () => list.querySelectorAll('.pt-actions').forEach((m) => m.remove());
    list.addEventListener('click', (e) => {
        const choose = e.target.closest('.pt-choose');
        if (choose) {
            input.value = choose.dataset.code;
            render();
            bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
            return;
        }
        const more = e.target.closest('.pt-more');
        if (more) {
            const open = more.parentElement.querySelector('.pt-actions');
            closeActions();
            if (open) return;
            more.insertAdjacentHTML('beforebegin', '<div class="pt-actions"><button type="button" class="btn btn-sm btn-light pt-edit"><i class="fas fa-pen me-1"></i>Edit</button>'
                + '<button type="button" class="btn btn-sm btn-light text-danger pt-delete"><i class="fas fa-trash me-1"></i>Hapus</button></div>');
            return;
        }
        const type = types.find((t) => t.code === e.target.closest('.pt-row')?.querySelector('.pt-more').dataset.code);
        if (e.target.closest('.pt-edit')) { closeActions(); bootstrap.Dropdown.getOrCreateInstance(toggle).hide(); editType(type); }
        if (e.target.closest('.pt-delete')) { closeActions(); bootstrap.Dropdown.getOrCreateInstance(toggle).hide(); deleteType(type); }
    });
    picker.querySelector('.pt-add').addEventListener('click', () => { bootstrap.Dropdown.getOrCreateInstance(toggle).hide(); editType(null); });
    toggle.addEventListener('hide.bs.dropdown', closeActions);
    render();
});
</script>
