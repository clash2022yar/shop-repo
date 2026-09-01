/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './public/js/**/*.js',
    './app/**/*.php',
    './preview/**/*.{js,html}',
  ],
  theme: {
    container: {
      center: true,
      padding: { DEFAULT: '1rem', lg: '1.5rem' },
      screens: { sm: '640px', md: '768px', lg: '1024px', xl: '1200px', '2xl': '1320px' },
    },
    extend: {
      fontFamily: {
        sans: ['Vazirmatn', 'Vazirmatn Variable', 'system-ui', 'Segoe UI', 'sans-serif'],
        display: ['Vazirmatn', 'system-ui', 'sans-serif'],
      },
      colors: {
        /* ---- Digino brand red, sampled from the mockups ---- */
        brand: {
          50: '#FEF2F3',
          100: '#FEE2E4',
          200: '#FECACE',
          300: '#FCA5AC',
          400: '#F87180',
          500: '#EF394E', // primary
          600: '#DC1F36',
          700: '#B9162A',
          800: '#991627',
          900: '#7F1726',
          950: '#450810',
        },
        /* ---- Neutral ink scale used for text / footer ---- */
        ink: {
          50: '#F7F7F8',
          100: '#F0F0F1',
          200: '#E5E5E7',
          300: '#D1D2D4',
          400: '#A1A3A8',
          500: '#7F8084',
          600: '#5C5E63',
          700: '#3F4145',
          800: '#2C2F34',
          900: '#23262B', // footer background
          950: '#15171A',
        },
        success: {
          50: '#ECFAF2',
          100: '#D3F4E1',
          500: '#17A35C',
          600: '#0E8449',
          700: '#0B6939',
        },
        warning: {
          50: '#FFF7E6',
          100: '#FFECC2',
          500: '#F5A623',
          600: '#D98A0B',
        },
        info: {
          50: '#EEF5FF',
          100: '#DBE9FE',
          500: '#2F6FED',
          600: '#1D55C9',
        },
        star: '#FFB800',
      },
      borderRadius: {
        card: '0.75rem',
        field: '0.5rem',
        pill: '999px',
      },
      boxShadow: {
        card: '0 1px 2px rgba(35,38,43,.04), 0 2px 10px rgba(35,38,43,.045)',
        'card-hover': '0 6px 16px rgba(35,38,43,.08), 0 12px 34px rgba(35,38,43,.09)',
        pop: '0 10px 40px rgba(35,38,43,.14)',
        header: '0 1px 0 rgba(35,38,43,.07)',
        ring: '0 0 0 3px rgba(239,57,78,.16)',
      },
      spacing: { 18: '4.5rem', 22: '5.5rem', 68: '17rem', 76: '19rem' },
      fontSize: {
        '2xs': ['0.6875rem', { lineHeight: '1.1rem' }],
      },
      transitionTimingFunction: {
        'out-soft': 'cubic-bezier(.22,.61,.36,1)',
        'in-out-soft': 'cubic-bezier(.45,0,.15,1)',
        bounce2: 'cubic-bezier(.34,1.56,.64,1)',
      },
      keyframes: {
        'fade-in': { from: { opacity: '0' }, to: { opacity: '1' } },
        'fade-out': { from: { opacity: '1' }, to: { opacity: '0' } },
        'fade-up': {
          from: { opacity: '0', transform: 'translateY(18px)' },
          to: { opacity: '1', transform: 'translateY(0)' },
        },
        'fade-down': {
          from: { opacity: '0', transform: 'translateY(-14px)' },
          to: { opacity: '1', transform: 'translateY(0)' },
        },
        'fade-side': {
          from: { opacity: '0', transform: 'translateX(-18px)' },
          to: { opacity: '1', transform: 'translateX(0)' },
        },
        'zoom-in': {
          from: { opacity: '0', transform: 'scale(.94)' },
          to: { opacity: '1', transform: 'scale(1)' },
        },
        'zoom-out': {
          from: { opacity: '1', transform: 'scale(1)' },
          to: { opacity: '0', transform: 'scale(.96)' },
        },
        'slide-in-start': {
          from: { transform: 'translateX(-100%)' },
          to: { transform: 'translateX(0)' },
        },
        'slide-up-sheet': {
          from: { transform: 'translateY(100%)' },
          to: { transform: 'translateY(0)' },
        },
        shimmer: { '100%': { transform: 'translateX(-200%)' } },
        'pulse-ring': {
          '0%': { boxShadow: '0 0 0 0 rgba(239,57,78,.45)' },
          '70%': { boxShadow: '0 0 0 12px rgba(239,57,78,0)' },
          '100%': { boxShadow: '0 0 0 0 rgba(239,57,78,0)' },
        },
        'bounce-in': {
          '0%': { transform: 'scale(.6)', opacity: '0' },
          '60%': { transform: 'scale(1.08)', opacity: '1' },
          '100%': { transform: 'scale(1)' },
        },
        float: {
          '0%,100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-9px)' },
        },
        'spin-slow': { to: { transform: 'rotate(360deg)' } },
        'progress-bar': { from: { width: '0%' }, to: { width: '100%' } },
        'count-flip': {
          '0%': { transform: 'translateY(-60%)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        'heart-pop': {
          '0%': { transform: 'scale(1)' },
          '45%': { transform: 'scale(1.35)' },
          '100%': { transform: 'scale(1)' },
        },
      },
      animation: {
        'fade-in': 'fade-in .35s ease-out both',
        'fade-out': 'fade-out .2s ease-in both',
        'fade-up': 'fade-up .5s cubic-bezier(.22,.61,.36,1) both',
        'fade-down': 'fade-down .35s cubic-bezier(.22,.61,.36,1) both',
        'fade-side': 'fade-side .45s cubic-bezier(.22,.61,.36,1) both',
        'zoom-in': 'zoom-in .28s cubic-bezier(.34,1.56,.64,1) both',
        'zoom-out': 'zoom-out .18s ease-in both',
        'slide-in-start': 'slide-in-start .3s cubic-bezier(.22,.61,.36,1) both',
        'slide-up-sheet': 'slide-up-sheet .32s cubic-bezier(.22,.61,.36,1) both',
        shimmer: 'shimmer 1.6s infinite',
        'pulse-ring': 'pulse-ring 1.8s cubic-bezier(.22,.61,.36,1) infinite',
        'bounce-in': 'bounce-in .45s cubic-bezier(.34,1.56,.64,1) both',
        float: 'float 4.5s ease-in-out infinite',
        'spin-slow': 'spin-slow 1.1s linear infinite',
        'heart-pop': 'heart-pop .4s cubic-bezier(.34,1.56,.64,1)',
        'count-flip': 'count-flip .3s ease-out both',
      },
    },
  },
  plugins: [],
};
