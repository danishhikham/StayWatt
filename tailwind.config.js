module.exports = {
  content: ['./views/**/*.php', './src/js/*.js'],
  safelist: ['text-accent', 'text-bad'],
  theme: {
    extend: {
      colors: {
        bg: 'var(--bg)', surface: 'var(--surface)', surface2: 'var(--surface2)', line: 'var(--line)',
        ink: 'var(--ink)', ink2: 'var(--ink2)', ink3: 'var(--ink3)',
        accent: 'var(--accent)', 'accent-dim': 'var(--accent-dim)', 'on-accent': 'var(--on-accent)',
        bad: 'var(--bad)', err: 'var(--err)', scrim: 'var(--scrim)',
        kwh: '#D8B97A', biaya: '#8FA8C8', co2: '#9DBF9E'
      },
      borderRadius: { card: '10px', ctl: '8px', chip: '6px' },
      fontFamily: { sans: ['-apple-system', '"Segoe UI"', 'Roboto', 'sans-serif'] }
    }
  },
  plugins: []
};
