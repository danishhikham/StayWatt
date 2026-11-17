// Menyalin Chart.js (minified) ke public/vendor agar disajikan lokal, tanpa CDN.
const fs = require('fs'), path = require('path');
const src = path.join(__dirname, '..', 'node_modules', 'chart.js', 'dist', 'chart.umd.min.js');
const alt = path.join(__dirname, '..', 'node_modules', 'chart.js', 'dist', 'chart.umd.js');
const dst = path.join(__dirname, '..', 'public', 'vendor', 'chart.umd.js');
fs.mkdirSync(path.dirname(dst), { recursive: true });
fs.copyFileSync(fs.existsSync(src) ? src : alt, dst);
