document.addEventListener('click', (e) => {
  const b = e.target.closest('[data-stepper]');
  if (!b) return;
  const i = b.closest('[data-stepper-wrap]').querySelector('input');
  const v = (parseFloat(i.value) || 0) + Number(b.dataset.stepper);
  i.value = Math.round(Math.min(+i.max, Math.max(+i.min, v)) * 100) / 100;
  i.dispatchEvent(new Event('input', { bubbles: true }));
});
