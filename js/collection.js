// Collection management
document.addEventListener('DOMContentLoaded', function() {
    const addCardBtn = document.getElementById('addCardBtn');
    const addCardModal = document.getElementById('addCardModal');
    const cardDetailModal = document.getElementById('cardDetailModal');
    const cardSearch = document.getElementById('cardSearch');
    const cardList = document.getElementById('cardList');

    // Open add card modal
    if (addCardBtn) {
        addCardBtn.addEventListener('click', function() {
            addCardModal.classList.add('active');
        });
    }

    // Close modals
    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            this.closest('.modal').classList.remove('active');
        });
    });

    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.classList.remove('active');
        }
    });

    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.classList.remove('active');
            });
        }
    });

    // Search cards in modal
    if (cardSearch) {
        cardSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = cardList.querySelectorAll('.modal-card-item');
            
            cards.forEach(card => {
                const name = card.dataset.name;
                if (name.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});

// Override showCardDetails for collection page to show quantity
const originalShowCardDetails = window.showCardDetails;
window.showCardDetails = function(card) {
    // Call original function
    originalShowCardDetails(card);
    
    // Store card ID for "Add to Collection" button
    window.currentModalCardId = card.id;
    
    // Show quantity if this is from collection page
    const quantityRow = document.getElementById('modalQuantityRow');
    const quantitySpan = document.getElementById('modalQuantity');
    
    if (card.quantity !== undefined && quantityRow && quantitySpan) {
        quantitySpan.textContent = 'x' + card.quantity;
        quantityRow.style.display = 'block';
    } else if (quantityRow) {
        quantityRow.style.display = 'none';
    }
    
    // Show collection actions if available
    const collectionActions = document.getElementById('modalCollectionActions');
    if (collectionActions) {
        // Only show on collection page when viewing owned cards
        if (card.quantity !== undefined) {
            collectionActions.style.display = 'block';
        } else {
            collectionActions.style.display = 'none';
        }
    }
};

// Add card to collection
async function addToCollection(cardId) {
    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('card_id', cardId);

    try {
        const response = await fetch('api/collection.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            // Show success message briefly
            showNotification(data.message, 'success');
            
            // Close add card modal if open
            const addCardModal = document.getElementById('addCardModal');
            if (addCardModal && addCardModal.classList.contains('active')) {
                addCardModal.classList.remove('active');
            }
            
            // Reload page to show updated collection
            setTimeout(() => {
                location.reload();
            }, 800);
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
    }
}

// Update card quantity
async function updateQuantity(cardId, change) {
    const formData = new FormData();
    formData.append('action', 'update_quantity');
    formData.append('card_id', cardId);
    formData.append('change', change);

    try {
        const response = await fetch('api/collection.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
    }
}

// Remove card from collection
async function removeCard(cardId) {
    if (!confirm('Remove this card from your collection?')) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'remove');
    formData.append('card_id', cardId);

    try {
        const response = await fetch('api/collection.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
    }
}

// Show notification
function showNotification(message, type) {
    // Remove any existing notifications
    const existing = document.querySelector('.notification');
    if (existing) {
        existing.remove();
    }
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = 'notification notification-' + type;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    // Trigger animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
