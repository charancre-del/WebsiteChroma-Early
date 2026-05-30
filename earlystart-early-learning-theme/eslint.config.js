module.exports = [
    {
        languageOptions: {
            ecmaVersion: 2020,
            sourceType: 'script',
            globals: {
                window: 'readonly',
                document: 'readonly',
                jQuery: 'readonly',
                $: 'readonly',
                wp: 'readonly',
                console: 'readonly',
                setTimeout: 'readonly',
                setInterval: 'readonly',
                clearTimeout: 'readonly',
                clearInterval: 'readonly',
                fetch: 'readonly',
                FormData: 'readonly',
                XMLHttpRequest: 'readonly',
                alert: 'readonly',
                confirm: 'readonly',
                location: 'readonly',
                navigator: 'readonly',
                URL: 'readonly',
                requestAnimationFrame: 'readonly',
                IntersectionObserver: 'readonly',
                MutationObserver: 'readonly',
                lucide: 'readonly',
                Chart: 'readonly',
                L: 'readonly',
                chromaLLM: 'readonly',
                ChromaInspector: 'readonly'
            }
        },
        rules: {
            'no-unused-vars': 'warn',
            'no-undef': 'error',
            'semi': ['warn', 'always'],
            'no-console': 'off'
        }
    }
];


