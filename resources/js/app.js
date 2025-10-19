// Dynamically load Alpine to enable better code-splitting
import('alpinejs').then((mod) => {
    const Alpine = mod.default;
    window.Alpine = Alpine;
    Alpine.start();
});