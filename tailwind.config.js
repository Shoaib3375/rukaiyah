/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        gold: {
          50: '#faf8f3',
          100: '#f5f0e8',
          200: '#ebe5d9',
          300: '#e8cc7a',
          400: '#d9b857',
          500: '#c9a84c',
          600: '#b89940',
          700: '#9d8235',
          800: '#7a6428',
          900: '#5c4c1f',
        },
        ink: '#0e0b1a',
        deep: '#12102a',
        void: '#080614',
        cream: '#f5f0e8',
      },
      backgroundColor: {
        dark: '#0e0b1a',
        darker: '#080614',
        'gold-dim': 'rgba(201,168,76,0.18)',
      },
      textColor: {
        gold: '#c9a84c',
        'gold-light': '#e8cc7a',
        cream: '#f5f0e8',
      },
      borderColor: {
        gold: '#c9a84c',
        'gold-light': '#e8cc7a',
      },
      spacing: {
        '4.5': '1.125rem',
        '5.5': '1.375rem',
      },
      borderRadius: {
        'lg': '0.5rem',
        'xl': '0.75rem',
        '2xl': '1rem',
      },
      boxShadow: {
        'xs': '0 1px 2px 0 rgba(0, 0, 0, 0.5)',
        'sm': '0 1px 2px 0 rgba(0, 0, 0, 0.5)',
        'md': '0 4px 6px -1px rgba(0, 0, 0, 0.7)',
        'lg': '0 10px 15px -3px rgba(0, 0, 0, 0.8)',
        'xl': '0 20px 25px -5px rgba(0, 0, 0, 0.9)',
      },
      fontFamily: {
        sans: ['system-ui', '-apple-system', 'BlinkMacSystemFont', '\"Segoe UI\"', 'Roboto', '\"Helvetica Neue\"', 'Arial', 'sans-serif'],
      },
      fontSize: {
        'xs': ['0.75rem', '1rem'],
        'sm': ['0.875rem', '1.25rem'],
        'base': ['1rem', '1.5rem'],
        'lg': ['1.125rem', '1.75rem'],
        'xl': ['1.25rem', '1.75rem'],
        '2xl': ['1.5rem', '2rem'],
        '3xl': ['1.875rem', '2.25rem'],
      },
      animation: {
        'fade-in': 'fadeIn 0.3s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(16px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
      },
    },
  },
  plugins: [],
}
