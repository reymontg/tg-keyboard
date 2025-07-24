import {defineConfig} from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
    // site-level options
    title : 'Tg Keyboard',
    base  : '/tg-keyboard/',
    srcDir: './docs',
    description: 'An easy keyboard builder for Telegram Api & Mtproto syntax.',
    themeConfig: {
        // theme-level options
    }
})