(() => {
  const f = document.getElementById('form-peralatan');
  if (!f) return;
  const el = (id) => document.getElementById(id);
  let t;
  const hitung = () => {
    clearTimeout(t);
    t = setTimeout(async () => {
      try {
        const p = new URLSearchParams({ daya: el('daya_w').value, jumlah: el('jumlah').value, jam: el('jam').value });
        const r = await fetch((document.documentElement.dataset.base || '') + '/api/estimasi?' + p);
        if (!r.ok) return;
        const d = await r.json();
        for (const k in d) { const n = el('est-' + k); if (n) n.textContent = d[k]; }
      } catch (_) { /* abaikan */ }
    }, 150);
  };
  f.addEventListener('input', hitung);
  const ref = el('referensi');
  if (ref) ref.addEventListener('change', () => {
    const o = ref.selectedOptions[0];
    if (!o || !o.value) return;
    el('nama').value = o.dataset.nama; el('daya_w').value = o.dataset.daya; el('kategori_id').value = o.dataset.kategori;
    hitung();
  });
})();
