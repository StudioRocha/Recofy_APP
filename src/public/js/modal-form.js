// public/js/modal-form.js

document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('modalOverlay'); // ✅ 新ID
  const openBtn = document.getElementById('openModal');
  const headerOpenBtn = document.querySelector('[data-open-modal="true"]');
  const closeBtn = document.getElementById('closeModal');

  if (openBtn && modal) {
    openBtn.addEventListener('click', () => {
      modal.classList.remove('hidden');
    });
  }

  if (headerOpenBtn && modal) {
    headerOpenBtn.addEventListener('click', () => {
      modal.classList.remove('hidden');
    });
  }

  if (closeBtn && modal) {
    closeBtn.addEventListener('click', () => {
      modal.classList.add('hidden');
    });
  }

  // ✅ オーバーレイ背景クリックでも閉じる（必要なら）
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.add('hidden');
    }
  });
});
