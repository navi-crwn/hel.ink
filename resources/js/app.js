import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
Alpine.plugin(collapse);
window.Alpine = Alpine;
// Use requestAnimationFrame to ensure inline scripts in blade templates have executed
// This handles the race condition with module script execution
requestAnimationFrame(() => {
    requestAnimationFrame(() => {
        Alpine.start();
    });
});
const ensureTurnstileScript = () => {
    if (window.turnstileReady) {
        return window.turnstileReady;
    }
    window.turnstileReady = new Promise((resolve) => {
        if (window.turnstile) {
            resolve(window.turnstile);
            return;
        }
        let script = document.getElementById('turnstile-script');
        if (!script) {
            script = document.createElement('script');
            script.id = 'turnstile-script';
            script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
            script.async = true;
            script.defer = true;
            script.crossOrigin = 'anonymous';
            script.addEventListener('error', (e) => {
                console.error('Turnstile script failed to load', e);
                resolve(null);
            }, { once: true });
            document.head.appendChild(script);
        }
        script.addEventListener('load', () => {
            setTimeout(() => resolve(window.turnstile || null), 50);
        }, { once: true });
    });
    return window.turnstileReady;
};
window.turnstileForm = (siteKey, initiallyVisible = false) => {
    return {
        siteKey,
        showCaptcha: initiallyVisible,
        token: '',
        widgetId: null,
        init() {
            if (this.showCaptcha) {
                this.renderCaptcha();
            }
        },
        handleSubmit(event) {
            if (!this.siteKey) {
                return;
            }
            if (!this.token) {
                event.preventDefault();
                this.showCaptcha = true;
                ensureTurnstileScript().then(() => {
                    this.renderCaptcha();
                });
            }
        },
        renderCaptcha() {
            if (!this.showCaptcha || this.widgetId || !this.siteKey) {
                return;
            }
            this.$nextTick(() => {
                if (!this.$refs.captcha) {
                    console.error('Captcha element not found.');
                    return;
                }
                ensureTurnstileScript().then(() => {
                    this.widgetId = window.turnstile.render(this.$refs.captcha, {
                        sitekey: this.siteKey,
                        callback: (token) => {
                            this.token = token;
                        },
                        'expired-callback': () => {
                            console.warn('Turnstile token expired.');
                            this.token = '';
                        },
                        'error-callback': () => {
                            console.error('Turnstile error occurred.');
                            this.token = '';
                        },
                    });
                });
            });
        },
    };
};
