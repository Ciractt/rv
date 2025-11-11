/*=====================================================================
   deck_builder.js – Riftmana-style Deck Builder
   -------------------------------------------------
   • Advanced client-side filtering (search, colors, set, type, rarity,
     energy/might/power sliders)
   • Collection integration (badges, insufficient-collection warning)
   • Deck sections (Champions / Landmarks / Main Deck)
   • Mana-curve Chart.js graph
   • No-restrictions toggle
   • Export deck as a beautiful image (canvas → JPEG)
   • All original save / load / export-code logic preserved
=====================================================================*/

;(() => {
  // -----------------------------------------------------------------
  // 1. GLOBALS & CONFIG
  // -----------------------------------------------------------------
  const colorMap = {
    Fury:  '#e0292e',
    Calm:  '#4d8e2f',
    Mind:  '#2a72a0',
    Body:  '#e6700d',
    Chaos: '#6e478f',
    Order: '#ccae0b'
  };

  const EXPORT_CONFIG = {
    canvas: { width: 1200, padding: 30, gap: 12 },
    brand:   { iconUrl: 'https://cdn.riftmana.com/logo.png', iconSize: 48, gap: 12, topMargin: 20 },
    colors:  { text: '#ffffff' },
    deckName: { fontSize: 48 },
    format:  'image/jpeg',
    quality: 0.92
  };

  let currentDeck = [];               // {id, name, cost, quantity}
  let collectionEnabled = false;      // Show collection toggle
  let collectionData = {};            // cardId → owned count
  let noRestriction = false;          // Bypass copy limits
  let manaChart = null;               // Chart.js instance

  // -----------------------------------------------------------------
  // 2. DOM READY
  // -----------------------------------------------------------------
  document.addEventListener('DOMContentLoaded', () => {
    // ---- 2.1 Load PHP-passed data -------------------------------------------------
    if (typeof currentDeckCards !== 'undefined') {
      currentDeckCards.forEach(c => {
        for (let i = 0; i < c.quantity; i++) addCardToDeck(c.id, c.name, c.energy);
      });
    }
    if (typeof collectionData !== 'undefined') collectionData = collectionData;
    if (typeof allCards !== 'undefined') allCards.forEach(c => cardDatabase[c.id] = c);

    // ---- 2.2 UI wiring -----------------------------------------------------------
    wireFilters();
    wireDeckActions();
    wireModals();
    wireCollectionToggle();
    wireNoRestrictionToggle();
    initManaChart();

    // initial render
    updateDeckDisplay();
    filterLibrary();               // apply any URL params / defaults
    builderCollectionUpdateBadges();
  });

  // -----------------------------------------------------------------
  // 3. FILTERING (search + advanced)
  // -----------------------------------------------------------------
  function wireFilters() {
    const els = {
      search:   document.getElementById('librarySearch'),
      colorBtns:document.querySelectorAll('.color-btn'),
      set:      document.getElementById('setFilter'),
      type:     document.getElementById('typeFilter'),
      rarity:   document.getElementById('rarityFilter'),
      energyMin:document.getElementById('energyMin'),
      energyMax:document.getElementById('energyMax'),
      mightMin: document.getElementById('mightMin'),
      mightMax: document.getElementById('mightMax'),
      powerMin: document.getElementById('powerMin'),
      powerMax: document.getElementById('powerMax')
    };

    // ---- live search -------------------------------------------------
    els.search.addEventListener('input', () => debounce(filterLibrary, 180));

    // ---- color buttons ------------------------------------------------
    els.colorBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        btn.classList.toggle('active');
        filterLibrary();
      });
    });

    // ---- dropdowns & sliders -----------------------------------------
    ['set','type','rarity','energyMin','energyMax','mightMin','mightMax','powerMin','powerMax']
      .forEach(id => els[id].addEventListener('input', () => debounce(filterLibrary, 120)));

    // ---- range value display -----------------------------------------
    function updateRangeLabel(id, min, max, anyText = 'Any') {
      const span = document.getElementById(id);
      if (min === 0 && max === (id.includes('energy')?12:10)) span.textContent = anyText;
      else span.textContent = `${min} – ${max}`;
    }
    els.energyMin.addEventListener('input', () => updateRangeLabel('energyValue', +els.energyMin.value, +els.energyMax.value));
    els.energyMax.addEventListener('input', () => updateRange CanvasLabel('energyValue', +els.energyMin.value, +els.energyMax.value));
    els.mightMin.addEventListener('input', () => updateRangeLabel('mightValue', +els.mightMin.value, +els.mightMax.value));
    els.mightMax.addEventListener('input', () => updateRangeLabel('mightValue', +els.mightMin.value, +els.mightMax.value));
    els.powerMin.addEventListener('input', () => updateRangeLabel('powerValue', +els.powerMin.value, +els.powerMax.value));
    els.powerMax.addEventListener('input', () => updateRangeLabel('powerValue', +els.powerMin.value, +els.powerMax.value));
  }

  function filterLibrary() {
    const els = {
      search:   document.getElementById('librarySearch').value.trim().toLowerCase(),
      colors:   Array.from(document.querySelectorAll('.color-btn.active')).map(b=>b.dataset.color.toLowerCase()),
      set:      document.getElementById('setFilter').value.toLowerCase(),
      type:     document.getElementById('typeFilter').value.toLowerCase(),
      rarity:   document.getElementById('rarityFilter').value.toLowerCase(),
      eMin: +document.getElementById('energyMin').value,
      eMax: +document.getElementById('energyMax').value,
      mMin: +document.getElementById('mightMin').value,
      mMax: +document.getElementById('mightMax').value,
      pMin: +document.getElementById('powerMin').value,
      pMax: +document.getElementById('powerMax').value
    };

    document.querySelectorAll('.builder-card').forEach(card => {
      const d = card.dataset;

      // ---- text search -------------------------------------------------
      let show = true;
      if (els.search) {
        const txt = `${d.name} ${d.cardCode||''} ${d.description||''}`.toLowerCase();
        if (!txt.includes(els.search)) show = false;
      }

      // ---- color -------------------------------------------------------
      if (els.colors.length && !els.colors.includes(d.color?.toLowerCase())) show = false;

      // ---- dropdowns ---------------------------------------------------
      if (els.set && d.set?.toLowerCase() !== els.set) show = false;
      if (els.type && d.type?.toLowerCase() !== els.type) show = false;
      if (els.rarity && d.rarity?.toLowerCase() !== els.rarity) show = false;

      // ---- numeric ranges (allow NULL) ---------------------------------
      if (show) {
        const e = d.energy === '' ? null : +d.energy;
        const m = d.might === '' ? null : +d.might;
        const p = d.power === '' ? null : +d.power;

        if (e !== null && (e < els.eMin || e > els.eMax)) show = false;
        if (m !== null && (m < els.mMin || m > els.mMax)) show = false;
        if (p !== null && (p < els.pMin || p > els.pMax)) show = false;
      }

      card.style.display = show ? '' : 'none';
    });
  }

  // -----------------------------------------------------------------
  // 4. CARD ADD / REMOVE + COPY LIMITS
  // -----------------------------------------------------------------
  window.addCardToDeck = (cardId, cardName, energy) => {
    const existing = currentDeck.find(c => c.id === cardId);
    const max = noRestriction ? 99 : (cardDatabase[cardId]?.rarity?.toLowerCase() === 'champion' ? 3 : 3);

    if (existing) {
      if (existing.quantity >= max) {
        if (!noRestriction) alert('Maximum copies reached for this card');
        return;
      }
      existing.quantity++;
    } else {
      currentDeck.push({ id: cardId, name: cardName, cost: energy, quantity: 1 });
    }
    updateDeckDisplay();
  };

  window.removeCardFromDeck = (cardId) => {
    const idx = currentDeck.findIndex(c => c.id === cardId);
    if (idx === -1) return;
    if (currentDeck[idx].quantity > 1) currentDeck[idx].quantity--;
    else currentDeck.splice(idx, 1);
    updateDeckDisplay();
  };

  // -----------------------------------------------------------------
  // 5. DECK DISPLAY (sections, badges, warnings)
  // -----------------------------------------------------------------
  function updateDeckDisplay() {
    const total = currentDeck.reduce((s,c)=>s+c.quantity,0);
    document.getElementById('cardCount').textContent = total;

    const deckList = document.getElementById('deckList');
    deckList.innerHTML = '';

    if (!currentDeck.length) {
      deckList.innerHTML = '<p class="empty-deck">Add cards from the library to start building your deck</p>';
      updateManaChart([]);
      return;
    }

    // ---- split into sections -------------------------------------------------
    const sections = { champion: [], landmark: [], main: [] };
    currentDeck.forEach(c => {
      const card = cardDatabase[c.id];
      const type = (card?.rarity?.toLowerCase() === 'champion') ? 'champion' :
                   (card?.card_type?.toLowerCase() === 'landmark') ? 'landmark' : 'main';
      sections[type].push(c);
    });

    const renderSection = (title, cards) => {
      if (!cards.length) return '';
      let html = `<div class="deck-section"><h3>${title}</h3><div class="deck-cards">`;
      cards.forEach(c => {
        const owned = collectionData[c.id] || 0;
        const warn = collectionEnabled && c.quantity > owned ? 'insufficient-collection' : '';
        html += `
          <div class="deck-card-item ${warn}" data-card-id="${c.id}" data-quantity="${c.quantity}">
            <div class="card-mini">
              <span class="card-energy">${c.cost}</span>
              <span class="card-name">${c.name}</span>
              <span>x${c.quantity}</span>
            </div>
            <div class="quantity-controls">
              <button class="btn-icon" onclick="addCardToDeck(${c.id},'${c.name.replace(/'/g,"\\'")}',${c.cost})">plus</button>
              <button class="btn-icon btn-danger" onclick="removeCardFromDeck(${c.id})">minus</button>
            </div>
          </div>`;
      });
      html += `</div></div>`;
      return html;
    };

    deckList.innerHTML = renderSection('Champions', sections.champion) +
                         renderSection('Landmarks', sections.landmark) +
                         renderSection('Main Deck', sections.main);

    // ---- collection warnings -------------------------------------------------
    if (collectionEnabled) builderCollectionUpdateBadges();

    // ---- mana curve -----------------------------------------------------------
    updateManaChart(currentDeck);
  }

  // -----------------------------------------------------------------
  // 6. COLLECTION INTEGRATION
  // -----------------------------------------------------------------
  function wireCollectionToggle() {
    const cb = document.getElementById('builder-collection-checkbox');
    if (!cb) return;
    cb.addEventListener('change', () => {
      collectionEnabled = cb.checked;
      builderCollectionUpdateBadges();
      updateDeckDisplay(); // refresh warnings
    });
  }

  function builderCollectionUpdateBadges() {
    if (!collectionEnabled) {
      document.querySelectorAll('.builder-collection-badge').forEach(b=>b.remove());
      return;
    }
    document.querySelectorAll('.builder-card').forEach(card => {
      const id = card.dataset.cardId;
      const owned = collectionData[id] || 0;
      const badge = card.querySelector('.builder-collection-badge') ||
                    document.createElement('div');
      if (!badge.parentNode) card.appendChild(badge);
      badge.className = 'builder-collection-badge' + (owned >= 3 ? ' has-playset' : '');
      badge.textContent = owned;
    });
  }

  // -----------------------------------------------------------------
  // 7. NO-RESTRICTION TOGGLE
  // -----------------------------------------------------------------
  function wireNoRestrictionToggle() {
    const btn = document.getElementById('no-restriction-btn');
    if (!btn) return;
    btn.addEventListener('click', () => {
      noRestriction = !noRestriction;
      btn.classList.toggle('active', noRestriction);
    });
  }

  // -----------------------------------------------------------------
  // 8. MANA CURVE CHART
  // -----------------------------------------------------------------
  function initManaChart() {
    const ctx = document.getElementById('manaCurveChart').getContext('2d');
    manaChart = new Chart(ctx, {
      type: 'bar',
      data: { labels: [], datasets: [{ label: 'Cards', data: [], backgroundColor: '#4d8e2f' }] },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: { title: { display: true, text: 'Energy' } },
          y: { beginAtZero: true, title: { display: true, text: 'Count' } }
        }
      }
    });
  }
  function updateManaChart(deck) {
    const curve = Array(13).fill(0); // 0-12
    deck.forEach(c => { const cost = c.cost; if (cost >=0 && cost <=12) curve[cost] += c.quantity; });
    manaChart.data.labels = curve.map((_,i)=>i);
    manaChart.data.datasets[0].data = curve;
    manaChart.update('quiet');
  }

  // -----------------------------------------------------------------
  // 9. DECK ACTIONS (save / export code)
  // -----------------------------------------------------------------
  function wireDeckActions() {
    document.getElementById('saveDeckBtn').addEventListener('click', saveDeck);
    document.getElementById('clearDeckBtn').addEventListener('click', () => confirm('Clear all cards?') && clearDeck());
    document.getElementById('exportDeckBtn').addEventListener('click', exportDeck);
    document.getElementById('generateExportImage').addEventListener('click', () => renderDeckImage().finally(() => {}));
  }

  async function saveDeck() {
    const name = document.getElementById('deckName').value.trim() || 'Untitled Deck';
    const desc = document.getElementById('deckDescription').value.trim();
    const id   = document.getElementById('deckId').value;

    if (!currentDeck.length) return alert('Deck is empty');
    const form = new FormData();
    form.append('action','save');
    form.append('deck_id',id);
    form.append('deck_name',name);
    form.append('description',desc);
    form.append('cards',JSON.stringify(currentDeck));

    const r = await fetch('api/deck.php',{method:'POST',body:form});
    const d = await r.json();
    if (d.success) {
      alert(d.message);
      document.getElementById('deckId').value = d.deck_id;
      history.replaceState(null,null,'deck_builder.php?deck_id='+d.deck_id);
    } else alert(d.message);
  }

  function clearDeck() {
    currentDeck = [];
    document.getElementById('deckName').value = 'Untitled Deck';
    document.getElementById('deckDescription').value = '';
    document.getElementById('deckId').value = '';
    updateDeckDisplay();
  }

  async function exportDeck() {
    const id = document.getElementById('deckId').value;
    if (!id) return alert('Save the deck first');
    const form = new FormData();
    form.append('action','export');
    form.append('deck_id',id);
    const r = await fetch('api/deck.php',{method:'POST',body:form});
    const d = await r.json();
    if (d.success) {
      navigator.clipboard.writeText(d.deck_code).then(()=>alert('Deck code copied!'));
    } else alert(d.message);
  }

  // -----------------------------------------------------------------
  // 10. IMAGE EXPORT (Riftmana style)
  // -----------------------------------------------------------------
  async function renderDeckImage() {
    const link = document.getElementById('downloadExportImage');
    const deckName = document.getElementById('deckName').value.trim() || 'Riftmana Deck';

    // ---- 1. collect data ----------------------------------------------------
    const deckData = {
      legend: null,
      battlefields: [],
      mains: [],
      sideboard: []
    };
    currentDeck.forEach(c => {
      const card = cardDatabase[c.id];
      const url = card.card_art_url || '';
      const entry = { url, qty: c.quantity };
      if (card.rarity?.toLowerCase() === 'champion') deckData.legend = entry;
      else if (card.card_type?.toLowerCase() === 'battlefield') deckData.battlefields.push(entry);
      else deckData.mains.push(entry);
    });

    // ---- 2. layout -----------------------------------------------------------
    const { layout, mainCards } = calculateLayout(deckData, !!deckName);

    // ---- 3. canvas -----------------------------------------------------------
    const { canvas, ctx } = createCanvas(EXPORT_CONFIG.canvas.width, layout.canvasHeight);
    const cfg = EXPORT_CONFIG.canvas;

    // ---- 4. load images ------------------------------------------------------
    const urls = [...new Set([ ...deckData.battlefields.map(b=>b.url),
                               ...mainCards.map(m=>m.url),
                               EXPORT_CONFIG.brand.iconUrl ].filter(Boolean))];
    const images = await Promise.all(urls.map(loadImage));
    const imgMap = new Map(); images.forEach(i=> imgMap.set(i.url, i.img));

    // ---- 5. draw -------------------------------------------------------------
    drawBackground(ctx, EXPORT_CONFIG.canvas.width, layout.canvasHeight);
    let y = cfg.padding;

    // deck name
    if (deckName) {
      ctx.fillStyle = EXPORT_CONFIG.colors.text;
      ctx.font = `700 ${EXPORT_CONFIG.deckName.fontSize}px system-ui, sans-serif`;
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.fillText(deckName, EXPORT_CONFIG.canvas.width/2, y + EXPORT_CONFIG.deckName.fontSize/2);
      y += EXPORT_CONFIG.deckName.fontSize + cfg.padding;
    }

    // legend (rotated)
    if (deckData.legend && imgMap.has(deckData.legend.url)) {
      const img = imgMap.get(deckData.legend.url);
      const h = layout.left.legendHeight;
      ctx.save();
      ctx.translate(cfg.padding + h/2, y + h/2);
      ctx.rotate(Math.PI/2);
      drawImageCover(ctx, img, -h/2, -layout.left.width/2, h, layout.left.width);
      ctx.restore();
      drawCountBadge(ctx, cfg.padding, y, layout.left.width, h, deckData.legend.qty);
      y += h + layout.left.legendToBfGap;
    }

    // battlefields
    deckData.battlefields.forEach(bf => {
      const img = imgMap.get(bf.url);
      if (!img) return;
      const h = layout.left.battlefieldSlotHeight;
      const w = layout.left.width;
      ctx.save();
      ctx.translate(cfg.padding + w/2, y + h/2);
      ctx.rotate(Math.PI/2);
      drawImageCover(ctx, img, -h/2, -w/2, h, w);
      ctx.restore();
      drawCountBadge(ctx, cfg.padding, y, w, h, bf.qty);
      y += h + layout.left.battlefieldGap;
    });

    // branding
    if (deckData.battlefields.length) {
      y += EXPORT_CONFIG.brand.topMargin;
      const brand = imgMap.get(EXPORT_CONFIG.brand.iconUrl);
      let x = cfg.padding;
      if (brand) {
        ctx.drawImage(brand, x, y, EXPORT_CONFIG.brand.iconSize, EXPORT_CONFIG.brand.iconSize);
        x += EXPORT_CONFIG.brand.iconSize + EXPORT_CONFIG.brand.gap;
      }
      ctx.fillStyle = EXPORT_CONFIG.colors.text;
      ctx.font = '600 26px system-ui, sans-serif';
      ctx.textBaseline = 'middle';
      ctx.fillText(EXPORT_CONFIG.brand.text || 'Rift Mana', x, y + EXPORT_CONFIG.brand.iconSize/2);
      y += EXPORT_CONFIG.brand.iconSize + cfg.padding;
    }

    // right panel (main grid)
    const rightX = layout.right.x;
    const slotW = layout.right.slotWidth;
    const slotH = layout.right.slotHeight;
    mainCards.forEach((c,i) => {
      const img = imgMap.get(c.url);
      if (!img) return;
      const row = Math.floor(i / layout.right.columns);
      const col = i % layout.right.columns;
      const x = rightX + col * (slotW + cfg.gap);
      const yy = y + row * (slotH + cfg.gap);
      drawImageCover(ctx, img, x, yy, slotW, slotH);
      drawCountBadge(ctx, x, yy, slotW, slotH, c.qty);
    });

    // ---- 6. download ---------------------------------------------------------
    const file = (deckName + '.jpg').replace(/[^\w.-]+/g, '_');
    canvas.toBlob(blob => {
      const url = URL.createObjectURL(blob);
      link.href = url; link.download = file; link.style.display = 'inline-flex';
    }, EXPORT_CONFIG.format, EXPORT_CONFIG.quality);
  }

  // -----------------------------------------------------------------
  // 11. CANVAS HELPERS (layout, drawing, images)
  // -----------------------------------------------------------------
  function calculateLayout(deckData, hasName) {
    const cfg = EXPORT_CONFIG.canvas;
    const cols = 8;
    const slotW = Math.floor((cfg.width - cfg.padding*2 - cfg.gap*(cols-1)) / cols);
    const slotH = Math.floor(slotW / (2.5/3.5));

    const mainCards = [...deckData.mains];
    const mainRows = Math.ceil(mainCards.length / cols);
    const sideRows = Math.ceil(deckData.sideboard.length / cols);

    const layout = {
      left: { width: 250, legendHeight: 350, legendToBfGap: 30,
              battlefieldSlotHeight: 120, battlefieldGap: 12 },
      right: { x: cfg.padding + layout.left.width + 60, columns: cols,
               slotWidth: slotW, slotHeight: slotH },
      canvasHeight: 0
    };
    layout.right.height = mainRows * slotH + Math.max(0, mainRows-1)*cfg.gap;
    const deckNameHeight = hasName ? 80 : 0;
    layout.canvasHeight = deckNameHeight + layout.right.height + cfg.padding*2;
    return { layout, mainCards };
  }

  function createCanvas(w,h){ const c=document.createElement('canvas'); c.width=w; c.height=h; return {canvas:c, ctx:c.getContext('2d')}; }
  function drawBackground(ctx,w,h){ ctx.fillStyle = '#0a0a0a'; ctx.fillRect(0,0,w,h); }
  function drawImageCover(ctx,img,x,y,w,h){ const r = img.width / img.height; const s = Math.max(w/img.width, h/img.height); const nw = img.width*s, nh = img.height*s; ctx.drawImage(img, x + (w-nw)/2, y + (h-nh)/2, nw, nh); }
  function drawCountBadge(ctx,x,y,w,h,qty){ ctx.fillStyle = 'rgba(0,0,0,0.6)'; ctx.font = 'bold 28px system-ui'; ctx.textBaseline='top'; ctx.fillText(qty, x + w - 40, y + h - 40); }
  function loadImage(url){ return new Promise(r=>{ const i=new Image(); i.onload =()=>r({url,img:i}); i.onerror=()=>r({url,img:null}); i.src = url; }); }

  // -----------------------------------------------------------------
  // 12. MODALS
  // -----------------------------------------------------------------
  function wireModals() {
    const modal = document.getElementById('loadDeckModal');
    const close = modal.querySelector('.close');
    document.getElementById('loadDeckBtn').addEventListener('click',()=>modal.classList.add('active'));
    close.addEventListener('click',()=>modal.classList.remove('active'));
    window.addEventListener('click', e => { if (e.target===modal) modal.classList.remove('active'); });
  }

  // -----------------------------------------------------------------
  // 13. UTILS
  // -----------------------------------------------------------------
  function debounce(fn, wait) {
    let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a),wait); };
  }

  // expose for deleteDeck in PHP
  window.deleteDeck = async (deckId) => {
    if (!confirm('Delete this deck permanently?')) return;
    const form = new FormData(); form.append('action','delete'); form.append('deck_id',deckId);
    const r = await fetch('api/deck.php',{method:'POST',body:form});
    const d = await r.json(); alert(d.message); if (d.success) location.reload();
  };
})();
