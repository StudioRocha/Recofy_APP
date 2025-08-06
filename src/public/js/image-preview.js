document.addEventListener('DOMContentLoaded', () => {
    const imageInput = document.getElementById('images');
    if (!imageInput) return;

    imageInput.addEventListener('change', function(e) {
        const maxImages = 4;
        const maxSize = 2048;
        const previewContainer = document.getElementById('imagePreviewContainer');
        const files = Array.from(e.target.files);

        previewContainer.innerHTML = '';

        if (files.length > maxImages) {
            alert(`画像は最大 ${maxImages} 枚までアップロードできます。`);
            this.value = '';
            return;
        }

        files.forEach((file) => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image();

                img.onload = function() {
                    const resizedDataUrl = resizeImage(img, maxSize);

                    const thumb = document.createElement('div');
                    thumb.className = 'thumb';
                    thumb.style.position = 'relative';
                    thumb.style.display = 'inline-block';
                    thumb.style.margin = '5px';
                    thumb.innerHTML = `
                        <img src="${resizedDataUrl}" width="150" height="150" style="object-fit: cover;">
                        <button type="button" class="remove-btn" style="
                            position: absolute;
                            top: 0;
                            right: 0;
                            background: rgba(0,0,0,0.5);
                            color: white;
                            border: none;
                            border-radius: 50%;
                            width: 24px;
                            height: 24px;
                            cursor: pointer;">✖</button>
                    `;

                    thumb.querySelector('.remove-btn').addEventListener('click', () => {
                        thumb.remove();
                        alert('画像を削除しました。フォームを再度選択しなおしてください。');
                    });

                    previewContainer.appendChild(thumb);
                };

                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    });

    // ✅ リサイズ関数（アスペクト比維持して長辺を maxLength に収める）
    function resizeImage(img, maxLength) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        const isLandscape = img.width >= img.height;
        const ratio = isLandscape ? maxLength / img.width : maxLength / img.height;

        if (ratio >= 1) {
            canvas.width = img.width;
            canvas.height = img.height;
        } else {
            canvas.width = img.width * ratio;
            canvas.height = img.height * ratio;
        }

        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        return canvas.toDataURL('image/jpeg', 0.9);
    }
});
