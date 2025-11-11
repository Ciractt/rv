// Authentication handler
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const riotLoginForm = document.getElementById('riotLoginForm');
    const messageDiv = document.getElementById('message');

    function showMessage(message, isSuccess) {
        messageDiv.textContent = message;
        messageDiv.className = 'message ' + (isSuccess ? 'success' : 'error');
        messageDiv.classList.remove('hidden');
    }

    function handleFormSubmit(form, callback) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            try {
                const response = await fetch('auth.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                showMessage(data.message, data.success);
                
                if (data.success && data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                }
                
                if (callback) callback(data);
            } catch (error) {
                showMessage('An error occurred. Please try again.', false);
            }
        });
    }

    if (loginForm) {
        handleFormSubmit(loginForm);
    }

    if (registerForm) {
        handleFormSubmit(registerForm, function(data) {
            if (data.success) {
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 2000);
            }
        });
    }

    if (riotLoginForm) {
        handleFormSubmit(riotLoginForm);
    }
});

// Logout function
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
        });
    }
}
