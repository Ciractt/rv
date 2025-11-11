// Advanced Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Color filter buttons
    const colorButtons = document.querySelectorAll('.color-btn');
    const colorInput = document.getElementById('colorInput');
    
    colorButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const color = this.dataset.color;
            
            // Toggle active state
            if (this.classList.contains('active')) {
                this.classList.remove('active');
                colorInput.value = '';
            } else {
                // Remove active from all others
                colorButtons.forEach(btn => btn.classList.remove('active'));
                // Add active to this one
                this.classList.add('active');
                colorInput.value = color;
            }
        });
    });
    
    // Dual range slider setup
    setupDualRangeSlider('energy', 'energyMin', 'energyMax', 'energyValue', 0, 12);
    setupDualRangeSlider('might', 'mightMin', 'mightMax', 'mightValue', 0, 10);
    setupDualRangeSlider('power', 'powerMin', 'powerMax', 'powerValue', 0, 10);
});

function setupDualRangeSlider(name, minId, maxId, valueId, minRange, maxRange) {
    const minSlider = document.getElementById(minId);
    const maxSlider = document.getElementById(maxId);
    const valueDisplay = document.getElementById(valueId);
    
    if (!minSlider || !maxSlider || !valueDisplay) return;
    
    function updateDisplay() {
        let minVal = parseInt(minSlider.value);
        let maxVal = parseInt(maxSlider.value);
        
        // Prevent crossing
        if (minVal > maxVal) {
            minSlider.value = maxVal;
            minVal = maxVal;
        }
        
        if (maxVal < minVal) {
            maxSlider.value = minVal;
            maxVal = minVal;
        }
        
        // Update display
        if (minVal === minRange && maxVal === maxRange) {
            valueDisplay.textContent = 'Any';
        } else {
            valueDisplay.textContent = `${minVal} - ${maxVal}`;
        }
        
        // Update visual track
        const percent1 = (minVal / maxRange) * 100;
        const percent2 = (maxVal / maxRange) * 100;
        
        const track = minSlider.parentElement.querySelector('.slider-track');
        if (track) {
            track.style.background = `linear-gradient(to right, #e0e0e0 ${percent1}%, #667eea ${percent1}%, #667eea ${percent2}%, #e0e0e0 ${percent2}%)`;
        }
    }
    
    minSlider.addEventListener('input', updateDisplay);
    maxSlider.addEventListener('input', updateDisplay);
    
    // Initialize display
    updateDisplay();
}

// Add sort dropdown to form
const sortSelect = document.querySelector('.sort-select');
if (sortSelect) {
    const form = document.getElementById('searchForm');
    const sortInput = document.createElement('input');
    sortInput.type = 'hidden';
    sortInput.name = 'sort';
    sortInput.value = sortSelect.value;
    form.appendChild(sortInput);
    
    sortSelect.addEventListener('change', function() {
        sortInput.value = this.value;
    });
}
