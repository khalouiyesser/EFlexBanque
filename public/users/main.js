document.querySelector('.profile-picture img').addEventListener('mouseover', function() {
    this.style.transform = 'scale(1.1)';
});

document.querySelector('.profile-picture img').addEventListener('mouseleave', function() {
    this.style.transform = 'scale(1)';
});