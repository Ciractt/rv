// Card detail modal handler
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('cardDetailModal');
    const closeBtn = modal.querySelector('.close');

    // Close modal on X click
    closeBtn.addEventListener('click', function() {
        modal.classList.remove('active');
    });

    // Close modal on outside click
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            modal.classList.remove('active');
        }
    });
});

function showCardDetails(card) {
    const modal = document.getElementById('cardDetailModal');

    // Store card ID globally for add to collection - CRITICAL!
    if (typeof window !== 'undefined') {
        window.currentModalCardId = card.id;
    }

    console.log('Card ID stored:', card.id); // Debug log

    // Set card image
    const cardImage = document.getElementById('modalCardImage');
    if (card.card_art_url) {
        cardImage.src = card.card_art_url;
        cardImage.alt = card.name;
        cardImage.style.display = 'block';
    } else {
        cardImage.style.display = 'none';
    }

    // Set card name
    document.getElementById('modalCardName').textContent = card.name;

    // Create badges (Type & Rarity)
    const badgesContainer = document.getElementById('modalBadges');
    badgesContainer.innerHTML = '';

    // Type badge with icon
    const typeBadge = document.createElement('span');
    typeBadge.className = 'card-badge';
    typeBadge.innerHTML = `
        <img src="https://riftmana.com/wp-content/uploads/types/${card.card_type.toLowerCase()}.webp"
             alt="${card.card_type}"
             style="width: 24px !important; height: 24px !important;"
             onerror="this.style.display='none'">
        <span class="card-badge-text">${card.card_type}</span>
    `;
    badgesContainer.appendChild(typeBadge);

    // Rarity badge with icon
    const rarityBadge = document.createElement('span');
    rarityBadge.className = 'card-badge';
    rarityBadge.innerHTML = `
        <img src="https://riftmana.com/wp-content/uploads/rarities/${card.rarity.toLowerCase()}.webp"
             alt="${card.rarity}"
             style="width: 24px !important; height: 24px !important;"
             onerror="this.style.display='none'">
        <span class="card-badge-text">${card.rarity}</span>
    `;
    badgesContainer.appendChild(rarityBadge);

    // Region badge with icon (if available)
    if (card.region) {
        const regionBadge = document.createElement('span');
        regionBadge.className = 'card-badge';
        regionBadge.innerHTML = `
            <img src="https://riftmana.com/wp-content/uploads/colors/${card.region.replace(/\s+/g, '')}.webp"
                 alt="${card.region}"
                 style="width: 24px !important; height: 24px !important;"
                 onerror="this.style.display='none'">
            <span class="card-badge-text">${card.region}</span>
        `;
        badgesContainer.appendChild(regionBadge);
    }

    // Create pills (Champion & Region text)
    const pillsContainer = document.getElementById('modalPills');
    pillsContainer.innerHTML = '';

    if (card.champion) {
        const championPill = document.createElement('span');
        championPill.className = 'card-pill';
        championPill.textContent = card.champion;
        pillsContainer.appendChild(championPill);
    }

    if (card.region) {
        const regionPill = document.createElement('span');
        regionPill.className = 'card-pill';
        regionPill.textContent = card.region;
        pillsContainer.appendChild(regionPill);
    }

    // Set stats
    document.getElementById('modalEnergy').textContent = card.energy !== null ? card.energy : '-';
    document.getElementById('modalPower').textContent = card.power !== null ? card.power : '-';
    document.getElementById('modalMight').textContent = card.might !== null ? card.might : '-';

    // Set description with formatting
    const descriptionElement = document.getElementById('modalDescription');
    if (card.description) {
        // Use the formatter if available
        if (typeof formatCardDescription === 'function') {
            descriptionElement.innerHTML = formatCardDescription(card.description, card.keywords);
        } else {
            descriptionElement.textContent = card.description;
        }
    } else {
        descriptionElement.textContent = 'No description available.';
    }

    // Set flavor text (if exists in card data)
    const flavorSection = document.getElementById('modalFlavorSection');
    const flavorText = document.getElementById('modalFlavorText');
    if (card.flavor_text) {
        flavorText.textContent = card.flavor_text;
        flavorSection.style.display = 'block';
    } else {
        flavorSection.style.display = 'none';
    }

    // Set card code
    document.getElementById('modalCardCode').textContent = card.card_code;

    // Set quantity if available (for collection page)
    const quantityRow = document.getElementById('modalQuantityRow');
    const quantitySpan = document.getElementById('modalQuantity');
    if (card.quantity !== undefined && quantityRow && quantitySpan) {
        quantitySpan.textContent = 'x' + card.quantity;
        quantityRow.style.display = 'flex';
    } else if (quantityRow) {
        quantityRow.style.display = 'none';
    }

    // Show modal
    modal.classList.add('active');
}
