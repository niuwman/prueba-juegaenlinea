import type { Config } from 'tailwindcss'

export default {
  content: [
    './components/**/*.{js,vue,ts}',
    './layouts/**/*.vue',
    './pages/**/*.vue',
    './plugins/**/*.{js,ts}',
    './app.vue',
  ],
  theme: {
    extend: {
      colors: {
        death: {
          light: '#e0e0e0',
          dark: '#1a1a1a',
          darker: '#0a0a0a',
          red: '#ff6b6b',
          redDark: '#3d1a1a',
          green: '#8bc34a',
          greenDark: '#2d5a2d'
        }
      },
      animation: {
        'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
      },
      keyframes: {
        pulseGlow: {
          '0%, 100%': { boxShadow: '0 0 20px rgba(255, 107, 107, 0.3)' },
          '50%': { boxShadow: '0 0 40px rgba(255, 107, 107, 0.6)' },
        }
      }
    },
  },
  plugins: [],
} satisfies Config