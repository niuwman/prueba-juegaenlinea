// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  
  ssr: false, 
  
  css: ['~/assets/css/main.css'],
  
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },
  
  nitro: {
    preset: 'node-server'
  },
  
  runtimeConfig: {
    public: {
      apiBase: import.meta.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api'
    }
  },
  
  modules: [
    '@nuxtjs/tailwindcss'
  ],
  
  typescript: {
    strict: true,
    typeCheck: false
  }
})
