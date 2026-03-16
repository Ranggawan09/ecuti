import path from "path";
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],

    resolve: {
        alias: {
            "@tailwindConfig": path.resolve(__dirname, "tailwind.config.js"),
        },
    },

    optimizeDeps: {
        include: ["@tailwindConfig"],
    },

    build: {
        // Naikkan batas warning agar tidak terlalu agresif
        chunkSizeWarningLimit: 800,

        rollupOptions: {
            output: {
                manualChunks(id) {
                    // Pisahkan node_modules menjadi vendor chunk
                    if (id.includes("node_modules")) {
                        if (id.includes("chart.js")) {
                            return "chart";
                        }

                        if (id.includes("flatpickr")) {
                            return "datepicker";
                        }

                        if (id.includes("axios") || id.includes("alpinejs")) {
                            return "vendor";
                        }

                        return "vendor";
                    }
                },
            },
        },
    },
});
