;(()=>{
	// deck-builder.js

// Global color mapping.
var colorMap = {
  'Fury': '#e0292e',
  'Calm': '#4d8e2f',
  'Mind': '#2a72a0',
  'Body': '#e6700d',
  'Chaos': '#6e478f',
  'Order': '#ccae0b'
};
var typeIconBaseUrl = '/wp-content/uploads/Icons/svg/';
var ignoreFilters = false;
var noRestriction = false;
var builderCollectionEnabled = false;
var builderCollectionData = {};
var builderCollectionFetched = false;

// toggle button behavior
var noRestrBtn = document.getElementById('no-restriction-btn');
if ( noRestrBtn ) {
  noRestrBtn.addEventListener('click', function(){
    noRestriction = !noRestriction;
    this.classList.toggle('active', noRestriction);
    this.setAttribute('aria-pressed', noRestriction);
    // you can also, e.g., change the counter text/color here
  });
}
// Helper for solid color or gradient.
function getBackgroundStyle(color) {
  if (color.indexOf(' ') > -1) {
    var colors = color.split(' ').map(function(c) {
      return colorMap[c] || '#333';
    });
    return 'linear-gradient(to right, ' + colors.join(', ') + ')';
  } else {
    return colorMap[color] || '#333';
  }
}

/* ============================================================================
   COLLECTION INTEGRATION FUNCTIONS
============================================================================ */

/**
 * Fetch user's collection counts from server
 */
function builderCollectionFetchCounts() {
  console.log('=== FETCH COLLECTION COUNTS ===');
  console.log('Already fetched?', builderCollectionFetched);
  
  if (builderCollectionFetched) {
    console.log('Using cached data');
    builderCollectionUpdateBadges();
	updateQuantityControlsCollectionState();
    return;
  }
  
  // Show loading state
  jQuery('#builder-collection-label').text('Loading...');
  console.log('Starting AJAX request...');
  
  jQuery.ajax({
    url: deck_ajax.ajax_url,
    type: 'POST',
    data: {
      action: 'builder_collection_get_counts',
      security: deck_ajax.nonce
    },
success: function(response) {
  console.log('AJAX Success:', response);
  if (response.success) {
    builderCollectionData = response.data.collection || {};
    builderCollectionFetched = true;
    console.log('Collection data loaded:', Object.keys(builderCollectionData).length, 'cards');
    console.log('Sample data:', Object.entries(builderCollectionData).slice(0, 5));
    builderCollectionUpdateBadges();
    updateQuantityControlsCollectionState(); // Changed from updateDeckCardCollectionCounts
    jQuery('#builder-collection-label').text('Show\nCollection');
  } else {
    console.error('Failed to fetch collection:', response.data.message);
    jQuery('#builder-collection-label').text('Error');
    jQuery('#builder-collection-checkbox').prop('checked', false);
    builderCollectionEnabled = false;
  }
},
    error: function(xhr, status, error) {
      console.error('AJAX error:', status, error);
      jQuery('#builder-collection-label').text('Error');
      jQuery('#builder-collection-checkbox').prop('checked', false);
      builderCollectionEnabled = false;
    }
  });
}

/**
 * Update collection badges on all visible cards
 */
function builderCollectionUpdateBadges() {
  console.log('=== UPDATE BADGES ===');
  console.log('Enabled:', builderCollectionEnabled);
  console.log('Data keys:', Object.keys(builderCollectionData).length);
  
  if (!builderCollectionEnabled) {
    // Remove all badges
    console.log('Removing all badges');
    jQuery('.builder-collection-badge').remove();
    return;
  }
  
  // Loop through all cards and add/update badges
  var cardsProcessed = 0;
  var badgesAdded = 0;
  var badgesUpdated = 0;
  
  jQuery('.builder-card').each(function() {
    cardsProcessed++;
    var $card = jQuery(this);
    var cardId = ($card.data('card-id') || '').toString().toLowerCase();
    
    if (!cardId) {
      console.log('Card #' + cardsProcessed + ': No card ID, skipping');
      return;
    }
    
    var count = builderCollectionGetCount(cardId);
    var cardType = ($card.data('type') || '').toString().toLowerCase();
    var $existingBadge = $card.find('.builder-collection-badge');
    
    if (cardsProcessed <= 5) {
      console.log('Card #' + cardsProcessed + ':', cardId, 'Type:', cardType, 'Count:', count, 'Existing badge:', $existingBadge.length);
    }
    
    if (count > 0) {
      // Determine the required amount based on card type
      var requiredAmount = 3; // Default for most cards
      
      if (cardType === 'legend' || cardType === 'battlefield') {
        requiredAmount = 1; // Legends and Battlefields only need 1
      }
      
      // Determine badge class based on count vs required amount
      var badgeClass = 'builder-collection-badge';
      if (count >= requiredAmount) {
        badgeClass += ' has-playset';
      } else {
        badgeClass += ' needs-more';
      }
      
      if ($existingBadge.length) {
        // Update existing badge text and class
        $existingBadge
          .text(count)
          .attr('class', badgeClass);
        badgesUpdated++;
      } else {
        // Create new badge with appropriate class
        var $badge = jQuery('<div class="' + badgeClass + '">' + count + '</div>');
        $card.append($badge);
        badgesAdded++;
      }
    } else {
      if ($existingBadge.length) {
        $existingBadge.remove();
      }
    }
  });
  
  console.log('Cards processed:', cardsProcessed);
  console.log('Badges added:', badgesAdded);
  console.log('Badges updated:', badgesUpdated);
  console.log('Total badges now:', jQuery('.builder-collection-badge').length);
}

/**
 * Update quantity controls visual state based on collection
 */
function updateQuantityControlsCollectionState() {
  if (!builderCollectionEnabled) {
    // Remove warning state from all quantity controls
    jQuery('#selected-cards .quantity-controls, #sideboard-cards .quantity-controls').removeClass('insufficient-collection');
    return;
  }
  
  // Check both main deck and sideboard
  jQuery('#selected-cards .deck-card, #sideboard-cards .deck-card').each(function() {
    var $card = jQuery(this);
    var cardId = ($card.data('card-id') || '').toString().toLowerCase();
    var cardType = ($card.data('type') || '').toString().toLowerCase();
    var deckQuantity = parseInt($card.data('quantity'), 10) || 0;
    
    if (!cardId) return;
    
    var collectionCount = builderCollectionGetCount(cardId);
    var $quantityControls = $card.find('.quantity-controls');
    
    // Check if user has added more cards than they own
    if (deckQuantity > collectionCount) {
      $quantityControls.addClass('insufficient-collection');
    } else {
      $quantityControls.removeClass('insufficient-collection');
    }
  });
}

/**
 * Get collection count for a specific card ID
 */
function builderCollectionGetCount(cardId) {
  if (!cardId) return 0;
  cardId = cardId.toString().toLowerCase();
  return builderCollectionData[cardId] || 0;
}

/**
 * Handle collection toggle change
 */
function builderCollectionToggle() {
  console.log('=== COLLECTION TOGGLE ===');
  var isChecked = jQuery('#builder-collection-checkbox').is(':checked');
  builderCollectionEnabled = isChecked;
  
  console.log('Toggle checked:', isChecked);
  console.log('Collection enabled:', builderCollectionEnabled);
  
  if (isChecked) {
    builderCollectionFetchCounts();
  } else {
    builderCollectionUpdateBadges();
    updateQuantityControlsCollectionState(); // Changed from updateDeckCardCollectionCounts
  }
}

function isValidYouTubeVideoURL(url) {
  if (!url || typeof url !== 'string') return false;
  url = url.trim();

  // youtu.be/VIDEOID
  var reShort = /^(?:https?:)?\/\/(?:www\.)?youtu\.be\/[A-Za-z0-9_-]{11}(?:[\/?#&].*)?$/i;

  // *.youtube.com/watch?v=VIDEOID | /shorts/VIDEOID | /embed/VIDEOID
  var reLong  = /^(?:https?:)?\/\/(?:[\w-]+\.)?youtube\.com\/(?:watch\?v=|shorts\/|embed\/)[A-Za-z0-9_-]{11}(?:[\/?#&].*)?$/i;

  return reShort.test(url) || reLong.test(url);
}


// — Popup open / close —

// open popup, fill in all fields
function deckBuilderOpenPopup(imgElement, triggerMagnify) {
  triggerMagnify = triggerMagnify || false;
  if (imgElement.closest('.builder-card') && !triggerMagnify) return;

  var popup       = document.getElementById('deckBuilderPopup'),
      popupImage  = document.getElementById('deckBuilderPopupImage'),
      popupHeader = document.querySelector('.deck-builder-popup-header'),
      popupCardId = document.getElementById('deckBuilderPopupCardId'),
      popupCardName = document.getElementById('deckBuilderPopupCardName'),
      popupCost   = document.getElementById('deckBuilderPopupCost'),
      popupType   = document.getElementById('deckBuilderPopupType'),
      popupColor  = document.getElementById('deckBuilderPopupColor'),
      popupMight  = document.getElementById('deckBuilderPopupMight'),
      popupEffect = document.getElementById('deckBuilderPopupEffect'),
      popupTags   = document.getElementById('deckBuilderPopupTags'),
      popupLink   = document.getElementById('deckBuilderPopupLink'),
      popupSet    = document.getElementById('deckBuilderPopupSet'),
      popupRarity = document.getElementById('deckBuilderPopupRarity'),
      popuppower  = document.getElementById('deckBuilderPopupPower');

  // read attrs or parent dataset
  var cardId    = imgElement.getAttribute('data-card-id')   || '',
      cardName  = imgElement.getAttribute('data-card-name') || '',
      cost      = imgElement.getAttribute('data-cost')      || '',
      cardType  = imgElement.getAttribute('data-type')      || '',
	  cardTypeName = imgElement.getAttribute('data-type-name') || cardType,
      color     = imgElement.getAttribute('data-color')     || '',
      might     = imgElement.getAttribute('data-might')     || '',
      effect    = imgElement.getAttribute('data-effect')    || '',
      tags      = imgElement.getAttribute('data-sub-type')  || '',
      cardLink  = imgElement.getAttribute('data-card-link') || '';

	var parent = imgElement.closest('.builder-card, .deck-card');
	if (parent) {
	  cost     = cost     || parent.getAttribute('data-cost')      || '';
	  cardType = cardType || parent.getAttribute('data-type')      || '';
	  cardTypeName = cardTypeName || parent.dataset.typeName || parent.getAttribute('data-type-name') || cardType;
	  color    = color    || parent.getAttribute('data-color')     || '';
	  cardLink = cardLink || parent.getAttribute('data-card-link') || '';
	  cardName = cardName || parent.getAttribute('data-name')      || cardName;
	  might    = might    || parent.getAttribute('data-might')     || '';
	  effect   = effect   || parent.getAttribute('data-effect')    || '';
	  tags     = tags     || parent.getAttribute('data-sub-type')  || '';
	}
  if (!cardName) cardName = cardId;

  popupImage.src = imgElement.src;
  // battlefield image condiition 
  if (cardType.toLowerCase() === 'battlefield') {
  popupImage.classList.add('horizontal-card');
} else {
  popupImage.classList.remove('horizontal-card');
}
  popupCardId.textContent   = cardId;
  popupCardName.textContent = cardName;
  popupCost.textContent     = cost;

  // Type
popupType.innerHTML = '';
var typeIcon = document.createElement('img');
typeIcon.className = 'type-icon';
typeIcon.width = typeIcon.height = 17;
typeIcon.alt = cardType;
// Use the slug for the file path, not the name
var typeSlug = imgElement.getAttribute('data-type-slug') || cardType.toLowerCase().replace(/\s+/g, '-');
typeIcon.src = typeIconBaseUrl + encodeURIComponent(typeSlug) + '.svg';
popupType.appendChild(typeIcon);
popupType.appendChild(document.createTextNode(' ' + cardTypeName));

  // Color
  popupColor.innerHTML = '';
  if (color.trim()) {
    color.trim().split(/\s+/).forEach(function(col) {
      var ico = document.createElement('img');
      ico.className = 'type-icon';
      ico.width = ico.height = 28;
      ico.alt = col;
      ico.src = typeIconBaseUrl + encodeURIComponent(col.toLowerCase()) + '.svg';
      popupColor.appendChild(ico);
    });
    popupColor.appendChild(document.createTextNode(' ' + color.trim()));
  } else {
    popupColor.textContent = '—';
  }

  // Might
  popupMight.innerHTML = '';
  var mightIcon = document.createElement('img');
  mightIcon.className = 'might-icon';
  mightIcon.width = mightIcon.height = 28;
  mightIcon.alt = 'Might';
  mightIcon.src = typeIconBaseUrl + 'might.svg';
  popupMight.appendChild(mightIcon);
  popupMight.appendChild(document.createTextNode(' ' + might));

  // Effect w/ [ICON]
// clear old effect contents
while (popupEffect.firstChild) popupEffect.removeChild(popupEffect.firstChild);

var iconRegex = /\[(\w+)\]/g,
    lastIndex = 0,
    m;

iconRegex.lastIndex = 0;
while ((m = iconRegex.exec(effect)) !== null) {
  // text before this bracketed token
  if (m.index > lastIndex) {
    popupEffect.appendChild(
      document.createTextNode(effect.slice(lastIndex, m.index))
    );
  }

  var token = m[1].toLowerCase();

  if (/^\d+$/.test(token)) {
    // numeric token → white circle with black number
    var span = document.createElement('span');
    span.className = 'effect-icon-number';
    span.textContent = token;
    popupEffect.appendChild(span);

  } else {
    // SVG icon
    var img = document.createElement('img');
    img.width = img.height = 26;
    img.alt   = token;
    img.src   = typeIconBaseUrl + encodeURIComponent(token) + '.svg';

    // only [MIGHT] or [TAP] get the white/filter class
    if (token === 'might' || token === 'tap') {
      img.className = 'effect-icon effect-icon-might';
    } else {
      img.className = 'effect-icon';
    }

    popupEffect.appendChild(img);
  }

  lastIndex = iconRegex.lastIndex;
}

// any trailing text after the last token
if (lastIndex < effect.length) {
  popupEffect.appendChild(
    document.createTextNode(effect.slice(lastIndex))
  );
}


  // Keyword highlighting
  var html = popupEffect.innerHTML;
  html = html
  .replace(/\bACCELERATE\b/g,  '<span class="keyword keyword-accelerate">ACCELERATE</span>')
  .replace(/\bLEGION\b/g,      '<span class="keyword keyword-legion">LEGION</span>')
  .replace(/\bHIDDEN\b/g,      '<span class="keyword keyword-hidden">HIDDEN</span>')
  .replace(/\bTANK\b/g,        '<span class="keyword keyword-tank">TANK</span>')
  .replace(/\bASSAULT(?:\s*\d+)?\b/gi, '<span class="keyword keyword-assault">$&</span>')
  .replace(/\bSHIELD(?:\s*\d+)?\b/gi,  '<span class="keyword keyword-shield">$&</span>')
  .replace(/DEFLECT\s*\d+/gi,         '<span class="keyword keyword-deflect">$&</span>')
  .replace(/\bACTION\b/g,     '<span class="keyword keyword-action">ACTION</span>')
  .replace(/\bREACTION\b/g,   '<span class="keyword keyword-reaction">REACTION</span>')
  .replace(/\bADD\b/g,        '<span class="keyword keyword-add">ADD</span>')
  .replace(/\bVISION\b/g,     '<span class="keyword keyword-vision">VISION</span>')
  .replace(/\bMIGHTY\b/g,     '<span class="keyword keyword-mighty">MIGHTY</span>')
  .replace(/\bGANKING\b/g,    '<span class="keyword keyword-ganking">GANKING</span>')
  .replace(/\bTEMPORARY\b/g,  '<span class="keyword keyword-temporary">TEMPORARY</span>')
  .replace(/\bDEFLECT\b/g,    '<span class="keyword keyword-deflect">DEFLECT</span>')
  .replace(/\bDEATHKNELL\b/g, '<span class="keyword keyword-deathknell">DEATHKNELL</span>');
  popupEffect.innerHTML = html;

  // Tags / Set / Rarity / Power
  popupTags.textContent = tags;
  popupSet.textContent    = imgElement.getAttribute('data-set')    || '—';
  popupRarity.innerHTML   = '';
  var rarity = imgElement.getAttribute('data-rarity') || '';
  if (rarity.trim()) {
    var rI = document.createElement('img');
    rI.className = 'type-icon';
    rI.width = rI.height = 28;
    rI.alt = rarity;
    rI.src = typeIconBaseUrl + encodeURIComponent(rarity.toLowerCase()) + '.svg';
    popupRarity.appendChild(rI);
    popupRarity.appendChild(document.createTextNode(' ' + rarity));
  } else {
    popupRarity.textContent = '—';
  }

  popuppower.innerHTML = '';
  var powerText = (imgElement.getAttribute('data-power') || '').trim();
  powerText.split(/\s+/).filter(Boolean).forEach(function(val) {
    var ico = document.createElement('img');
    ico.className = 'type-icon';
    ico.width = ico.height = 28;
    if (val.includes('/')) {
      ico.alt = 'RainbowRune';
      ico.src = typeIconBaseUrl + 'rainbowrune.svg';
    } else {
      ico.alt = val;
      ico.src = typeIconBaseUrl + encodeURIComponent(val.toLowerCase()) + '.svg';
    }
    popuppower.appendChild(ico);
  });

  var baseUrl = 'https://riftmana.com/cards/';
  var slug    = (cardLink.trim() || cardId);
  popupLink.href = baseUrl + encodeURIComponent(slug);

  if (popupHeader) {
    popupHeader.style.background = getBackgroundStyle(color);
  }
  popup.style.display = 'flex';
}

// close popup
function deckBuilderClosePopup(event) {
  if (event.target.id === 'deckBuilderPopup' ||
      event.target.classList.contains('deck-builder-popup-close')) {
    document.getElementById('deckBuilderPopup').style.display = 'none';
  }
}

window.deckBuilderOpenPopup  = deckBuilderOpenPopup;
window.deckBuilderClosePopup = deckBuilderClosePopup;


// — jQuery logic —  
jQuery(document).ready(function($){
	
	
	// --- client memo cache for filter+page payloads (10 min TTL) ---
var clientCache = new Map(); // key -> { t, data }
var CLIENT_TTL  = 10 * 60 * 1000;

function keyFor(params){
  return JSON.stringify(params);
}
function cacheGet(k){
  var hit = clientCache.get(k);
  if (!hit) return null;
  if (Date.now() - hit.t > CLIENT_TTL) { clientCache.delete(k); return null; }
  return hit.data;
}
function cacheSet(k, data){
  if (clientCache.size > 30) {
    var first = clientCache.keys().next().value;
    clientCache.delete(first);
  }
  clientCache.set(k, { t: Date.now(), data: data });
}

	
	
  // debounce helper
  function debounce(fn, delay){
    var timer = null;
    return function(){
      clearTimeout(timer);
      var args = arguments, ctx = this;
      timer = setTimeout(function(){ fn.apply(ctx,args); }, delay);
    };
  }
  
  // helper for sorting cards
function getSortCriteria() {
  var sel = $('#card-filter-order').val();
  if (sel) return sel;
  var cat = $('#available-tabs button.active').data('cat');
  return cat === 'Main' ? 'cost' : 'card-id';
}  
  
  
$('#deck-viewability-checkbox').on('change', function(){
  var isPublic = this.checked;
  $('#deck-viewability-label').text( isPublic ? 'Public' : 'Private' );
});

$('#alt-art-checkbox').on('change', debounce(function() {
  var showing = $(this).is(':checked');
  $('#alt-art-label').text( showing ? 'Hide\nAlt Art' : 'Show\nAlt Art' );
  
  // instead of doing a full AJAX reload, just re-apply the client-side filter
  filterCards();
  syncAvailableQuantities();
}, 100));

// Collection toggle handler
$('#builder-collection-checkbox').on('change', debounce(function() {
  builderCollectionToggle();
}, 100));

  // cache selectors & state
  var $availContainer  = $('#available-cards'),
      $availGrid       = $availContainer,
      $searchInput     = $('#card-filter'),
      $typeBoxes       = $('#card-filter-types input[type=checkbox]'),
      $colorBoxes      = $('#card-filter-colors input[type=checkbox]'),
      $costBoxes       = $('#card-filter-cost input[type=checkbox]'),
      $rarityBoxes     = $('#card-filter-rarity input.card-rarity-checkbox'),
      loading          = false,
      currentPage      = (parseInt(deck_ajax.initial_page,10) || 1) + 1,
      maxPages         = parseInt(deck_ajax.max_pages,10)    || 1,
      perPage          = parseInt(deck_ajax.per_page,10)     || 40,
      initialMaxPages  = maxPages;
	  
const tabTypeMap = {
  Legend:      ['legend'],
  Main:        ['unit','spell','signature-spell', 'signature-unit', 'gear','champion'],
  Battlefield: ['battlefield'],
  Rune:        ['rune'],
};


//for the popup trigger

$(document).on('click', '.magnify-icon', function(e){
  e.stopPropagation();
  const img = this.closest('.builder-card')?.querySelector('img.builder-card-image');
  if (img) deckBuilderOpenPopup(img, true);
});



function getFilters(){
  if ( ignoreFilters ) {
    return { search:'', types:[], colors:[], costs:[], rarities:[], sets:[] };
  }

  const activeTab = $('#available-tabs button.active').data('cat');

  // Get checked types
  let checkedTypes = $('#card-filter-types input:checked').map((i,e)=>e.value).get();
  
  // Expand filter selections to include signature variants
  const expandedTypes = [];
  checkedTypes.forEach(type => {
    expandedTypes.push(type);
    
    // If "Spell" is checked, also include "Signature Spell"
    if (type === 'Spell') {
      expandedTypes.push('signature-spell');
    }
    // If "Unit" is checked, also include "Signature Unit"
    else if (type === 'Unit') {
      expandedTypes.push('signature-unit');
    }
    // If "Signature" is checked, expand it to both signature types
    else if (type === 'Signature') {
      expandedTypes.push('signature-spell', 'signature-unit');
    }
  });

  // Remove duplicates and the original "Signature" if it was added
  const finalTypes = [...new Set(expandedTypes)].filter(t => t !== 'Signature');

  // base filters from the checkboxes
  const filters = {
    search:   ($('#card-filter').val()||'').trim(),
    types:    finalTypes,
    // omit color filtering when on Battlefield
    colors:   activeTab === 'Battlefield'
                ? []
                : $('#card-filter-colors input:checked').map((i,e)=>e.value).get(),
    costs:    $('#card-filter-cost input:checked').map((i,e)=>e.value).get(),
    rarities: $('#card-filter-rarity input:checked').map((i,e)=>e.value).get(),
    sets:     [$('#card-filter-set').val()].filter(Boolean)
  };

  // inject the tab's default types if none are manually selected
  if (
    activeTab &&
    tabTypeMap[activeTab] &&
    checkedTypes.length === 0
  ) {
    filters.types = tabTypeMap[activeTab];
  }

  return filters;
}

  // show/hide DOM cards by cost+color
function filterCards(){
    // 1) Alt-art toggle state
    var showAlt = $('#alt-art-checkbox').is(':checked');

    // 2) Current tab & filters
    const activeTab     = $('#available-tabs button.active').data('cat');
    const selCosts      = $('#card-filter-cost input:checked').map((i,e)=>e.value).get();
    const selColors     = activeTab === 'Battlefield'
                          ? []
                          : $('#card-filter-colors input:checked').map((i,e)=>e.value).get();
    const typeWhitelist = tabTypeMap[activeTab] || [];

    // 3) Loop through every card
$('.builder-card').each(function(){
  var $c      = $(this);
  var altCode = ($c.data('altCode') || '').toString();

  // Only treat as alt if an alt code actually exists AND it doesn't end in '-1'
  // (adjust the regex if your “main” variant differs)
  var isAlt = !!altCode && !/-(?:1|001)$/i.test(altCode);

  // read attributes safely
  var costAttr = ($c.data('cost') || '').toString().trim();  // e.g. "04" or "4"
  var costNum  = parseInt(costAttr, 10); if (isNaN(costNum)) costNum = 0;

  var cols = ($c.data('color') || '').toString().trim()
              .split(/\s+/).filter(Boolean);                 // ["Fury"] or ["Fury","Mind"]
  var type = ($c.data('type')  || '').toString().toLowerCase();

  // Hide alts when toggle is OFF
  if (!showAlt && isAlt) { $c.hide(); return; }

  // Cost check (supports 8+ and zero-padded values)
  var okCost = !selCosts.length || selCosts.some(function(s){
    if (s === '8+') return costNum >= 8;
    var want = parseInt(s, 10); if (isNaN(want)) want = 0;
    return costNum === want || costAttr === s || costAttr === String(want).padStart(2,'0');
  });

  // Color check (skipped for Battlefield tab)
  var okColor = !selColors.length || selColors.some(function(s){ return cols.includes(s); });

  // Type check from the tab whitelist
  var okType  = !typeWhitelist.length || typeWhitelist.includes(type);

// Signature filtering: only show if matches Legend's subtype
var okSignature = true;
if (type === 'signature-spell' || type === 'signature-unit') {
  var legendSubtype = window.currentLegendSubtype || '';
  var cardSubtype = ($c.data('sub-type') || '').toString().trim();
  okSignature = legendSubtype && cardSubtype && cardSubtype === legendSubtype;
}

  $c.toggle(okCost && okColor && okType && okSignature);
});

  // Update collection badges if enabled
  if (builderCollectionEnabled) {
    builderCollectionUpdateBadges();
  }
}

// sortAvailableCards: criteria is one of 'card-id', 'cost', 'name'
function sortAvailableCards(criteria) {
  var $container = $('#available-cards'),
      cards      = $container.find('.builder-card').get();

  cards.sort(function(a, b) {
    function getVal(el){
      if (criteria === 'card-id') return el.dataset.cardId || '';
      if (criteria === 'name')    return el.dataset.name    || '';
      if (criteria === 'cost')    return parseInt(el.dataset.cost || '0', 10) || 0;
      return '';
    }
    var av = getVal(a), bv = getVal(b);
    return (criteria === 'cost') ? (av - bv) : String(av).localeCompare(String(bv), undefined, {numeric:true});
  });

  $container.append(cards);
}





  // reload on filter change
function reloadCards() {
  const filters = getFilters();
  const sortBy  = getSortCriteria();
  const $grid   = $('#available-cards');
  $grid.scrollTop(0);
  
  
  // Try client cache for page 1
var cacheKey1 = keyFor({
  page:1, per_page:perPage, post_type: $('#card-post-type').val(),
  filters: filters, sort_by: sortBy, sort_order:'ASC'
});
var cached1 = cacheGet(cacheKey1);
if (cached1) {
  $grid.html(cached1.html);
  sortAvailableCards(sortBy);
  filterCards();
  syncAvailableQuantities();
  maxPages    = parseInt(cached1.max_pages, 10) || 1;
  currentPage = 2;
  $grid.removeClass('loading');
  return; // skip network
}


  // 1) reset pagination
  currentPage = 1;
  maxPages    = initialMaxPages;

  // 2) show skeletons & clear old cards
  $grid
    .addClass('loading')
    .empty()
    .append(Array(perPage).fill('<div class="builder-card loading"></div>').join(''));

  // 3) fetch just the first page
  $.post(deck_ajax.ajax_url, {
    action:    'load_more_cards',
    security:  deck_ajax.load_more_nonce,
    page:      1,
    per_page:  perPage,
    post_type: $('#card-post-type').val(),
    filters:   filters,
    sort_by:   sortBy,
    sort_order:'ASC'
  })
  .done(function(resp) {
    if (resp.success) {
      var payload = resp.data;

      // cache the first page for this request signature
      cacheSet(keyFor({
        page:1, per_page:perPage, post_type: $('#card-post-type').val(),
        filters: filters, sort_by: sortBy, sort_order:'ASC'
      }), payload);

      // inject the real cards
      $grid.html(payload.html);

      // sort instantly in the browser (feels snappier)
      sortAvailableCards(sortBy);

      // apply client-side filters & sync quantities
      filterCards();
      syncAvailableQuantities();

      // update pagination state
      maxPages    = parseInt(payload.max_pages, 10) || 1;
      currentPage = 2;

      // prefetch page 2 into cache (optional)
      if (currentPage <= maxPages) {
        var preKey = keyFor({
          page:2, per_page:perPage, post_type: $('#card-post-type').val(),
          filters: filters, sort_by: sortBy, sort_order:'ASC'
        });
        if (!cacheGet(preKey)) {
          $.post(deck_ajax.ajax_url, {
            action:'load_more_cards', security: deck_ajax.load_more_nonce,
            page:2, per_page: perPage, post_type: $('#card-post-type').val(),
            filters: filters, sort_by: sortBy, sort_order:'ASC'
          }).done(function(r2){ if (r2.success) cacheSet(preKey, r2.data); });
        }
      }
    }
  })

  .always(function() {
    // 4) remove loading flag so real cards fade in
    $grid.removeClass('loading');
  });
}



function loadCategory(cat, perPage) {
  const typesMap = {
    Legend:      ['legend'],
    Battlefield: ['battlefield'],
    Rune:        ['rune'],
    Main:        ['unit','spell', 'signature-spell', 'signature-unit', 'gear','champion','token']
  };

  const filters = getFilters();
  const selectedTypes = $('#card-filter-types input:checked').map((i,e)=>e.value).get();
  filters.types = selectedTypes.length ? selectedTypes : (typesMap[cat] || []);

  // ✅ NEW: Check cache first
  const sortBy = getSortCriteria();
  const cacheKey1 = keyFor({
    page:1, per_page:perPage, post_type: $('#card-post-type').val(),
    filters: filters, sort_by: sortBy, sort_order:'ASC'
  });
  const cached1 = cacheGet(cacheKey1);
  
  if (cached1) {
    $('#available-cards').html(cached1.html);
    sortAvailableCards(sortBy);
    $('#available-tabs button[data-cat="' + cat + '"]').addClass('active');
    filterCards();
    syncAvailableQuantities();
    maxPages = parseInt(cached1.max_pages, 10) || 1;
    currentPage = 2;
    
    if (builderCollectionEnabled) {
      builderCollectionUpdateBadges();
    }
    return; // ✅ Skip AJAX entirely
  }

  // Rest of existing loadCategory code...
  $('#available-cards')
    .addClass('loading')
    .empty()
    .append(Array(perPage).fill('<div class="builder-card loading"></div>').join(''));

  $.post(deck_ajax.ajax_url, {
    action:    'load_more_cards',
    security:  deck_ajax.load_more_nonce,
    page:      1,
    per_page:  perPage,
    post_type: $('#card-post-type').val(),
    filters:   filters,
    sort_by:   sortBy,
    sort_order:'ASC'
  }).done(resp => {
    if (resp.success) {
      var payload = resp.data;
      
 
      cacheSet(cacheKey1, payload);

      $('#available-cards').html(payload.html);
      sortAvailableCards(sortBy);
      $('#available-tabs button[data-cat="' + cat + '"]').addClass('active');
      filterCards();
      syncAvailableQuantities();
      maxPages = parseInt(payload.max_pages, 10) || 1;
      currentPage = 2;
      loading = false;

 
      if (currentPage <= maxPages) {
        var preKey = keyFor({
          page:2, per_page: perPage, post_type: $('#card-post-type').val(),
          filters: filters, sort_by: sortBy, sort_order:'ASC'
        });
        if (!cacheGet(preKey)) {
          $.post(deck_ajax.ajax_url, {
            action:'load_more_cards', security: deck_ajax.load_more_nonce,
            page:2, per_page: perPage, post_type: $('#card-post-type').val(),
            filters: filters, sort_by: sortBy, sort_order:'ASC'
          }).done(function(r2){ if (r2.success) cacheSet(preKey, r2.data); });
        }
      }
    }
  }).always(() => {
    $('#available-cards').removeClass('loading');
  });
  
  if (builderCollectionEnabled) {
    builderCollectionUpdateBadges();
  }
}

// 3) Hook your filters and sort-order dropdown to call reloadCards(), not fetchPage()
var debouncedReload = debounce(function(e) {
  // keyCode 9 is Tab
  if (e.which === 9) return;
  reloadCards();
}, 300);

$searchInput.on('keyup', debouncedReload);
var $setDropdown = $('#card-filter-set');
$typeBoxes.add($colorBoxes).add($costBoxes).add($rarityBoxes).add($setDropdown)
  .on('change', debounce(reloadCards,100));
$('#card-filter-order').on('change', debounce(reloadCards,100));

// 4) On initial load, do a full preload+filter
reloadCards();


// ============================================================
//  AUTO-SAVE DECK PROGRESS TO LOCALSTORAGE
// ============================================================

var AUTOSAVE_KEY = 'riftmana_deck_builder_autosave';
var AUTOSAVE_DELAY = 2000; // 2 seconds after last change
var autosaveTimer = null;

function triggerAutoSave() {
  clearTimeout(autosaveTimer);
  autosaveTimer = setTimeout(function() {
    saveDeckProgress();
  }, AUTOSAVE_DELAY);
}

function saveDeckProgress() {
  try {
    // Skip if we're editing an existing deck (already saved to server)
    if ($('#edit-deck-id').val()) {
      return;
    }

    var progress = {
      timestamp: Date.now(),
      deckName: $('#deck-name').val() || '',
      deckDescription: $('#deck-description').val() || '',
      youtubeLink: $('#deck-youtube-link').val() || '',
      noRestriction: noRestriction,
      viewability: $('#deck-viewability-label').text() || 'Public',
      selectedTags: $('#deck-tags-container input.deck-tag-checkbox:checked')
        .map(function() { return $(this).val(); }).get(),
      mainDeck: [],
      sideboard: []
    };

    // Collect main deck cards
    $('#selected-cards .deck-card').each(function() {
      progress.mainDeck.push({
        cardId: $(this).data('card-id'),
        quantity: parseInt($(this).data('quantity')) || 1
      });
    });

    // Collect sideboard cards
    $('#sideboard-cards .deck-card').each(function() {
      progress.sideboard.push({
        cardId: $(this).data('card-id'),
        quantity: parseInt($(this).data('quantity')) || 1
      });
    });

    // Only save if there's actual progress
    if (progress.mainDeck.length > 0 || progress.sideboard.length > 0 || 
        progress.deckName || progress.deckDescription) {
      localStorage.setItem(AUTOSAVE_KEY, JSON.stringify(progress));
      console.log('Deck progress auto-saved');
    }
  } catch (e) {
    console.error('Failed to auto-save deck progress:', e);
  }
}

function loadDeckProgress() {
	var restoredCards = false;
  try {
    // Don't load autosave if we're editing an existing deck
    if (window.editDeckData) {
      return;
    }

    // Check if there are already cards in the deck - if so, don't restore
    var hasExistingCards = $('#selected-cards .deck-card').length > 0 || 
                          $('#sideboard-cards .deck-card').length > 0;
    if (hasExistingCards) {
      return;
    }

    var saved = localStorage.getItem(AUTOSAVE_KEY);
    if (!saved) return;

    var progress = JSON.parse(saved);
    
    // ✅ Check if autosave is older than 2 hours
    var hoursSinceAutosave = (Date.now() - progress.timestamp) / (1000 * 60 * 60);
    if (hoursSinceAutosave > 2) {
      localStorage.removeItem(AUTOSAVE_KEY);
      return;
    }

    // Restore form fields
    if (progress.deckName) $('#deck-name').val(progress.deckName);
    if (progress.deckDescription) $('#deck-description').val(progress.deckDescription);
    if (progress.youtubeLink) $('#deck-youtube-link').val(progress.youtubeLink);
    if (progress.viewability) {
      var isPublic = progress.viewability === 'Public';
      $('#deck-viewability-checkbox').prop('checked', isPublic);
      $('#deck-viewability-label').text(progress.viewability);
      $('#deck-viewability-checkbox').trigger('change');
    }
    if (progress.noRestriction) {
      noRestriction = true;
      $('#no-restriction-btn').addClass('active').attr('aria-pressed', 'true');
    }
    if (progress.selectedTags && progress.selectedTags.length) {
      progress.selectedTags.forEach(function(tag) {
        $('#deck-tags-container input[value="' + tag + '"]').prop('checked', true);
      });
    }

    // Collect all card codes to fetch
    var allCodes = [];
    progress.mainDeck.forEach(function(card) {
      if (!allCodes.includes(card.cardId.toLowerCase())) {
        allCodes.push(card.cardId.toLowerCase());
      }
    });
    progress.sideboard.forEach(function(card) {
      if (!allCodes.includes(card.cardId.toLowerCase())) {
        allCodes.push(card.cardId.toLowerCase());
      }
    });

    if (allCodes.length === 0) return;

    // Show loading indicator
    $('#deck-loading-overlay').show();

    // Fetch all cards in one request
    $.post(deck_ajax.ajax_url, {
      action: 'load_more_cards',
      security: deck_ajax.load_more_nonce,
      post_type: $('#card-post-type').val(),
      page: 1,
      per_page: allCodes.length,
      filters: { codes: allCodes },
      use_card_id: 1
    })
    .done(function(resp) {
      if (!resp.success) return;

      var $temp = $('<div>').html(resp.data.html);

      // Restore main deck
      progress.mainDeck.forEach(function(card) {
        var $cardEl = $temp.find('.builder-card')
          .filter(function() {
            return ($(this).data('card-id') || '').toLowerCase() === card.cardId.toLowerCase();
          }).first();
        
        if ($cardEl.length) {
          for (var i = 0; i < card.quantity; i++) {
            addCardToDeck($cardEl);
			restoredCards = true;
          }
        }
      });

      // Switch to sideboard mode and restore sideboard
      if (progress.sideboard.length > 0) {
        $('.deck-info-tab').removeClass('active');
        $('.deck-info-tab[data-target="sideboard-view"]').addClass('active');
        $('#deck-view, #sideboard-view').hide();
        $('#sideboard-view').show();

        progress.sideboard.forEach(function(card) {
          var $cardEl = $temp.find('.builder-card')
            .filter(function() {
              return ($(this).data('card-id') || '').toLowerCase() === card.cardId.toLowerCase();
            }).first();
          
          if ($cardEl.length) {
            for (var i = 0; i < card.quantity; i++) {
              addCardToDeck($cardEl);
			  restoredCards = true;
            }
          }
        });

        // Switch back to deck view
        $('.deck-info-tab').removeClass('active');
        $('.deck-info-tab[data-target="deck-view"]').addClass('active');
        $('#sideboard-view').hide();
        $('#deck-view').show();
      }

      syncAvailableQuantities();
	      if (builderCollectionEnabled) {
        builderCollectionUpdateBadges();
      }
      console.log('Restored cards flag:', restoredCards);
	  if (restoredCards) {  // CHECK THE FLAG
        $('<div class="deck-saved-notification">Previous deck progress restored!</div>')
          .appendTo('body')
          .delay(3000)
          .fadeOut(500, function() { $(this).remove(); });
      }
    })
    .always(function() {
      $('#deck-loading-overlay').hide();
    });

  } catch (e) {
    console.error('Failed to load deck progress:', e);
  }
}

function clearDeckProgress() {
  try {
    localStorage.removeItem(AUTOSAVE_KEY);
    console.log('Deck progress cleared');
  } catch (e) {
    console.error('Failed to clear deck progress:', e);
  }
}


// Initialize viewability to Public by default for new decks
if (!window.editDeckData && $('#deck-builder').length > 0) {
  $('#deck-viewability-checkbox').prop('checked', true);
  $('#deck-viewability-label').text('Public');
  
  setTimeout(function() {
    loadDeckProgress();
  }, 100);
}
  // deck-builder logic
  var typeCopyLimit    = { 'unit':3,'gear':3,'spell':3, 'signature-spell':3, 'signature-unit':3, 'champion':3,'battlefield':1 },
      maxDeckSize      = 100,
      displayDeckLimit = 56;

  function updateDeckCounter(){
    var total=0;
    $('#selected-cards .deck-card').each(function(){
      total += parseInt($(this).data('quantity'))||0;
    });
    $('#deck-counter')
      .text('Cards: '+total+'/'+displayDeckLimit)
      .css('color', total>displayDeckLimit ? 'red' : '' );
  }
function updateSectionCounters(){
  $('.deck-section').each(function(){
    var cnt=0;
    $(this).find('.deck-card').each(function(){ cnt+=parseInt($(this).data('quantity'))||0; });
    var $t=$(this).find('.deck-section-title');
    if(!$t.data('base-title')) $t.data('base-title',$t.text());
    $t.text($t.data('base-title')+' ('+cnt+')');
    
    // Turn red if Main Deck section exceeds 40 cards
    if($(this).attr('id') === 'main-section') {
      $t.css('color', cnt > 40 ? '#ff3333' : '');
    }
  });
}
function syncAvailableQuantities(){
  $('.builder-card').each(function(){
    var code = $(this).data('card-id');

    // how many in main deck?
    var qtyDeck = parseInt(
      $('#selected-cards .deck-card[data-card-id="'+code+'"]')
        .data('quantity')
    ) || 0;

    // how many in sideboard?
    var qtySide = parseInt(
      $('#sideboard-cards .deck-card[data-card-id="'+code+'"]')
        .data('quantity')
    ) || 0;

    // total copies across both
    var total = qtyDeck + qtySide;

    $(this).find('.builder-card-quantity').text(total);
  });
}
  function getSectionContainer(type){
    type = type.toLowerCase();
    if(type==='legend')       return $('#legend-section .deck-section-cards');
    if(type==='battlefield')  return $('#battlefield-section .deck-section-cards');
    if(type==='rune')         return $('#rune-section .deck-section-cards');
    return $('#main-section .deck-section-cards');
  }
  function sortDeckSection($sec){
    var cards = $sec.find('.deck-card').detach().get();
    cards.sort(function(a,b){
      return (parseInt($(a).data('cost'))||0) - (parseInt($(b).data('cost'))||0);
    });
    $sec.append(cards);
  }

function addCardToDeck($card) {
  // ——— Determine mode & containers ———
var inSideboardMode = $('.deck-info-tab.active').data('target') === 'sideboard-view',
     containerSel    = inSideboardMode ? '#sideboard-cards' : '#selected-cards',
     sectionContainer = inSideboardMode
                           ? $('#sideboard-cards .deck-section-cards')
                           : getSectionContainer(($card.data('type')||'').toLowerCase());
	
// Prevent Legend, Battlefield, Rune in sideboard
if (inSideboardMode && ['legend','battlefield','rune'].includes(($card.data('type') || '').toLowerCase())) {
  $('<div class="deck-error-notification">You cannot add Legend, Battlefield, or Rune cards to the sideboard.</div>')
    .appendTo('body')
    .delay(2000)
    .fadeOut(500, function(){ $(this).remove(); });
  return;
}

	
  // ——— Gather metadata ———
  var cardId       = $card.data('card-id'),
      type         = ($card.data('type') || '').toLowerCase(),
	  typeName     = $card.data('type-name') || $card.find('img').attr('data-type-name') || type,
      legendColors = ($card.data('color') || '').trim().split(/\s+/),
      rawAlt       = $card.data('altCode') || '',
      altCode      = rawAlt.toUpperCase(),
      // copies already present
      qtyDeck      = parseInt($('#selected-cards .deck-card[data-card-id="'+cardId+'"]').data('quantity')) || 0,
      qtySide      = parseInt($('#sideboard-cards .deck-card[data-card-id="'+cardId+'"]').data('quantity'))   || 0,
      totalCopies  = qtyDeck + qtySide,
      limit        = typeCopyLimit[type] || Infinity;

  // ——— Helper to count total cards of a given type across both deck & sideboard ———
  function countType(t) {
    var sum = 0;
    $('#selected-cards .deck-card, #sideboard-cards .deck-card').each(function(){
      if ($(this).data('type') === t) {
        sum += parseInt($(this).data('quantity')) || 0;
      }
    });
    return sum;
  }

  // ——— Enforce deck/sideboard size ———
  var maxCards     = inSideboardMode ? 8 : maxDeckSize,
      currentCount = 0;
  $(containerSel + ' .deck-card').each(function(){
    currentCount += parseInt($(this).data('quantity')) || 0;
  });
  if (!noRestriction && currentCount >= maxCards) {
    $('<div class="deck-error-notification">' +
      (inSideboardMode
        ? 'You can only have up to 8 cards in your sideboard.'
        : 'You can only have ' + maxDeckSize + ' cards in your deck.') +
      '</div>')
    .appendTo('body')
    .delay(2000)
    .fadeOut(500, function(){ $(this).remove(); });
    return;
  }

  // ——— Legend logic (deck only) ———
if (!inSideboardMode && type === 'legend') {
    // remove old Legend
    $('#selected-cards .deck-card').filter(function() {
      return $(this).data('type') === 'legend';
    }).remove();
    // switch to deck tab
    $('.deck-info-tab').removeClass('active');
    $('.deck-info-tab[data-target="deck-view"]').addClass('active');
    $('#deck-view, #sideboard-view').hide();
    $('#deck-view').show();
    // switch available to Main + apply color picks
    $('#available-tabs button').removeClass('active');
    $('#available-tabs button[data-cat="Main"]').addClass('active');
    $('#card-filter-colors input.card-color-checkbox').prop('checked', false);
    legendColors.forEach(function(col) {
      $('#card-filter-colors input.card-color-checkbox[value="' + col + '"]')
        .prop('checked', true);
    });
    
    // Store the legend's subtype for Signature filtering
    window.currentLegendSubtype = $card.data('sub-type') || '';
    
    loadCategory('Main', perPage);
  }

  // ——— Battlefield & Rune global limits ———
  if (!noRestriction) {
    if (type === 'battlefield' && countType('battlefield') >= 3) {
      $('<div class="deck-error-notification">You can only have up to 3 Battlefield cards total.</div>')
        .appendTo('body').delay(2000).fadeOut(500, function(){ $(this).remove(); });
      return;
    }
    if (type === 'rune' && countType('rune') >= 12) {
      $('<div class="deck-error-notification">You can only have up to 12 Rune cards total.</div>')
        .appendTo('body').delay(2000).fadeOut(500, function(){ $(this).remove(); });
      return;
    }
  }

  // ——— Per‑type copy limit across both ———
if (!noRestriction && type !== 'legend' && totalCopies >= limit) {
  var pretty = typeName,  // Use typeName instead of manipulating type
      msg    = limit === 1
               ? "You can only have 1 copy of " + pretty + " cards total."
               : "You can only have up to " + limit + " copies of " + pretty + " cards total.";
  $('<div class="deck-error-notification">' + msg + '</div>')
    .appendTo('body')
    .delay(2000)
    .fadeOut(500, function(){ $(this).remove(); });
  return;
}

  // ——— Gather display data ———
  var name   = $card.data('name'),
      color  = $card.data('color'),
      cost   = $card.data('cost'),
      imgUrl = $card.find('img').attr('src'),
      might  = $card.data('might'),
      effect = $card.data('effect'),
      tags   = $card.data('sub-type'),
      link   = $card.data('card-link'),
      set    = $card.data('set'),
      rarity = $card.data('rarity'),
      power  = $card.data('power');

  // ——— Find existing in this container ———
  var $exist = $(containerSel + ' .deck-card[data-card-id="' + cardId + '"]'),
      qty    = $exist.length ? parseInt($exist.data('quantity')) : 0;

  // ——— Add or increment ———
  if ($exist.length) {
    qty++;
    $exist.data('quantity', qty)
          .find('.builder-card-quantity').text(qty);
  } else {
    var $new = $('<div/>', {
      class: 'deck-card',
      'data-card-id':   cardId,
      'data-type':      type,
      'data-cost':      cost,
      'data-color':     color,
      'data-alt-code':  altCode,
      'data-card-link': link,
      'data-might':     might,
      'data-effect':    effect,
      'data-sub-type':  tags,
      'data-quantity':  1,
      style:            'background:' + getBackgroundStyle(color)
    }).append(
      $('<div/>',{class:'deck-card-content'}).append(
        $('<div/>',{class:'card-info'}).append(
          $('<span/>',{class:'card-cost',text:cost}),
          $('<span/>',{class:'card-name',text:name}),
          $('<span/>',{class:'card-type',text:typeName})
        ),
        $('<div/>',{class:'quantity-controls'}).append(
          $('<img/>',{class:'deck-card-thumb-inline',src:imgUrl,alt:name,title:name}),
          '<button class="minus">-</button>',
          $('<span/>',{class:'builder-card-quantity',text:1}),
          '<button class="plus">+</button>'
        )
      ),
      $('<div/>',{class:'card-hover'}).append(
        $('<img/>', {
          src:            imgUrl,
          alt:            name,
          'data-card-id':   cardId,
          'data-card-name': name,
          'data-cost':      cost,
          'data-type':      type,
		  'data-type-name': typeName,
          'data-color':     color,
          'data-card-link': link,
          'data-might':     might,
          'data-effect':    effect,
          'data-sub-type':  tags,
          'data-set':       set,
          'data-rarity':    rarity,
          'data-power':     power
        })
      )
    );
    sectionContainer.append($new);
    if (!inSideboardMode) sortDeckSection(sectionContainer);
  }

  // ——— Update counters & sync UI ———
  updateDeckCounter();
  updateSectionCounters();
  syncAvailableQuantities();
updateQuantityControlsCollectionState();

  // ——— Sync hidden sideboard field ———
  if (inSideboardMode) {
    var lines = [];
    $('#sideboard-cards .deck-card').each(function(){
      lines.push($(this).data('quantity') + 'x' + $(this).data('card-id'));
    });
    $('#sideboard-ids').val(lines.join('\n'));
  }
  triggerAutoSave();
}



  
  


  // ————— EDIT MODE —————
  // ————— EDIT MODE (FAST) —————

if ( window.editDeckData ) {
  // 0) handle “no restriction” toggle
  if ( window.editDeckData.no_restriction ) {
    noRestriction = true;
    $('#no-restriction-btn')
      .addClass('active')
      .attr('aria-pressed', 'true');
  }

  const $grid    = $('#available-cards');
  const perPage  = parseInt(deck_ajax.per_page, 10) || 40;

  // 1) show deck‐editor spinner & skeletons
  $('#deck-loading-overlay').show();
  $grid.addClass('loading').empty();
  for ( let i = 0; i < perPage; i++ ) {
    $grid.append('<div class="builder-card loading"></div>');
  }

  // 2) parse main‐deck lines → codeQty map
  const rawLines = (window.editDeckData.deck_codes_raw || '')
    .trim()
    .split(/\r?\n/)
    .map(l => l.trim())
    .filter(Boolean);
  const codeQty = {};
  rawLines.forEach(line => {
    const m = line.match(/^(\d*)x?(.+)$/i);
    if (!m) return;
    const qty  = parseInt(m[1], 10) || 1;
    const code = m[2].trim().toLowerCase();
    codeQty[code] = (codeQty[code] || 0) + qty;
  });

  // 3) parse sideboard lines → sideQty map
  const rawSideLines = (window.editDeckData.sideboard_codes_raw || '')
    .trim()
    .split(/\r?\n/)
    .map(l => l.trim())
    .filter(Boolean);
  const sideQty = {};
  rawSideLines.forEach(line => {
    const m = line.match(/^(\d*)x?(.+)$/i);
    if (!m) return;
    const qty  = parseInt(m[1], 10) || 1;
    const code = m[2].trim().toLowerCase();
    sideQty[code] = (sideQty[code] || 0) + qty;
  });

  // 4) populate deck name & description
  $('#deck-name').val(window.editDeckData.deck_name);
  $('#deck-description').val(window.editDeckData.deck_desc);
  $('#deck-youtube-link').val(window.editDeckData.youtube_link || '');

// Load existing deck tags if available
if (window.editDeckData.deck_tags && window.editDeckData.deck_tags.length > 0) {
  // Clear all checkboxes first
  $('#deck-tags-container input.deck-tag-checkbox').prop('checked', false);
  
  // Check the tags that are assigned to this deck
  window.editDeckData.deck_tags.forEach(function(tagSlug) {
    $('#deck-tags-container input.deck-tag-checkbox[value="' + tagSlug + '"]').prop('checked', true);
  });
}







	
  // 5) combine both code lists for a single AJAX fetch
  const deckCodes = Object.keys(codeQty);
  const sideCodes = Object.keys(sideQty);
  const allCodes  = Array.from(new Set([ ...deckCodes, ...sideCodes ]));

  if ( allCodes.length ) {
    $.post(deck_ajax.ajax_url, {
      action:      'load_more_cards',
      security:    deck_ajax.load_more_nonce,
      post_type:   $('#card-post-type').val(),
      page:        1,
      per_page:    allCodes.length,
      filters:     { codes: allCodes },
      use_card_id: 1
    })
    .done(resp => {
      if ( resp.success ) {
        const $tmp = $('<div>').html(resp.data.html);

        // 6) add main‐deck cards
        deckCodes.forEach(code => {
          const $cardEl = $tmp.find('.builder-card')
            .filter((_,el) => (el.dataset.cardId||'').toLowerCase() === code)
            .first();
          if ( !$cardEl.length ) return;
          for ( let i = 0; i < codeQty[code]; i++ ) {
            addCardToDeck($cardEl);
          }
        });
        syncAvailableQuantities();

        // 7) switch UI into Sideboard mode
        $('.deck-info-tab').removeClass('active');
        $('.deck-info-tab[data-target="sideboard-view"]').addClass('active');
        $('#deck-view, #sideboard-view').hide();
        $('#sideboard-view').show();

        // 8) add sideboard cards
        sideCodes.forEach(code => {
          const $cardEl = $tmp.find('.builder-card')
            .filter((_,el) => (el.dataset.cardId||'').toLowerCase() === code)
            .first();
          if ( !$cardEl.length ) return;
          for ( let i = 0; i < sideQty[code]; i++ ) {
            addCardToDeck($cardEl);
          }
        });
        syncAvailableQuantities();

        // 9) switch back to Deck view
        $('.deck-info-tab').removeClass('active');
        $('.deck-info-tab[data-target="deck-view"]').addClass('active');
        $('#sideboard-view').hide();
        $('#deck-view').show();
      } else {
        console.warn('Edit-mode load failed:', resp.data && resp.data.message);
      }
    })
    .fail(() => {
      console.error('AJAX error in edit-mode load');
    })
    .always(() => {
      // 10) hide spinner, remove skeletons, then reapply filters & tabs
      $('#deck-loading-overlay').hide();
      $grid.removeClass('loading');
      filterCards();
      $('#available-tabs button.active').trigger('click');
      syncAvailableQuantities();
    });

  } else {
    // no codes at all: just clear overlay and show empty grid
    $('#deck-loading-overlay').hide();
    $grid.removeClass('loading');
    filterCards();
    $('#available-tabs button.active').trigger('click');
  }
}


// Deck tags selection limit (max 4)
$(document).on('change', '#deck-tags-container input.deck-tag-checkbox', function() {
  var $checkbox = $(this);
  var $container = $('#deck-tags-container');
  var checkedCount = $container.find('input.deck-tag-checkbox:checked').length;
  var maxTags = 4;
  
  if (checkedCount > maxTags) {
    // Prevent checking this box
    $checkbox.prop('checked', false);
    
    // Show error notification
    $('<div class="deck-error-notification">You can only select up to ' + maxTags + ' deck tags.</div>')
      .appendTo('body')
      .delay(2000)
      .fadeOut(500, function() { 
        $(this).remove(); 
      });
  } else {
    // Update visual state for remaining checkboxes
    var remainingCheckboxes = $container.find('input.deck-tag-checkbox:not(:checked)');
    
    if (checkedCount === maxTags) {
      // Disable remaining unchecked boxes
      remainingCheckboxes.prop('disabled', true);
      remainingCheckboxes.closest('.deck-tag-pill').addClass('disabled');
    } else {
      // Re-enable all unchecked boxes
      remainingCheckboxes.prop('disabled', false);
      remainingCheckboxes.closest('.deck-tag-pill').removeClass('disabled');
    }
  }
});

  // ——— UI Hooks ———

  // click to add
  $(document).on('click','.builder-card',function(){ addCardToDeck($(this)); });

  // plus/minus on available
  $(document).on('click','.builder-card .quantity-controls.for-available .plus', function(e){
    e.stopPropagation();
    addCardToDeck($(this).closest('.builder-card'));
  });
$(document).on('click', '.builder-card .quantity-controls.for-available .minus', function(e){
  e.stopPropagation();
  var code    = $(this).closest('.builder-card').data('card-id'),
      // detect which tab is active
      inSide  = $('.deck-info-tab.active').data('target') === 'sideboard-view',
      container = inSide ? '#sideboard-cards' : '#selected-cards',
      // find the corresponding deck‑card in the right container
      $dc     = $(container + ' .deck-card[data-card-id="' + code + '"]');
  if ($dc.length) {
    // trigger that container’s minus button
    $dc.find('.minus').click();
  }
});

  // hover popup positioning
$('#selected-cards, #sideboard-cards')
  .on('mouseenter', '.deck-card-thumb-inline', function(e) {
    var $thumb = $(this),
        $card  = $thumb.closest('.deck-card'),
        $hover = $card.find('.card-hover').detach(),  // pull it out
        rect   = $thumb[0].getBoundingClientRect();

    // append to body and prep for fixed positioning
    $hover.css({
      position:   'fixed',
      display:    'block',
      visibility: 'hidden',
      zIndex:     9999
    }).appendTo(document.body);

    // measure now that it's in body
    var h = $hover.outerHeight(),
        w = $hover.outerWidth(),
        spaceBelow = window.innerHeight - rect.bottom - 8;

    // decide whether to flip above or below
    var top = spaceBelow < h
              ? rect.top - h - 4    // show above
              : rect.bottom + 4;    // show below

    // center horizontally over the thumb
    var left = rect.left + (rect.width / 2) - (w / 2);

    // position and reveal
    $hover.css({
      top:        top + 'px',
      left:       left + 'px',
      visibility: 'visible'
    });
  })
  .on('mouseleave', '.deck-card-thumb-inline', function(e) {
    var $thumb = $(this),
        $card  = $thumb.closest('.deck-card'),
        $hover = $('body > .card-hover'); // it's now a direct child of <body>

    // hide and re‐attach to its original card for click‑popup logic
    $hover.hide().appendTo($card);
  })
  // clicking the small thumbnail still opens the full popup
  .on('click', '.deck-card-thumb-inline', function(e) {
    e.stopPropagation();
    var $card    = $(this).closest('.deck-card'),
        popupImg = $card.find('.card-hover img')[0];
    if (popupImg) deckBuilderOpenPopup(popupImg, true);
  });

// ——— plus in deck & sideboard panels ———
$('#selected-cards, #sideboard-cards').on('click', '.plus', function(e){
  e.stopPropagation();
  var $card   = $(this).closest('.deck-card'),
      cardId  = $card.data('card-id'),
      type    = ($card.data('type')||'').toLowerCase(),
      inSide  = $card.closest('#sideboard-cards').length > 0;

  // legends stay at 1 and are handled via builder‑card clicks
  if (type === 'legend') return;

  // count existing copies across both containers
  var qtyDeck = parseInt($('#selected-cards .deck-card[data-card-id="'+cardId+'"]').data('quantity')) || 0,
      qtySide = parseInt($('#sideboard-cards .deck-card[data-card-id="'+cardId+'"]').data('quantity'))   || 0,
      total   = qtyDeck + qtySide,
      limit   = typeCopyLimit[type] || Infinity;

  if (!noRestriction && total >= limit) {
    var pretty = type.charAt(0).toUpperCase() + type.slice(1),
        msg    = limit === 1
                 ? 'You can only have 1 copy of ' + pretty + ' cards total.'
                 : 'You can only have up to ' + limit + ' copies of ' + pretty + ' cards total.';
    $('<div class="deck-error-notification">'+msg+'</div>')
      .appendTo('body')
      .delay(2000).fadeOut(500, function(){ $(this).remove(); });
    return;
  }

  // enforce deck/sideboard size
  var maxCount = inSide ? 8 : maxDeckSize,
      current  = 0,
      container = inSide ? '#sideboard-cards' : '#selected-cards';
  $(container+' .deck-card').each(function(){
    current += parseInt($(this).data('quantity'))||0;
  });
  if (!noRestriction && current >= maxCount) {
    $('<div class="deck-error-notification">'+
      (inSide
        ? 'You can only have up to 8 cards in your sideboard.'
        : 'You can only have ' + maxDeckSize + ' cards in your deck.')+
      '</div>')
      .appendTo('body')
      .delay(2000).fadeOut(500, function(){ $(this).remove(); });
    return;
  }

  // increment quantity
  var newQty = (inSide ? qtySide : qtyDeck) + 1;
  $card
    .data('quantity', newQty)
    .find('.builder-card-quantity').text(newQty);

  // refresh counters & sync
  updateDeckCounter();
  updateSectionCounters();
  syncAvailableQuantities();
updateQuantityControlsCollectionState();

  // if sideboard, update the hidden field
  if (inSide) {
    var lines = [];
    $('#sideboard-cards .deck-card').each(function(){
      lines.push($(this).data('quantity') + 'x' + $(this).data('card-id'));
    });
    $('#sideboard-ids').val(lines.join('\n'));
  }
  triggerAutoSave();
});


// ——— minus in deck & sideboard panels ———
$('#selected-cards, #sideboard-cards').on('click', '.minus', function(e){
  e.stopPropagation();

  // Grab reference to the card element & whether it's in sideboard
  var $card    = $(this).closest('.deck-card'),
      inSide   = $card.closest('#sideboard-cards').length > 0,
      qty      = parseInt($card.data('quantity'), 10) || 1;

  // decrement or remove
  if (--qty < 1) {
    $card.remove();
  } else {
    $card
      .data('quantity', qty)
      .find('.builder-card-quantity').text(qty);
  }

  // update counters
  updateDeckCounter();
  updateSectionCounters();
  syncAvailableQuantities();
updateQuantityControlsCollectionState();
  // if we were in the sideboard, *always* sync the hidden field
  if (inSide) {
    var lines = [];
    $('#sideboard-cards .deck-card').each(function(){
      lines.push($(this).data('quantity') + 'x' + $(this).data('card-id'));
    });
    $('#sideboard-ids').val(lines.join('\n'));
  }
  var totalCards = $('#selected-cards .deck-card').length + $('#sideboard-cards .deck-card').length;
  if (totalCards === 0) {
    clearDeckProgress();
  } else {
    triggerAutoSave();
  }
});


// ——— click deck‑card for popup in deck & sideboard ———
$('#selected-cards, #sideboard-cards').on('click', '.deck-card', function(e){
  if ($(e.target).closest('.quantity-controls').length) return;
  var img = $(this).find('.card-hover img')[0];
  if (img) deckBuilderOpenPopup(img);
});


  // Save deck (AJAX)
// Save deck - show popup first
$('#save-deck').on('click', function(){
  if(deck_ajax.logged_in!=1){
    // Redirect to /login page
    window.location.href = '/login';
    return;
  }
  
  if(!$('#legend-section .deck-card').length){
    $('<div class="deck-error-notification">Your deck must include a Legend card!</div>')
      .appendTo('body').fadeOut(3000,function(){ $(this).remove(); });
    return;
  }
  
  // Show the save popup
  $('#save-deck-popup').fadeIn(200);
});

// Cancel save
$('#save-deck-cancel, #save-deck-popup .deck-builder-popup-close').on('click', function(){
  $('#save-deck-popup').fadeOut(200);
});

// Click outside to close
$('#save-deck-popup').on('click', function(e){
  if(e.target === this) {
    $('#save-deck-popup').fadeOut(200);
  }
});

// Confirm save
$('#save-deck-confirm').on('click',function(){
  var $btn=$(this);
  
  // Validation checks
  var total = 0;
  $('#selected-cards .deck-card').each(function(){
    total += parseInt($(this).data('quantity')) || 0;
  });
  if ( ! noRestriction && total < displayDeckLimit ) {
    $('<div class="deck-error-notification">Your deck must have at least '
        + displayDeckLimit + ' cards.</div>')
      .appendTo('body')
      .fadeOut(3000, function(){ $(this).remove(); });
    return;
  }
  
  var sideCount = 0;
  $('#sideboard-cards .deck-card').each(function(){
    sideCount += parseInt($(this).data('quantity')) || 0;
  });
  if ( ! noRestriction && sideCount !== 0 && sideCount !== 8 ) {
    $('<div class="deck-error-notification">Your sideboard must have either 0 or 8 cards.</div>')
      .appendTo('body')
      .delay(2000)
      .fadeOut(500, function(){ $(this).remove(); });
    return;
  }
  
  $btn.prop('disabled',true);
  var deckId    = $('#edit-deck-id').val()||0,
      deckName  = $('#deck-name').val(),
      deckDesc  = $('#deck-description').val(),
      cardIds   = [],
      postType  = $('#card-post-type').val();
	  
	  
	var yt = ($('#deck-youtube-link').val() || '').trim();
if (yt && !isValidYouTubeVideoURL(yt)) {
  $('<div class="deck-error-notification">Please enter a valid YouTube video link.</div>')
    .appendTo('body').delay(2500).fadeOut(500, function(){ $(this).remove(); });
  $btn.prop('disabled', false);   // <— add this
  return; // stop save
}  
	  
	  
  $('#selected-cards .deck-card').each(function(){
    cardIds.push((($(this).data('quantity')||1)+'x'+$(this).data('card-id')));
  });
  
  var viewability = $('#deck-viewability-label').text();
  
  // Collect selected deck tags
var selectedTags = [];
$('#deck-tags-container input.deck-tag-checkbox:checked').each(function(){
  selectedTags.push($(this).val());
});
  
  
  $.post(deck_ajax.ajax_url,{
    action:           'save_deck',
    deck_id:          deckId,
    deck_name:        deckName,
    deck_description: deckDesc,
    card_ids:         cardIds,
    sideboard_ids:    $('#sideboard-ids').val(),
    card_post_type:   postType,
    no_restriction: noRestriction ? 1 : 0,
	viewability:      viewability,
	deck_tags:        selectedTags,
	youtube_link:     yt,
	security:         deck_ajax.nonce
  })
  .done(function(resp){
    if ( resp.success ) {
      if ( resp.data.deck_id ) {
        $('#edit-deck-id').val(resp.data.deck_id);
      }
	  clearDeckProgress();
	  
      $('#save-deck-popup').fadeOut(200);
		$('<div class="deck-saved-notification">' + resp.data.message + '</div>')
		  .appendTo('body')
		  .delay(300)
		  .fadeOut(300, function(){ 
			$(this).remove();
			if (resp.data.link) {
			  window.location.href = resp.data.link;
			}
		  });
    } else {
      $('<div class="deck-error-notification">' + resp.data.message + '</div>')
        .appendTo('body')
        .delay(2000)
        .fadeOut(500, function(){ $(this).remove(); });
    }
  })
  .fail(function(xhr){
    var msg = xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message;
    if ( xhr.status === 429 && msg ) {
      $('<div class="deck-throttle-notification">' + msg + '</div>')
        .appendTo('body')
        .delay(2000)
        .fadeOut(500, function(){ $(this).remove(); });
    } else if ( msg ) {
      $('<div class="deck-error-notification">' + msg + '</div>')
        .appendTo('body')
        .delay(2000)
        .fadeOut(500, function(){ $(this).remove(); });
    } else {
      $('<div class="deck-error-notification">Unexpected error, please try again.</div>')
        .appendTo('body')
        .delay(2000)
        .fadeOut(500, function(){ $(this).remove(); });
    }
  })
  .always(function(){ $btn.prop('disabled',false); });
});

  // Import popup handlers + AJAX import (same as before)
  $('#import-deck-button').on('click', ()=>$('#import-deck-popup').fadeIn(200));
  $('#import-deck-cancel').on('click', ()=>$('#import-deck-popup').fadeOut(200));
  $('#import-deck-popup').on('click', e=>{ if(e.target===e.currentTarget) $('#import-deck-popup').fadeOut(200); });

$('#import-deck-submit').off('click').on('click', function (e) {
  e.preventDefault();
  $('#import-deck-popup').fadeOut();
  $('#deck-loading-overlay').show();

  // always import main deck first—even if Sideboard is currently active
  $('.deck-info-tab').removeClass('active');
  $('.deck-info-tab[data-target="deck-view"]').addClass('active');
  $('#sideboard-view').hide();
  $('#deck-view').show();

  // Clear everything
  $('.deck-section-cards').empty();
  $('#sideboard-ids').val(''); // Clear sideboard field
  updateDeckCounter();
  updateSectionCounters();

  var raw = $('#import-deck-text').val().trim();
  var deckParts = [];
  var sideParts = [];
  var isDeckCode = false;

  // Detect Deck Code: must be only base32 characters (A-Z2-7), optionally with |
  const normalized = normalizeDeckCodeInput(raw.replace(/\|/g, '')); // Remove | for base32 check
  if (isDeckCodeCandidate(normalized) || raw.includes('|')) {
    const decoded = decodeDeckCode(raw); // Pass original raw with | intact
    if (decoded) {
      isDeckCode = true;
      // Use the separated arrays if available, otherwise fall back to combined
      if (decoded.mainExpanded && decoded.sideExpanded) {
        deckParts = decoded.mainExpanded;
        sideParts = decoded.sideExpanded;
      } else {
        // Fallback for backwards compatibility
        deckParts = decoded.entriesExpanded.slice(0, 56);
        sideParts = decoded.entriesExpanded.slice(56);
      }
      console.log('Decoded deck parts:', deckParts.length, 'sideboard parts:', sideParts.length);
    }
  }

  if (!isDeckCode) {
    // fallback to TTS-style import (space-separated alt codes)
    var parts = raw.split(/\s+/).filter(Boolean);
    deckParts = parts.slice(0, 56);
    sideParts = parts.slice(56);
  }

  // Build lookup codes for AJAX (deduped, lowercase)
  const lookupCodes = Array.from(
    new Set([...deckParts, ...sideParts].map(c => c.toLowerCase()))
  );

  if (!lookupCodes.length) {
    $('#deck-loading-overlay').hide();
    console.log('No valid codes found to import');
    return;
  }

  // Prepare which attribute to match: deck code uses card-id, TTS uses alt-id
  const matchAttr = isDeckCode ? 'cardId' : 'altCode';

  // Perform AJAX fetch of all relevant cards
  const ajaxPayload = {
    action: 'load_more_cards',
    security: deck_ajax.load_more_nonce,
    post_type: $('#card-post-type').val(),
    page: 1,
    per_page: lookupCodes.length,
    filters: { codes: lookupCodes },
  };
  if (isDeckCode) {
    ajaxPayload.use_card_id = 1; // fetch by card-id taxonomy
  }

  $.post(deck_ajax.ajax_url, ajaxPayload)
    .done(function (resp) {
      if (!resp.success) {
        console.error('Import failed:', resp.data && resp.data.message);
        return;
      }
      var $temp = $('<div>').html(resp.data.html);

      // 4) Add main deck cards
      console.log('Adding', deckParts.length, 'main deck cards');
      deckParts.forEach(function (code) {
        code = code.toLowerCase();
        var $cardEl = $temp
          .find('.builder-card')
          .filter(function () {
            return ((this.dataset[matchAttr] || '').toLowerCase() === code);
          })
          .first();
        if ($cardEl.length) {
          addCardToDeck($cardEl);
        } else {
          console.warn('Card not found for main deck code:', code);
        }
      });

      // 5) Switch into sideboard mode if we have sideboard cards
      if (sideParts.length > 0) {
        console.log('Adding', sideParts.length, 'sideboard cards');
        $('.deck-info-tab').removeClass('active');
        $('.deck-info-tab[data-target="sideboard-view"]').addClass('active');
        $('#deck-view, #sideboard-view').hide();
        $('#sideboard-view').show();

        // 6) Add sideboard cards
        sideParts.forEach(function (code) {
          code = code.toLowerCase();
          var $cardEl = $temp
            .find('.builder-card')
            .filter(function () {
              return ((this.dataset[matchAttr] || '').toLowerCase() === code);
            })
            .first();
          if ($cardEl.length) {
            addCardToDeck($cardEl);
          } else {
            console.warn('Card not found for sideboard code:', code);
          }
        });

        // 7) Switch back to Deck view
        $('.deck-info-tab').removeClass('active');
        $('.deck-info-tab[data-target="deck-view"]').addClass('active');
        $('#sideboard-view').hide();
        $('#deck-view').show();
      }

      syncAvailableQuantities();
      console.log('Import completed successfully');
    })
    .fail(function () {
      console.error('AJAX import error');
    })
    .always(function () {
      $('#deck-loading-overlay').hide();
      $('#import-deck-text').val('');
    });
});


  // Clear & New deck
  $('#clear-deck-button').on('click', function(){
    $('.deck-section-cards').empty();
	$('#sideboard-cards .deck-section-cards').empty();
    updateDeckCounter(); updateSectionCounters(); syncAvailableQuantities(); clearDeckProgress();
  });
  $('#new-deck-button').on('click', function(){
    $('#edit-deck-id').val('');
    $('#deck-name,#deck-description').val('');
    $('.deck-section-cards').empty();
    updateDeckCounter(); updateSectionCounters(); syncAvailableQuantities();
    $('#save-deck').prop('disabled',false);
	clearDeckProgress();
  });


// ─── Available‐cards Category Tabs ───
$('#available-tabs').on('click', 'button', function(){
  // if this tab is already active, do nothing
  if ($(this).hasClass('active')) {
    return;
  }

  var cat = $(this).data('cat');

  // 1) Clear any manual type filters
  $('#card-filter-types input[type=checkbox]').prop('checked', false);

  // 2) Visually switch the tab
  $('#available-tabs button').removeClass('active');
  $(this).addClass('active');

  // 3) Load page 1 for the new tab
  loadCategory(cat, perPage);

  // 4) Reset the scroll so you don’t immediately retrigger
  $('#available-cards').scrollTop(0);
});

// ─── Infinite scroll: load more cards as you scroll down ───
$('#available-cards').on('scroll', debounce(function() {
  var $el       = $(this),
      threshold = 200; // Fixed pixel threshold instead of dynamic

  // Check if we're near the bottom
  var scrollTop = $el.scrollTop();
  var innerHeight = $el.innerHeight();
  var scrollHeight = this.scrollHeight;
  
  // Add visible card count check
  var visibleCards = $('#available-cards .builder-card:visible').length;
  
  if (
    !loading &&
    currentPage <= maxPages &&
    scrollTop + innerHeight + threshold >= scrollHeight
  ) {
    loading = true;

    var filters = getFilters();
    var sortBy  = getSortCriteria();
    var k       = keyFor({
      page: currentPage, per_page: perPage, post_type: $('#card-post-type').val(),
      filters: filters, sort_by: sortBy, sort_order:'ASC'
    });
    var cached = cacheGet(k);

    function appendPayload(payload){
      var showAlt = $('#alt-art-checkbox').is(':checked');

      var $fetched = $(payload.html).filter(function() {
        var $el = $(this);
        var key = showAlt ? ($el.data('altCode') || $el.data('cardId'))
                          : $el.data('cardId');

        var selector = showAlt
          ? '.builder-card[data-alt-code="' + key + '"]'
          : '.builder-card[data-card-id="' + key + '"]';

        return $('#available-cards').find(selector).length === 0;
      });

      $('#available-cards').append($fetched);
      sortAvailableCards(getSortCriteria());
      filterCards();
      syncAvailableQuantities();
      currentPage++;
      
      // IMPORTANT: Check if we need to load more immediately
      // (in case the newly loaded cards are mostly hidden)
      setTimeout(function() {
        var newVisibleCards = $('#available-cards .builder-card:visible').length;
        var stillNearBottom = $el.scrollTop() + $el.innerHeight() + threshold >= $el[0].scrollHeight;
        
        // If we're still at the bottom and didn't add many visible cards, trigger another load
        if (stillNearBottom && newVisibleCards - visibleCards < 10 && currentPage <= maxPages) {
          loading = false;
          $el.trigger('scroll'); // Trigger another load
        } else {
          loading = false;
        }
      }, 100);
    }

    if (cached) {
      appendPayload(cached);
    } else {
      $.post(deck_ajax.ajax_url, {
        action:     'load_more_cards',
        security:   deck_ajax.load_more_nonce,
        page:       currentPage,
        per_page:   perPage,
        post_type:  $('#card-post-type').val(),
        filters:    filters,
        sort_by:    sortBy,
        sort_order: 'ASC'
      })
      .done(function(resp){
        if (resp.success) {
          cacheSet(k, resp.data);
          appendPayload(resp.data);
        } else {
          loading = false;
        }
      })
      .fail(function(){
        loading = false; // Make sure to reset on failure
      });
    }
  }
}, 200));

// — Tab switcher between Deck / Sideboard —
$('.deck-info-tab').on('click', function(){
  $('.deck-info-tab').removeClass('active');
  $(this).addClass('active');
  var tgt = $(this).data('target');
  $('#deck-view, #sideboard-view').hide();
  $('#' + tgt).show();
});

// helper to know if we're in sideboard mode
function inSideboard() {
  return $('.deck-info-tab.active').data('target') === 'sideboard-view';
}

$('#deck-name, #deck-description, #deck-youtube-link').on('input change', debounce(triggerAutoSave, 500));
$('#deck-viewability-checkbox, #no-restriction-btn').on('change click', triggerAutoSave);
$('#deck-tags-container input.deck-tag-checkbox').on('change', triggerAutoSave);

});

// export button in deck builder
jQuery(function($){


function buildTTSCode(){
  const entries = [];

  // main deck entries
  $('#selected-cards .deck-card').each(function(){
    const alt = $(this).data('altCode');   // e.g. "OGN-123-1"
    const qty = parseInt($(this).data('quantity'), 10) || 0;
    for (let i = 0; i < qty; i++) {
      entries.push(alt);
    }
  });

  // sideboard entries (appended after main deck)
  $('#sideboard-cards .deck-card').each(function(){
    const alt = $(this).data('altCode');
    const qty = parseInt($(this).data('quantity'), 10) || 0;
    for (let i = 0; i < qty; i++) {
      entries.push(alt);
    }
  });

  return entries.join(' ');
}







// —— Fixed Deck Code generation maps and helpers ——

// Constants (format 1, version 1)
const DECKCODE_FORMAT = 1;
const DECKCODE_VERSION = 1;
const SET_MAP = { OGN: 0, OGS: 1 }; // extend as needed
const VARIANT_MAP = { "": 0, a: 1, s: 2 };
const BASE32_ALPHABET = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";

// —— Utility helpers ——

// Normalize input: strip whitespace and uppercase
function normalizeDeckCodeInput(code) {
  return (code || "").toString().replace(/\s+/g, "").toUpperCase();
}

// Quick sanity check: is this plausibly a deck code (base32 chars only)?
function isDeckCodeCandidate(str) {
  return /^[A-Z2-7]+$/.test(normalizeDeckCodeInput(str));
}

// —— base32 encode / decode ——

// Base32 encoder for Uint8Array
function base32Encode(byteArray) {
  let result = "";
  let buffer = 0;
  let bitsLeft = 0;
  for (let i = 0; i < byteArray.length; i++) {
    buffer = (buffer << 8) | byteArray[i];
    bitsLeft += 8;
    while (bitsLeft >= 5) {
      bitsLeft -= 5;
      const index = (buffer >> bitsLeft) & 0x1f;
      result += BASE32_ALPHABET[index];
    }
  }
  if (bitsLeft > 0) {
    buffer <<= (5 - bitsLeft);
    result += BASE32_ALPHABET[buffer & 0x1f];
  }
  return result;
}

// Base32 decoder - FIXED VERSION
function base32Decode(input) {
  const normalized = normalizeDeckCodeInput(input);
  const lookup = {};
  for (let i = 0; i < BASE32_ALPHABET.length; i++) {
    lookup[BASE32_ALPHABET[i]] = i;
  }
  let bits = 0;
  let value = 0;
  const output = [];
  for (let i = 0; i < normalized.length; i++) {
    const ch = normalized[i];
    if (typeof lookup[ch] === "undefined") {
      throw new Error("Invalid base32 character: " + ch);
    }
    value = (value << 5) | lookup[ch];
    bits += 5;
    if (bits >= 8) {
      bits -= 8;
      output.push((value >> bits) & 0xff);
    }
  }
  return new Uint8Array(output);
}

// —— Varint (7-bit continuation) - FIXED VERSION ——

// Encoder
function getVarint(value) {
  const bytes = [];
  if (value === 0) return [0];
  while (value !== 0) {
    let byteVal = value & 0x7f;
    value >>>= 7;
    if (value !== 0) byteVal |= 0x80;
    bytes.push(byteVal);
  }
  return bytes;
}

// Decoder (advances ptr.i) - FIXED VERSION
function readVarint(bytes, ptr) {
  let result = 0;
  let shift = 0;
  while (ptr.i < bytes.length) {
    const byte = bytes[ptr.i++];
    result |= (byte & 0x7f) << shift;
    if ((byte & 0x80) === 0) {
      return result;
    }
    shift += 7;
    if (shift >= 32) {
      throw new Error("Varint too long");
    }
  }
  throw new Error("Truncated varint");
}

// —— Card code normalization / grouping ——

// Parse card codes like "OGN-003a", keep "OGN-001" intact, strip extraneous "-1"/"-2"
function parseCardCode(cardCode) {
  let code = (cardCode || "").toString().trim();

  // Only remove trailing "-<number>" if there's an extra segment (e.g., "OGN-123-1" → "OGN-123")
  const parts = code.split("-");
  if (parts.length > 2 && /^\d+$/.test(parts[parts.length - 1])) {
    code = parts.slice(0, -1).join("-");
  }

  const [setRaw = "", restRaw = ""] = code.split("-");
  const set = setRaw.toUpperCase();

  let number = "";
  let variant = "";

  const match = restRaw.match(/^(\d+)([a-zA-Z]?)$/);
  if (match) {
    number = match[1];
    variant = (match[2] || "").toLowerCase();
  } else if (restRaw) {
    number = restRaw;
  }

  return { set, number, variant };
}

// Group cards (all with same count) by set & variant, sort deterministically
// Expecting input: array of objects { cardCode: "OGN-003a", count: N }
function groupBySetAndVariant(cards) {
  const groups = {};
  cards.forEach((card) => {
    if (!card || !card.cardCode) return;
    const parsed = parseCardCode(card.cardCode);
    if (!parsed.number) return; // skip malformed
    const setName = parsed.set;
    const variant = parsed.variant;
    const key = `${setName}-${variant}`;
    if (!groups[key]) {
      groups[key] = {
        set: SET_MAP[setName] !== undefined ? SET_MAP[setName] : 0,
        variant: VARIANT_MAP[variant] !== undefined ? VARIANT_MAP[variant] : 0,
        cardNumbers: [],
      };
    }
    groups[key].cardNumbers.push(parsed.number);
  });

  const groupList = Object.values(groups);
  groupList.sort((a, b) => {
    if (a.set !== b.set) return a.set - b.set;
    if (a.variant !== b.variant) return a.variant - b.variant;
    return 0;
  });

  groupList.forEach((g) => {
    g.cardNumbers.sort((a, b) =>
      a.localeCompare(b, undefined, { numeric: true, sensitivity: "base" })
    );
  });

  return groupList;
}

// —— Deck Code builder / decoder ——

// Updated buildDeckCode function with sideboard support
function buildDeckCode() {
  // Helper function to encode a set of cards
  function encodeCards(cardSelector) {
    const aggregate = {};
    
    $(cardSelector).each(function () {
      const rawCode = ($(this).data("card-id") || "").toString().trim();
      if (!rawCode) return;
      const codeNorm = rawCode.toUpperCase();
      const qty = parseInt($(this).data("quantity"), 10) || 0;
      if (qty <= 0) return;
      aggregate[codeNorm] = (aggregate[codeNorm] || 0) + qty;
    });

    const entries = Object.keys(aggregate)
      .map((k) => ({ cardCode: k, count: aggregate[k] }))
      .filter((e) => e.count > 0);

    const bytes = [];
    // header byte: format/version
    bytes.push((DECKCODE_FORMAT << 4) | DECKCODE_VERSION);

    // counts from 12 down to 1
    for (let count = 12; count >= 1; count--) {
      const cardsWithCount = entries.filter((c) => c.count === count);
      const setVariantGroups = groupBySetAndVariant(cardsWithCount);
      bytes.push(...getVarint(setVariantGroups.length));
      setVariantGroups.forEach((group) => {
        bytes.push(...getVarint(group.cardNumbers.length));
        bytes.push(group.set);
        bytes.push(group.variant);
        group.cardNumbers.forEach((numStr) => {
          const num = parseInt(numStr, 10);
          if (isNaN(num)) return;
          bytes.push(...getVarint(num));
        });
      });
    }

    return base32Encode(new Uint8Array(bytes));
  }

  // Encode main deck
  const mainDeckCode = encodeCards("#selected-cards .deck-card");
  
  // Check if there are any sideboard cards
  const sideboardCards = $("#sideboard-cards .deck-card");
  
  if (sideboardCards.length === 0) {
    // No sideboard, return just the main deck code
    return mainDeckCode;
  } else {
    // Encode sideboard
    const sideboardCode = encodeCards("#sideboard-cards .deck-card");
    
    // Return main deck code + "|" + sideboard code
    return mainDeckCode + "|" + sideboardCode;
  }
}

// Updated decodeDeckCode function with sideboard support
function decodeDeckCode(code) {
  try {
    // Check if there's a sideboard (contains "|")
    const parts = code.split("|");
    const mainDeckCode = parts[0];
    const sideboardCode = parts[1] || null;
    
    // Helper function to decode a single code
    function decodeSingleCode(singleCode) {
      const bytes = base32Decode(singleCode);
      console.log('Decoded bytes length:', bytes.length);
      
      const REVERSE_SET_MAP = Object.fromEntries(
        Object.entries(SET_MAP).map(([k, v]) => [v, k])
      );
      const REVERSE_VARIANT_MAP = Object.fromEntries(
        Object.entries(VARIANT_MAP).map(([k, v]) => [v, k])
      );
      
      const ptr = { i: 0 };
      
      // Read and validate header
      if (ptr.i >= bytes.length) {
        throw new Error("Empty byte array");
      }
      const header = bytes[ptr.i++];
      const format = (header >> 4) & 0x0F;
      const version = header & 0x0F;
      
      console.log('Header - Format:', format, 'Version:', version);

      const aggregate = {};

      // Process counts from 12 down to 1
      for (let count = 12; count >= 1; count--) {
        if (ptr.i >= bytes.length) {
          console.log(`Reached end of bytes at count ${count}`);
          break;
        }
        
        const numGroups = readVarint(bytes, ptr);
        console.log(`Count ${count}: ${numGroups} groups`);
        
        for (let g = 0; g < numGroups; g++) {
          if (ptr.i >= bytes.length) {
            console.log(`Reached end of bytes in group ${g} of count ${count}`);
            break;
          }
          
          const numCardNumbers = readVarint(bytes, ptr);
          console.log(`  Group ${g}: ${numCardNumbers} cards`);
          
          if (ptr.i + 1 >= bytes.length) {
            console.log(`Not enough bytes for set/variant at group ${g}`);
            break;
          }
          
          const setByte = bytes[ptr.i++];
          const variantByte = bytes[ptr.i++];
          const setName = REVERSE_SET_MAP[setByte] || "UNK";
          const variant = REVERSE_VARIANT_MAP[variantByte] || "";
          
          console.log(`    Set: ${setName} (${setByte}), Variant: ${variant} (${variantByte})`);
          
          for (let cn = 0; cn < numCardNumbers; cn++) {
            if (ptr.i >= bytes.length) {
              console.log(`Reached end of bytes reading card number ${cn}`);
              break;
            }
            
            const number = readVarint(bytes, ptr);
            let cardCode = setName ? `${setName}-${number.toString().padStart(3, '0')}` : `${number}`;
            if (variant) {
              cardCode += variant;
            }
            cardCode = cardCode.toLowerCase();
            
            console.log(`      Card: ${cardCode} (count: ${count})`);
            
            aggregate[cardCode] = (aggregate[cardCode] || 0) + count;
          }
        }
      }

      return aggregate;
    }
    
    // Decode main deck
    const mainAggregate = decodeSingleCode(mainDeckCode);
    let sideboardAggregate = {};
    
    // Decode sideboard if present
    if (sideboardCode) {
      sideboardAggregate = decodeSingleCode(sideboardCode);
    }
    
    console.log('Main deck aggregate:', mainAggregate);
    console.log('Sideboard aggregate:', sideboardAggregate);

    // Create expanded list for import (main deck first, then sideboard)
    const mainExpanded = [];
    const sideExpanded = [];
    
    // Add main deck cards
    Object.entries(mainAggregate).forEach(([codeKey, qty]) => {
      for (let i = 0; i < qty; i++) {
        mainExpanded.push(codeKey);
      }
    });
    
    // Add sideboard cards separately
    Object.entries(sideboardAggregate).forEach(([codeKey, qty]) => {
      for (let i = 0; i < qty; i++) {
        sideExpanded.push(codeKey);
      }
    });

    // For backwards compatibility, also create the combined list
    const combinedExpanded = [...mainExpanded, ...sideExpanded];

    console.log('Main deck expanded:', mainExpanded.length, 'cards');
    console.log('Sideboard expanded:', sideExpanded.length, 'cards');
    
    return { 
      aggregate: { ...mainAggregate, ...sideboardAggregate }, // combined for backwards compatibility
      entriesExpanded: combinedExpanded, // combined list for backwards compatibility
      mainDeck: mainAggregate,
      sideboard: sideboardAggregate,
      mainExpanded: mainExpanded,
      sideExpanded: sideExpanded
    };
    
  } catch (e) {
    console.error('Deck code decode error:', e);
    return null;
  }
}


function buildCardNamesCode() {
  const sections = [
    { selector: '#legend-section .deck-card', showTitle: false, addSpaceAfter: true },
    { selector: '#main-section .deck-card', showTitle: false, addSpaceAfter: true }, 
    { selector: '#battlefield-section .deck-card', showTitle: false, addSpaceAfter: true },
    { selector: '#rune-section .deck-card', showTitle: false, addSpaceAfter: true },
    { selector: '#sideboard-cards .deck-card', title: 'Sideboard', showTitle: true, addSpaceAfter: false }
  ];

  let output = [];
  let hasMainDeckCards = false;
  
  sections.forEach(section => {
    const cards = $(section.selector);
    if (cards.length > 0) {
      // Only add section header for sideboard
      if (section.showTitle && section.title) {
        // Add blank line before sideboard if there were main deck cards
        if (hasMainDeckCards) {
          output.push('');
        }
        output.push(section.title + ':');
      }
      
      // Get cards and sort by name
      const cardList = [];
      cards.each(function() {
        // Try multiple ways to get the card name to ensure we get it
        let name = $(this).data('name') || 
                   $(this).attr('data-name') || 
                   $(this).find('.card-name').text() || 
                   'Unknown Card';
        
        const qty = parseInt($(this).data('quantity'), 10) || 1;
        cardList.push({ name: name.trim(), quantity: qty });
      });
      
      // Sort alphabetically by card name
      cardList.sort((a, b) => a.name.localeCompare(b.name));
      
      // Add cards to output
      cardList.forEach(card => {
        output.push(`${card.quantity} ${card.name}`);
      });
      
      // Add space after each section (except sideboard)
      if (section.addSpaceAfter) {
        output.push('');
      }
      
      // Mark that we had main deck cards (for spacing before sideboard)
      if (!section.showTitle) {
        hasMainDeckCards = true;
      }
    }
  });
  
  // Remove any trailing empty line
  if (output[output.length - 1] === '') {
    output.pop();
  }
  
  return output.join('\n');
}


// Expose globally
window.buildDeckCode            = buildDeckCode;
window.decodeDeckCode           = decodeDeckCode;
window.isDeckCodeCandidate      = isDeckCodeCandidate;
window.normalizeDeckCodeInput   = normalizeDeckCodeInput;



function buildPixelCode() {
  var entries = [];
  
  // Main deck entries
  $('#selected-cards .deck-card').each(function() {
    var rawAlt = ($(this).data('altCode') || '').toString().toUpperCase();
    // Normalize variant codes: OGN-001-2 → OGN-001-1
    var alt = rawAlt.replace(/-\d+$/, '-1');
    var qty = parseInt($(this).data('quantity'), 10) || 0;
    
    for (var i = 0; i < qty; i++) {
      entries.push(alt);
    }
  });
  
  /* Sideboard entries (appended after main deck)
  $('#sideboard-cards .deck-card').each(function() {
    var rawAlt = ($(this).data('altCode') || '').toString().toUpperCase();
    var alt = rawAlt.replace(/-\d+$/, '-1');
    var qty = parseInt($(this).data('quantity'), 10) || 0;
    
    for (var i = 0; i < qty; i++) {
      entries.push(alt);
    }
  });
  */
  // Join with '$' and base64-encode
  return btoa(entries.join('$'));
}


// ─── EXPORT MODAL: Tab Switching + Auto-generate + Copy ───
  $('#export-deck-button').on('click', function() {
    // generate both textareas immediately
    $('#export-tts-text').val(buildTTSCode());
    $('#export-deckcode-text').val(buildDeckCode());
	$('#export-pixelborn-text').val(buildPixelCode());
	$('#export-cardnames-text').val(buildCardNamesCode());
    // then show popup
    $('#export-deck-popup')
      .stop(true)
      .css('display','flex')
      .hide()
      .fadeIn(200);
  });

  // TAB SWITCH: regenerate on tab change
  $('.export-modal-tabs li').on('click', function() {
    var tab = $(this).data('tab');
    $('.export-modal-tabs li').removeClass('active');
    $(this).addClass('active');
    $('.tab-pane').addClass('hidden');
    $('#tab-' + tab).removeClass('hidden');

    if (tab === 'tts') {
      $('#export-tts-text').val(buildTTSCode());
    } else {
      $('#export-deckcode-text').val(buildDeckCode());
    }
  });

   // COPY button for card names
  $('#copy-cardnames-btn').on('click', function() {
    var $btn = $(this);
    var txt = $('#export-cardnames-text').val();
    
    if (!txt.trim()) {
      showNotification('No card names to copy!', 'error');
      return;
    }
    
    // Try modern clipboard API first
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(txt).then(function() {
        showCopySuccess($btn, 'Card names copied!');
      }).catch(function(err) {
        console.error('Clipboard API failed:', err);
        fallbackCopy(txt, $btn, 'Card names copied!');
      });
    } else {
      // Fallback for older browsers
      fallbackCopy(txt, $btn, 'Card names copied!');
    }
  });
  
  // COPY button for Pixelborn
$('#copy-pixelborn-btn').on('click', function() {
  var $btn = $(this);
  var txt = $('#export-pixelborn-text').val();
  
  if (!txt.trim()) {
    showNotification('No Pixelborn code to copy!', 'error');
    return;
  }
  
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(txt).then(function() {
      showCopySuccess($btn, 'Pixelborn code copied!');
    }).catch(function(err) {
      console.error('Clipboard API failed:', err);
      fallbackCopy(txt, $btn, 'Pixelborn code copied!');
    });
  } else {
    fallbackCopy(txt, $btn, 'Pixelborn code copied!');
  }
});

  // Update existing copy buttons to use the helper functions
  $('#copy-tts-btn').on('click', function() {
    var $btn = $(this);
    var txt = $('#export-tts-text').val();
    
    if (!txt.trim()) {
      showNotification('No TTS code to copy!', 'error');
      return;
    }
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(txt).then(function() {
        showCopySuccess($btn, 'TTS code copied!');
      }).catch(function(err) {
        console.error('Clipboard API failed:', err);
        fallbackCopy(txt, $btn, 'TTS code copied!');
      });
    } else {
      fallbackCopy(txt, $btn, 'TTS code copied!');
    }
  });

  $('#copy-deckcode-btn').on('click', function() {
    var $btn = $(this);
    var txt = $('#export-deckcode-text').val();
    
    if (!txt.trim()) {
      showNotification('No deck code to copy!', 'error');
      return;
    }
    
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(txt).then(function() {
        showCopySuccess($btn, 'Deck code copied!');
      }).catch(function(err) {
        console.error('Clipboard API failed:', err);
        fallbackCopy(txt, $btn, 'Deck code copied!');
      });
    } else {
      fallbackCopy(txt, $btn, 'Deck code copied!');
    }
  });


  // Fallback copy method for older browsers
  function fallbackCopy(text, $btn, successMessage) {
    // Create a temporary textarea
    var $temp = $('<textarea>')
      .val(text)
      .css({
        position: 'fixed',
        left: '-9999px',
        top: '-9999px',
        opacity: 0
      })
      .appendTo('body');
    
    try {
      // Select and copy
      $temp.select();
      $temp[0].setSelectionRange(0, 99999); // For mobile devices
      
      var successful = document.execCommand('copy');
      if (successful) {
        showCopySuccess($btn, successMessage);
      } else {
        showNotification('Copy failed. Please manually select and copy the text.', 'error');
      }
    } catch (err) {
      console.error('Fallback copy failed:', err);
      showNotification('Copy not supported. Please manually select and copy the text.', 'error');
    } finally {
      $temp.remove();
    }
  }

  // Show copy success with button feedback
function showCopySuccess($btn, message) {
  // Store original button text and icon
  var $buttonText = $btn.find('.button-text');
  var $icon = $btn.find('i');
  var originalText = $buttonText.text();
  
  // Change button text and icon temporarily
  $buttonText.text('Copied!');
  $icon.removeClass('fa-copy').addClass('fa-check');
  $btn.addClass('copied');
  
  // Show notification
  showNotification(message, 'success');
  
  // Reset button after 2 seconds
  setTimeout(function() {
    $buttonText.text(originalText);
    $icon.removeClass('fa-check').addClass('fa-copy');
    $btn.removeClass('copied');
  }, 2000);
}

  // Generic notification function
  function showNotification(message, type) {
    var className = type === 'error' ? 'deck-error-notification' : 'deck-success-notification';
    
    $('<div class="' + className + '">' + message + '</div>')
      .appendTo('body')
      .delay(2000)
      .fadeOut(500, function() { 
        $(this).remove(); 
      });
  }
  
// ============ EXPORT: IMAGE (JPG) - OPTIMIZED ============

const EXPORT_CONFIG = {
  format: 'image/jpeg',
  quality: 0.85,
  scale: 2,
  canvas: { width: 1600, padding: 32, gap: 12 },
  colors: {
    background: '#1b1b1b',
    badge: '#ffa600',
    text: '#ffffff'
  },
  brand: {
    iconUrl: '/wp-content/uploads/Icons/Riftmana-logo.webp',
    text: 'RiftMana.com',
    iconSize: 60,
    gap: 4,
    topMargin: 12
  },
  deckName: {
    height: 80,
    fontSize: 42
  }
};

// -------- Utility Functions --------

function createCanvas(width, height) {
  const canvas = document.getElementById('deck-export-canvas');
  const ctx = canvas.getContext('2d');
  const scale = EXPORT_CONFIG.scale;
  
  canvas.width = Math.round(width * scale);
  canvas.height = Math.round(height * scale);
  ctx.scale(scale, scale);
  
  return { canvas, ctx, width, height };
}

function loadImage(url) {
  return new Promise((resolve) => {
    const img = new Image();
    img.onload = () => resolve({ img, url, loaded: true });
    img.onerror = () => resolve({ img: null, url, loaded: false });
    img.src = url;
  });
}

function drawImageCover(ctx, img, x, y, w, h) {
  if (!img || !img.naturalWidth || !img.naturalHeight) return;
  
  const imgRatio = img.naturalWidth / img.naturalHeight;
  const boxRatio = w / h;
  
  let drawW, drawH, drawX, drawY;
  
  if (imgRatio > boxRatio) {
    drawH = h;
    drawW = h * imgRatio;
    drawX = x - (drawW - w) / 2;
    drawY = y;
  } else {
    drawW = w;
    drawH = w / imgRatio;
    drawX = x;
    drawY = y - (drawH - h) / 2;
  }
  
  ctx.drawImage(img, drawX, drawY, drawW, drawH);
}

function drawCountBadge(ctx, x, y, w, h, count) {
  if (!count || count <= 1) return;
  
  const radius = 28;
  const padding = 4;
  const cx = x + w - radius - padding;
  const cy = y + h - radius - padding;
  
  ctx.save();
  
  // Circle with border
  ctx.beginPath();
  ctx.arc(cx, cy, radius, 0, Math.PI * 2);
  ctx.fillStyle = EXPORT_CONFIG.colors.badge;
  ctx.fill();
  ctx.lineWidth = 3;
  ctx.strokeStyle = '#000';
  ctx.stroke();
  
  // Text
  ctx.fillStyle = '#000';
  ctx.font = 'bold 26px system-ui, sans-serif';
  ctx.textAlign = 'center';
  ctx.textBaseline = 'middle';
  ctx.fillText(String(count), cx, cy);
  
  ctx.restore();
}

function drawSeparator(ctx, x, y, width) {
  ctx.save();
  const yy = Math.round(y) + 0.5;
  
  const gradient = ctx.createLinearGradient(x, yy, x + width, yy);
  gradient.addColorStop(0, 'rgba(255,255,255,0)');
  gradient.addColorStop(0.15, 'rgba(255,255,255,0.45)');
  gradient.addColorStop(0.5, 'rgba(255,255,255,0.9)');
  gradient.addColorStop(0.85, 'rgba(255,255,255,0.45)');
  gradient.addColorStop(1, 'rgba(255,255,255,0)');
  
  ctx.shadowColor = 'rgba(0,0,0,0.25)';
  ctx.shadowBlur = 2;
  ctx.fillStyle = gradient;
  ctx.fillRect(x, yy, width, 2);
  
  ctx.restore();
}

function drawBackground(ctx, width, height) {
  const legendColors = getLegendColors();
  
  if (legendColors.length === 0) {
    ctx.fillStyle = EXPORT_CONFIG.colors.background;
    ctx.fillRect(0, 0, width, height);
  } else if (legendColors.length === 1) {
    ctx.fillStyle = colorMap[legendColors[0]] || EXPORT_CONFIG.colors.background;
    ctx.fillRect(0, 0, width, height);
  } else {
    const gradient = ctx.createLinearGradient(0, 0, width, 0);
    const step = 1 / (legendColors.length - 1);
    legendColors.forEach((color, i) => {
      gradient.addColorStop(i * step, colorMap[color] || EXPORT_CONFIG.colors.background);
    });
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, width, height);
  }
  
  // Vignette
  const vignette = ctx.createRadialGradient(
    width / 2, height / 2, width * 0.25,
    width / 2, height / 2, width * 0.75
  );
  vignette.addColorStop(0, 'rgba(0,0,0,0)');
  vignette.addColorStop(1, 'rgba(0,0,0,0.28)');
  ctx.fillStyle = vignette;
  ctx.fillRect(0, 0, width, height);
}

function getLegendColors() {
  const $legend = $('#legend-section .deck-card').first();
  if (!$legend.length) return [];
  
  const colorStr = ($legend.data('color') || '').toString().trim();
  return colorStr ? colorStr.split(/\s+/).filter(Boolean) : [];
}

// -------- Data Collection --------

function collectDeckData() {
  const data = {
    legend: null,
    battlefields: [],
    runes: [],
    mains: [],
    sideboard: []
  };
  
  const cardMap = new Map();
  
  function addCard(category, $card) {
    const id = ($card.data('card-id') || '').toString();
    const qty = parseInt($card.data('quantity'), 10) || 0;
    const type = ($card.data('type') || '').toLowerCase();
    const cost = parseInt($card.data('cost') || '0', 10) || 0;
    const imgSrc = $card.find('.card-hover img').attr('src');
    
    if (!id || !imgSrc || qty < 1) return;
    
    const key = `${category}-${id}`;
    
    if (cardMap.has(key)) {
      cardMap.get(key).qty += qty;
    } else {
      cardMap.set(key, { id, url: imgSrc, type, cost, qty, category });
    }
  }
  
  // Collect main deck
  $('#legend-section .deck-card').each(function() {
    const $card = $(this);
    const imgSrc = $card.find('.card-hover img').attr('src');
    if (imgSrc) {
      data.legend = { 
        id: $card.data('card-id'), 
        url: imgSrc, 
        qty: 1 
      };
    }
  });
  
  $('#battlefield-section .deck-card').each(function() { addCard('battlefield', $(this)); });
  $('#rune-section .deck-card').each(function() { addCard('rune', $(this)); });
  $('#main-section .deck-card').each(function() { addCard('main', $(this)); });
  $('#sideboard-cards .deck-card').each(function() { addCard('sideboard', $(this)); });
  
  // Convert map to arrays and sort
  cardMap.forEach(card => {
    if (card.category === 'battlefield') data.battlefields.push(card);
    else if (card.category === 'rune') data.runes.push(card);
    else if (card.category === 'main') data.mains.push(card);
    else if (card.category === 'sideboard') data.sideboard.push(card);
  });
  
  data.runes.sort((a, b) => a.cost - b.cost);
  data.mains.sort((a, b) => a.cost - b.cost);
  
  return data;
}

// -------- Layout Calculator --------

function calculateLayout(deckData, hasDeckName) {
  const cfg = EXPORT_CONFIG.canvas;
  const deckNameHeight = hasDeckName ? EXPORT_CONFIG.deckName.height : 0;
  
  const layout = {
    left: {
      width: 230,
      legendHeight: deckData.legend ? 320 : 0,
      battlefieldSlotHeight: 134,
      battlefieldGap: 40,
      legendToBfGap: 34
    },
    right: {
      columns: 6,
      slotWidth: 0,
      slotHeight: 0
    },
    deckNameHeight: deckNameHeight
  };
  
  // Calculate battlefield height
  const bfCount = deckData.battlefields.length;
  const bfHeight = bfCount 
    ? (bfCount * layout.left.battlefieldSlotHeight) + ((bfCount - 1) * layout.left.battlefieldGap)
    : 0;
  
  // Left column total
  const brandHeight = bfCount ? (EXPORT_CONFIG.brand.topMargin + EXPORT_CONFIG.brand.iconSize) : 0;
  layout.left.totalHeight = layout.left.legendHeight 
    + (deckData.legend && bfCount ? layout.left.legendToBfGap : 0)
    + bfHeight 
    + brandHeight;
  
  // Right panel
  layout.right.x = cfg.padding + layout.left.width + cfg.padding;
  layout.right.width = cfg.width - layout.right.x - cfg.padding;
  
  const cols = layout.right.columns;
  layout.right.slotWidth = Math.floor((layout.right.width - cfg.gap * (cols - 1)) / cols);
  layout.right.slotHeight = Math.floor(layout.right.slotWidth / (2.5 / 3.5));
  
  // Calculate rows
  const mainCards = [...deckData.runes, ...deckData.mains];
  const mainRows = Math.ceil(mainCards.length / cols);
  const sideRows = Math.ceil(deckData.sideboard.length / cols);
  
  const sideboardPadding = sideRows ? 46 : 0;
  const sectionGap = sideRows ? cfg.padding : 0;
  
  layout.right.height = 
    (mainRows ? mainRows * layout.right.slotHeight + Math.max(0, mainRows - 1) * cfg.gap : 0) +
    (sideRows ? sectionGap + sideboardPadding + sideRows * layout.right.slotHeight + Math.max(0, sideRows - 1) * cfg.gap : 0);
  
  // Final canvas height - add deck name height if present
  const contentHeight = Math.max(layout.left.totalHeight, layout.right.height);
  layout.canvasHeight = deckNameHeight + contentHeight + cfg.padding * 2;
  
  return { layout, mainCards, mainRows, sideRows };
}

// -------- Main Render Function --------

async function renderDeckImage() {
  const link = document.getElementById('download-export-image');
  
  try {
    // 1. Collect data
    const deckData = collectDeckData();
    
    // Get deck name
    const deckName = ($('#deck-name').val() || '').trim();
    
    // 2. Calculate layout
    const { layout, mainCards, mainRows, sideRows } = calculateLayout(deckData, !!deckName);
    
    // 3. Create canvas
    const { canvas, ctx, width, height } = createCanvas(
      EXPORT_CONFIG.canvas.width, 
      layout.canvasHeight
    );
    
    // 4. Load all images (single batch)
    const imagesToLoad = [
      ...(deckData.legend ? [deckData.legend.url] : []),
      ...deckData.battlefields.map(c => c.url),
      ...mainCards.map(c => c.url),
      ...deckData.sideboard.map(c => c.url),
      EXPORT_CONFIG.brand.iconUrl
    ];
    
    const uniqueUrls = [...new Set(imagesToLoad)];
    const loadedImages = await Promise.all(uniqueUrls.map(loadImage));
    
    // Create lookup map
    const imageMap = new Map();
    loadedImages.forEach(({ url, img, loaded }) => {
      if (loaded) imageMap.set(url, img);
    });
    
    // 5. Draw background
    drawBackground(ctx, width, height);
    
    // 6. Draw deck name at top if present
    const contentStartY = deckName ? layout.deckNameHeight : 0;
    
    if (deckName) {
      ctx.save();
      ctx.fillStyle = EXPORT_CONFIG.colors.text;
      ctx.font = `700 ${EXPORT_CONFIG.deckName.fontSize}px system-ui, sans-serif`;
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.shadowColor = 'rgba(0,0,0,0.3)';
      ctx.shadowBlur = 8;
      ctx.fillText(deckName, width / 2, layout.deckNameHeight / 2);
      ctx.restore();
    }
    
    // 7. Draw left column (shifted down by deck name height)
    let yPos = contentStartY + 12;
    const cfg = EXPORT_CONFIG.canvas;
    
    // Legend
    if (deckData.legend) {
      const img = imageMap.get(deckData.legend.url);
      if (img) {
        drawImageCover(ctx, img, cfg.padding, yPos, layout.left.width, layout.left.legendHeight);
        yPos += layout.left.legendHeight + layout.left.legendToBfGap;
      }
    }
    
    // Battlefields (rotated)
    deckData.battlefields.forEach(bf => {
      const img = imageMap.get(bf.url);
      if (!img) return;
      
      const x = cfg.padding;
      const h = layout.left.battlefieldSlotHeight;
      const w = layout.left.width;
      const cx = x + w / 2;
      const cy = yPos + h / 2;
      
      ctx.save();
      ctx.translate(cx, cy);
      ctx.rotate(Math.PI / 2);
      drawImageCover(ctx, img, -h / 2, -w / 2, h, w);
      ctx.restore();
      
      drawCountBadge(ctx, x, yPos, w, h, bf.qty);
      yPos += h + layout.left.battlefieldGap;
    });
    
    // Branding
    if (deckData.battlefields.length) {
      yPos += EXPORT_CONFIG.brand.topMargin;
      const brandImg = imageMap.get(EXPORT_CONFIG.brand.iconUrl);
      
      let xText = cfg.padding;
      if (brandImg) {
        ctx.drawImage(brandImg, cfg.padding, yPos, EXPORT_CONFIG.brand.iconSize, EXPORT_CONFIG.brand.iconSize);
        xText += EXPORT_CONFIG.brand.iconSize + EXPORT_CONFIG.brand.gap;
      }
      
      ctx.save();
      ctx.fillStyle = EXPORT_CONFIG.colors.text;
      ctx.font = '600 26px system-ui, sans-serif';
      ctx.textBaseline = 'middle';
      ctx.fillText(EXPORT_CONFIG.brand.text, xText, yPos + EXPORT_CONFIG.brand.iconSize / 2);
      ctx.restore();
    }
    
    // 8. Draw right panel (main grid) - shifted down by deck name height
    function drawGrid(cards, startY) {
      const cols = layout.right.columns;
      const slotW = layout.right.slotWidth;
      const slotH = layout.right.slotHeight;
      
      cards.forEach((card, i) => {
        const img = imageMap.get(card.url);
        if (!img) return;
        
        const row = Math.floor(i / cols);
        const col = i % cols;
        const x = layout.right.x + col * (slotW + cfg.gap);
        const y = startY + row * (slotH + cfg.gap);
        
        drawImageCover(ctx, img, x, y, slotW, slotH);
        
        // Subtle overlay
        ctx.save();
        ctx.globalCompositeOperation = 'multiply';
        ctx.fillStyle = 'rgba(0,0,0,0.06)';
        ctx.fillRect(x, y, slotW, slotH);
        ctx.restore();
        
        drawCountBadge(ctx, x, y, slotW, slotH, card.qty);
      });
    }
    
    const rightTop = contentStartY + 12;
    drawGrid(mainCards, rightTop);
    
    // 9. Draw sideboard
    if (sideRows) {
      const mainHeight = mainRows ? (mainRows * layout.right.slotHeight + (mainRows - 1) * cfg.gap) : 0;
      const sideStartY = rightTop + mainHeight + cfg.padding;
      
      // Separator
      drawSeparator(ctx, layout.right.x, sideStartY - 14, layout.right.width);
      
      // Label
      ctx.fillStyle = EXPORT_CONFIG.colors.text;
      ctx.font = '600 20px system-ui, sans-serif';
      ctx.textBaseline = 'top';
      ctx.fillText('SIDEBOARD', layout.right.x, sideStartY - 8);
      
      // Grid
      drawGrid(deckData.sideboard, sideStartY + 46);
    }
    
    // 10. Generate download
    const fileName = ((deckName || 'riftmana-deck') + '.jpg').replace(/[^\w.-]+/g, '_');
    
    if (canvas.toBlob) {
      canvas.toBlob((blob) => {
        const url = URL.createObjectURL(blob);
        link.href = url;
        link.download = fileName;
        link.style.display = 'inline-flex';
        canvas.style.display = 'block';
      }, EXPORT_CONFIG.format, EXPORT_CONFIG.quality);
    } else {
      // Fallback
      const dataUrl = canvas.toDataURL(EXPORT_CONFIG.format, EXPORT_CONFIG.quality);
      link.href = dataUrl;
      link.download = fileName;
      link.style.display = 'inline-flex';
      canvas.style.display = 'block';
    }
    
  } catch (error) {
    console.error('Export failed:', error);
    alert('Failed to generate deck image. Please try again.');
  }
}

// Wire the button
document.addEventListener('click', function(e) {
  const btn = e.target.closest('#generate-export-image');
  if (!btn) return;
  e.preventDefault();
  
  // Show loading state
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span class="button-text">Generating...</span>';
  
  renderDeckImage().finally(() => {
    btn.disabled = false;
    btn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> <span class="button-text">Generate Image</span>';
  });
}, false);

 
  
  

  // CLOSE HANDLERS (unchanged)
  $('#export-close-btn, .deck-builder-popup-close').on('click', function() {
    $('#export-deck-popup').fadeOut(200);
  });
  $('#export-deck-popup').on('click', function(e) {
    if (e.target === this) {
      $('#export-deck-popup').fadeOut(200);
    }
  });




});
})();
