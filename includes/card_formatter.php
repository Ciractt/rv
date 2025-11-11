<?php
/**
 * Card Description Formatter
 * Formats card descriptions with keywords, icons, and special styling
 */

// Keyword color definitions
// Card Description Formatter - Client Side
const KEYWORD_COLORS = {
    'ACCELERATE': { bg: '#246F60', text: '#FFFFFF' },
    'ATTACK': { bg: '#B91C1C', text: '#FFFFFF' },
    'GANKING': { bg: '#7C3AED', text: '#FFFFFF' },
    'CHALLENGER': { bg: '#DC2626', text: '#FFFFFF' },
    'QUICK ATTACK': { bg: '#EA580C', text: '#FFFFFF' },
    'TOUGH': { bg: '#059669', text: '#FFFFFF' },
    'OVERWHELM': { bg: '#DC2626', text: '#FFFFFF' },
    'ELUSIVE': { bg: '#3B82F6', text: '#FFFFFF' },
    'LIFESTEAL': { bg: '#EC4899', text: '#FFFFFF' },
    'FEARSOME': { bg: '#6366F1', text: '#FFFFFF' },
    'BARRIER': { bg: '#10B981', text: '#FFFFFF' },
    'REGENERATION': { bg: '#059669', text: '#FFFFFF' },
    'FROSTBITE': { bg: '#06B6D4', text: '#FFFFFF' },
    'STUN': { bg: '#F59E0B', text: '#FFFFFF' },
    'RECALL': { bg: '#8B5CF6', text: '#FFFFFF' }
};

const ICON_MAPPINGS = {
    '{0}': '0', '{1}': '1', '{2}': '2', '{3}': '3', '{4}': '4',
    '{5}': '5', '{6}': '6', '{7}': '7', '{8}': '8', '{9}': '9', '{10}': '10',
    '{fury}': 'fury',
    '{energy}': 'energy',
    '{power}': 'power',
    '{might}': 'might'
};

// Base URL for icons - change this to point to your icon location
const ICON_BASE_URL = 'https://riftmana.com/wp-content/uploads/icons/';

function formatCardDescription(description, keywords = null) {
    if (!description) return '';

    let formatted = description;

    // Parse keywords
    const keywordList = keywords ? keywords.split(',').map(k => k.trim().toUpperCase()) : [];

    // Format keywords with special styling
    Object.keys(KEYWORD_COLORS).forEach(keyword => {
        const regex = new RegExp('\\b(' + keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')\\b', 'gi');

        if (regex.test(formatted)) {
            const colors = KEYWORD_COLORS[keyword];
            const replacement = `<span class="keyword-badge" style="background-color: ${colors.bg}; color: ${colors.text};">$1</span>`;
            formatted = formatted.replace(regex, replacement);
        }
    });

    // Replace icon placeholders
    Object.keys(ICON_MAPPINGS).forEach(placeholder => {
        if (formatted.includes(placeholder)) {
            const iconName = ICON_MAPPINGS[placeholder];
            const iconHtml = `<img src="${ICON_BASE_URL}${iconName}.svg" alt="${iconName}" class="inline-icon" onerror="this.style.display='none'">`;
            formatted = formatted.replace(new RegExp(placeholder.replace(/[.*+?^${}()|[\]\\]/g, '\\    // Replace icon placeholders
    Object.keys(ICON_MAPPINGS).forEach(placeholder => {
        if (formatted.includes(placeholder)) {
            const iconName = ICON_MAPPINGS[placeholder];
            const iconHtml = `<img src="https://cdn.piltoverarchive.com/icons/${iconName}.svg" alt="${iconName}" class="inline-icon" onerror="this.style.display='none'">`;
            formatted = formatted.replace(new RegExp(placeholder.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g'), iconHtml);
        }
    });'), 'g'), iconHtml);
        }
    });

    // Wrap italic text (text in parentheses)
    formatted = formatted.replace(/\((.*?)\)/g, '<span class="italic">($1)</span>');

    return formatted;
}

function getKeywordBadge(keyword) {
    keyword = keyword.trim().toUpperCase();

    if (KEYWORD_COLORS[keyword]) {
        const colors = KEYWORD_COLORS[keyword];
        return `<span class="keyword-badge" style="background-color: ${colors.bg}; color: ${colors.text};">${keyword}</span>`;
    }

    return `<span class="keyword-badge">${keyword}</span>`;
}

// Export for use in other scripts
window.formatCardDescription = formatCardDescription;
window.getKeywordBadge = getKeywordBadge;
}
