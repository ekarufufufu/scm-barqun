import { fileURLToPath, URL } from "node:url";
import { defineConfig } from "vite";
import { createVuePlugin } from "vite-plugin-vue2";
import { VuetifyResolver } from "unplugin-vue-components/resolvers";
import Components from "unplugin-vue-components/vite";

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    createVuePlugin(),
    Components({
      resolvers: [VuetifyResolver()],
    }),
  ],
  server: {
    host: "127.0.0.1",
    port: 8080,
  },
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          vue: ["vue", "vue-router", "vuex"],
        },
      },
    },
  },
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
    extensions: [".js", ".ts", ".tsx", ".jsx", ".vue"],
  },
});
