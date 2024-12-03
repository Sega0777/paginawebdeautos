document.querySelectorAll('.auto-card img').forEach((img) => {
    img.style.position = 'relative'; 
    let isDragging = false;
    let offsetX, offsetY;

    img.addEventListener('mousedown', (e) => {
        isDragging = true;
        offsetX = e.offsetX;
        offsetY = e.offsetY;
        img.style.cursor = 'grabbing';
    });

    document.addEventListener('mousemove', (e) => {
        if (isDragging) {
            img.style.left = `${e.pageX - offsetX}px`;
            img.style.top = `${e.pageY - offsetY}px`;
        }
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
        img.style.cursor = 'grab';
    });
});
