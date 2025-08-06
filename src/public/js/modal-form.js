// public/js/modal-form.js

document.addEventListener('DOMContentLoaded', () => {
    const openBtn = document.getElementById('openModal');
    const closeBtn = document.getElementById('closeModal');
    const overlay = document.getElementById('modalOverlay');

    if (!openBtn || !closeBtn || !overlay) {
        console.warn('⚠ モーダル要素が見つかりません。');
        return;
    }

    openBtn.addEventListener('click', () => {
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    closeBtn.addEventListener('click', () => {
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    });

    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
});
