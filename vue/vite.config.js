import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { fileURLToPath } from "url";
import dotenv from 'dotenv'

dotenv.config()

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  define: {
    'process.env': process.env
  },
  resolve: {
    alias: {
        "@": fileURLToPath(new URL("./public", import.meta.url)),
    },
},
})
