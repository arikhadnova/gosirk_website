/**
 * Language Switcher for GoSirk Website
 * Supports Indonesian (ID) and English (EN)
 */

(function() {
    'use strict';

    // Language configuration
    const LANG_CONFIG = {
        id: {
            code: 'id',
            name: 'Bahasa Indonesia',
            flag: 'id',
            flagLabel: 'ID'
        },
        en: {
            code: 'en',
            name: 'English',
            flag: 'gb',
            flagLabel: 'EN'
        }
    };

    // Default language
    const DEFAULT_LANG = 'en';

    /**
     * Get current language from localStorage or default
     */
    function getCurrentLanguage() {
        // If not migrated to new default EN, force reset once
        if (!localStorage.getItem('gosirk_lang_migrated_v1')) {
            localStorage.setItem('gosirk_lang_migrated_v1', 'true');
            localStorage.setItem('gosirk_language', DEFAULT_LANG);
            return DEFAULT_LANG;
        }
        return localStorage.getItem('gosirk_language') || DEFAULT_LANG;
    }

    /**
     * Set current language to localStorage
     */
    function setCurrentLanguage(lang) {
        localStorage.setItem('gosirk_language', lang);
    }

    /**
     * Update navbar flag and label
     */
    function updateNavbarDisplay(lang) {
        const config = LANG_CONFIG[lang];
        const flagImg = document.getElementById('current-flag');
        const langLabel = document.getElementById('current-lang');

        if (flagImg) {
            flagImg.src = `https://flagcdn.com/w20/${config.flag}.png`;
            flagImg.alt = config.flagLabel;
        }

        if (langLabel) {
            langLabel.textContent = config.flagLabel;
        }

        // Update HTML lang attribute
        document.documentElement.lang = lang;
    }

    /**
     * Helper to get nested object value by string path
     */
    function getNestedValue(obj, path) {
        return path.split('.').reduce((prev, curr) => {
            return prev ? prev[curr] : null;
        }, obj);
    }

    /**
     * Translate all elements with data-i18n attributes
     */
    function translatePage(lang) {
        if (typeof resources === 'undefined') {
            console.error('Translations resources not loaded!');
            return;
        }

        const translations = resources[lang].translation;
        const elements = document.querySelectorAll('[data-i18n]');

        elements.forEach(element => {
            const key = element.getAttribute('data-i18n');
            const translation = getNestedValue(translations, key);

            if (translation) {
                if (element.hasAttribute('data-i18n-html') || translation.includes('<')) {
                    element.innerHTML = translation;
                } else if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                     // Form fields: translate the placeholder, never the value the visitor types
                     element.placeholder = translation;
                } else {
                    element.textContent = translation;
                }
            } else {
                console.warn(`Missing translation for key: ${key} in language: ${lang}`);
            }
        });

        // Handle data-i18n-placeholder
        const placeholderElements = document.querySelectorAll('[data-i18n-placeholder]');
        placeholderElements.forEach(element => {
            const key = element.getAttribute('data-i18n-placeholder');
            const translation = getNestedValue(translations, key);
            if (translation) {
                element.placeholder = translation;
            }
        });

        // Translate dynamic elements from database
        const dynamicElements = document.querySelectorAll('[data-lang-id][data-lang-en]');
        dynamicElements.forEach(element => {
            const translation = lang === 'en' ? element.getAttribute('data-lang-en') : element.getAttribute('data-lang-id');
            if (translation && translation.trim() !== '') {
                if (element.hasAttribute('data-i18n-html') || (translation && translation.includes('<'))) {
                    element.innerHTML = translation;
                } else {
                    element.textContent = translation;
                }
            }
        });
    }

    /**
     * Switch to specified language
     */
    function switchLanguage(lang) {
        if (!LANG_CONFIG[lang]) {
            console.error('Invalid language:', lang);
            return;
        }

        setCurrentLanguage(lang);
        updateNavbarDisplay(lang);
        translatePage(lang);

        // Trigger custom event for other scripts
        window.dispatchEvent(new CustomEvent('languageChanged', { detail: { language: lang } }));
    }

    /**
     * Initialize language system
     */
    function initLanguageSystem() {
        const currentLang = getCurrentLanguage();

        // Set initial language
        updateNavbarDisplay(currentLang);
        
        // Wait specifically ensuring resources are loaded if placed in header
        if (typeof resources !== 'undefined') {
             translatePage(currentLang);
        } else {
            // Retry briefly if translations.js loads after this (though header order prevents this usually)
            setTimeout(() => translatePage(currentLang), 100);
        }

        // Add click handlers to language selector links
        const langLinks = document.querySelectorAll('.lang-select');
        langLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const lang = this.getAttribute('data-lang');
                switchLanguage(lang);
            });
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLanguageSystem);
    } else {
        initLanguageSystem();
    }

    // Expose API to window for external access
    window.GoSirkLang = {
        switch: switchLanguage,
        getCurrent: getCurrentLanguage,
        config: LANG_CONFIG
    };

})();
