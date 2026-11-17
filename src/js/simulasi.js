(() => {
  const baris = [...document.querySelectorAll('[data-baris]')];
  if (!baris.length) return;
  const inp = (b) => b.querySelector('input[data-sim]');
  let t;
  const warna = (n, arah) => { n.classList.remove('text-accent', 'text-bad', 'text-ink2'); n.classList.add(arah < 0 ? 'text-accent' : arah > 0 ? 'text-bad' : 'text-ink2'); };
  const ubah = () => {
    clearTimeout(t);
    t = setTimeout(async () => {
      const d = baris.map((b) => `${b.dataset.baris}:${parseFloat(inp(b).value) || 0}`).join(',');
      try {
        const r = await fetch((document.documentElement.dataset.base || '') + '/api/simulasi?d=' + encodeURIComponent(d));
        if (!r.ok) return;
        const h = await r.json();
        document.querySelectorAll('[data-r]').forEach((n) => { if (h[n.dataset.r] !== undefined) n.textContent = h[n.dataset.r]; });
        document.querySelectorAll('[data-arah]').forEach((n) => warna(n, h.arah > 0 ? 1 : -1));
        baris.forEach((b) => { const x = h.baris[b.dataset.baris]; if (!x) return; b.querySelector('[data-ke]').textContent = x.ke; warna(b.querySelector('[data-ke-wrap]'), x.arah); });
      } catch (_) { /* abaikan */ }
    }, 120);
  };
  document.addEventListener('input', (e) => { if (e.target.matches('input[data-sim]')) ubah(); });
  document.querySelectorAll('[data-reset]').forEach((b) => b.addEventListener('click', () => {
    baris.forEach((r) => { inp(r).value = r.dataset.asal; });
    ubah();
  }));
})();
