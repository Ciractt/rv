// Riftmana-style Deck Builder JS
const deck = { main: {}, side: {} };
let showCollection = false;
let noRestriction = false;

document.addEventListener('DOMContentLoaded', () => {
    if (currentDeckCards) {
        currentDeckCards.forEach(c => {
            for (let i = 0; i < c.quantity; i++) riftAddCard(c.id, c.name, c.energy);
        });
    }

    // Wire filters
    document.querySelectorAll('.filter-btn, .cost-btn, .color-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            filterCards();
        });
    });

    document.getElementById('globalSearch').addEventListener('input', () => debounce(filterCards, 200));
    document.getElementById('showCollection').addEventListener('change', e => {
        showCollection = e.target.checked;
        updateCollectionBadges();
    });
    document.getElementById('noRestrictionBtn').addEventListener('click', () => {
        noRestriction = !noRestriction;
        document.getElementById('noRestrictionBtn').classList.toggle('active', noRestriction);
    });

    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
        });
    });

    updateDeck();
});

window.riftAddCard = (id, name, cost) => {
    const section = getCardSection(cardDatabase[id]);
    const target = deck.main;
    target[id] = (target[id] || 0) + 1;
    if (!noRestriction && target[id] > 3) { target[id] = 3; }
    updateDeck();
};

window.riftIncCard = id => { riftAddCard(id); };
window.riftDecCard = id => {
    const target = deck.main;
    if (target[id]) { target[id]--; if (target[id] <= 0) delete target[id]; }
    updateDeck();
};

function getCardSection(card) {
    if (card.rarity === 'Champion') return 'legend';
    if (card.type === 'Battlefield') return 'battlefield';
    if (card.type === 'Rune') return 'rune';
    return 'main';
}

function updateDeck() {
    let total = 0;
    Object.values(deck.main).forEach(q => total += q);
    document.getElementById('totalCards').textContent = total;

    ['legend', 'battlefield', 'rune', 'main'].forEach(sec => {
        const container = document.getElementById(sec + 'Section').querySelector('.section-cards');
        container.innerHTML = '';
        const cards = Object.entries(deck.main).filter(([id]) => getCardSection(cardDatabase[id]) === sec);
        cards.forEach(([id, qty]) => {
            const card = cardDatabase[id];
            const el = document.createElement('div');
            el.className = 'deck-card-item';
            el.innerHTML = `
                <img src="${card.card_art_url}" alt="">
                <div>
                    <div class="card-name">${card.name}</div>
                    <div class="card-type">${card.type}</div>
                </div>
                <div class="quantity-controls">
                    <button onclick="riftDecCard(${id})">-</button>
                    <span>${qty}</span>
                    <button onclick="riftIncCard(${id})">+</button>
                </div>
            `;
            container.appendChild(el);
        });
        document.querySelector(`#${sec}Section h3 span`).textContent = cards.length;
    });
}

function filterCards() {
    const search = document.getElementById('globalSearch').value.toLowerCase();
    const types = Array.from(document.querySelectorAll('.filter-btn.active')).map(b => b.dataset.type);
    const costs = Array.from(document.querySelectorAll('.cost-btn.active')).map(b => b.dataset.cost);
    const colors = Array.from(document.querySelectorAll('.color-btn.active')).map(b => b.dataset.color);

    document.querySelectorAll('.riftmana-card').forEach(card => {
        const data = card.dataset;
        const cardObj = cardDatabase[data.id];

        let show = true;
        if (search && !data.name.includes(search)) show = false;
        if (types.length && !types.includes(data.type.toLowerCase())) show = false;
        if (costs.length && !costs.includes(data.cost >= 8 ? '8' : data.cost)) show = false;
        if (colors.length && !colors.includes(data.color.toLowerCase())) show = false;

        card.style.display = show ? '' : 'none';
    });
}

function updateCollectionBadges() {
    document.querySelectorAll('.collection-badge').forEach(b => b.style.display = 'none');
    if (!showCollection) return;
    document.querySelectorAll('.riftmana-card').forEach(card => {
        const id = card.dataset.id;
        const owned = collectionData[id] || 0;
        const badge = card.querySelector('.collection-badge');
        if (owned > 0) {
            badge.textContent = owned;
            badge.style.display = 'block';
            badge.style.background = owned >= 3 ? '#4d8e2f' : '#e0292e';
        }
    });
}

function debounce(fn, wait) {
    let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), wait); };
}
