import { defineConfig } from 'vite';
import { resolve } from 'path';
import fs from 'fs';

// Plugin: creates/removes dist/hot file to signal dev mode to PHP
function hotFilePlugin() {
    const hotPath = resolve(__dirname, 'dist/hot');
    return {
        name: 'hot-file',
        configureServer() {
            fs.mkdirSync(resolve(__dirname, 'dist'), { recursive: true });
            fs.writeFileSync(hotPath, 'http://localhost:5173');
        },
        buildStart() {
            // Remove hot file on production build
            if (fs.existsSync(hotPath)) {
                fs.unlinkSync(hotPath);
            }
        },
    };
}

export default defineConfig({
    plugins: [hotFilePlugin()],

    // Dev server
    server: {
        host: 'localhost',
        port: 5173,
        strictPort: true,
        cors: true,
        origin: 'http://localhost:5173',
    },

    // Build config
    build: {
        outDir: 'dist',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: {
                main: resolve(__dirname, 'assets/js/main.js'),
            },
        },
    },
});
