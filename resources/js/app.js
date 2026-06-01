import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Detección automática de zona horaria del navegador y almacenamiento en cookie
(function() {
    const cookieName = 'user_timezone';
    const hasTimezoneCookie = document.cookie.split('; ').find(row => row.startsWith(cookieName + '='));
    if (!hasTimezoneCookie) {
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'America/Monterrey';
        // Guardar la cookie válida por 1 año con SameSite=Lax
        document.cookie = `${cookieName}=${encodeURIComponent(timezone)}; path=/; max-age=31536000; SameSite=Lax`;
    }
})();
