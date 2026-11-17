(() => {
  const c = document.getElementById('grafik');
  if (!c || !window.Chart) return;
  const d = JSON.parse(document.getElementById('grafik-data').textContent);
  const s = getComputedStyle(document.documentElement), v = (n) => s.getPropertyValue(n).trim();
  const fam = '-apple-system,"Segoe UI",Roboto,sans-serif', akhir = d.nilai.length - 1;
  Chart.defaults.font.family = fam;
  new Chart(c, {
    type: 'bar',
    data: { labels: d.label, datasets: [{ data: d.nilai, backgroundColor: d.nilai.map((_, i) => (i === akhir ? v('--accent') : v('--surface2'))), borderRadius: 4, barPercentage: 0.4, categoryPercentage: 1 }] },
    options: {
      animation: false, maintainAspectRatio: false,
      plugins: { legend: { display: false }, tooltip: { backgroundColor: v('--surface2'), titleColor: v('--ink2'), bodyColor: v('--ink'), displayColors: false, callbacks: { label: (x) => String(x.parsed.y).replace('.', ',') + ' kWh' } } },
      scales: { y: { display: false, beginAtZero: true, grace: '18%' }, x: { grid: { display: false }, border: { color: v('--line') }, ticks: { color: v('--ink3'), font: { size: 11 } } } }
    },
    plugins: [{ id: 'label-akhir', afterDatasetsDraw(ch) {
      const m = ch.getDatasetMeta(0).data[akhir]; if (!m) return;
      const x = ch.ctx; x.save(); x.fillStyle = v('--ink2'); x.font = '12px ' + fam; x.textAlign = 'center';
      x.fillText(Math.round(d.nilai[akhir]), m.x, m.y - 8); x.restore();
    } }]
  });
})();
