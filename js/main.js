// Main JavaScript functions
document.addEventListener('DOMContentLoaded', function() {
    console.log('Riftbound loaded');
    
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});

// Logout function (used in header)
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        const formData = new FormData();
        formData.append('action', 'logout');
        
        fetch('auth.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'index.php';
            }
        })
        .catch(error => {
            console.error('Logout error:', error);
        });
    }
}

// Utility function to show messages
function showMessage(element, message, type) {
    if (!element) return;
    
    element.textContent = message;
    element.className = 'message ' + type;
    element.classList.remove('hidden');
    
    setTimeout(() => {
        element.classList.add('hidden');
    }, 5000);
}
