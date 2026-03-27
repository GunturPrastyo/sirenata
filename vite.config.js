import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

const paths = [
    "resources/css/app.css", "resources/js/app.js",
    "Modules/Dashboard/resources/assets/js/app.js",
];

export default defineConfig({
    
    plugins: [
        laravel({
            input: paths,
            refresh: [
                "resources/**",
                "Modules/**/*.blade.php",
                "Modules/**/*.php",
            ],
        }),
        tailwindcss(),
    ],
});