/**
 * SMO - Theme Switcher
 * Sistema de alternância entre tema claro e escuro
 */

(function () {
    'use strict';

    // Configuração
    const THEME_KEY = 'smo-theme-preference';
    const THEMES = {
        LIGHT: 'light',
        DARK: 'dark'
    };

    // Elementos
    let themeToggle = null;
    let themeIcon = null;
    let themeStylesheet = null;

    // Inicialização
    document.addEventListener('DOMContentLoaded', function () {
        initThemeSwitcher();
    });

    /**
     * Inicializa o sistema de troca de tema
     */
    function initThemeSwitcher() {
        createToggleButton();
        loadThemePreference();
        setupEventListeners();
    }

    /**
     * Cria o botão de toggle
     */
    function createToggleButton() {
        // Cria o botão
        const button = document.createElement('button');
        button.id = 'theme-toggle';
        button.className = 'theme-toggle';
        button.setAttribute('aria-label', 'Alternar tema');
        button.setAttribute('title', 'Alternar entre tema claro e escuro');

        // Cria o ícone
        const icon = document.createElement('i');
        icon.className = 'bi bi-sun-fill';
        icon.id = 'theme-icon';

        button.appendChild(icon);

        // Adiciona ao body (fixo no canto)
        const toggleContainer = document.createElement('div');
        toggleContainer.className = 'theme-toggle-container';
        toggleContainer.appendChild(button);
        document.body.appendChild(toggleContainer);

        themeToggle = button;
        themeIcon = icon;
    }

    /**
     * Carrega a preferência de tema salva
     */
    function loadThemePreference() {
        const savedTheme = localStorage.getItem(THEME_KEY);
        const preferredTheme = savedTheme || THEMES.LIGHT; // Padrão: claro

        applyTheme(preferredTheme, true);
    }

    /**
     * Aplica o tema
     */
    function applyTheme(theme, animate = true) {
        // Remove stylesheet existente se houver
        const existingStylesheet = document.getElementById('theme-stylesheet');
        if (existingStylesheet) {
            existingStylesheet.remove();
        }

        // Cria novo stylesheet
        const link = document.createElement('link');
        link.id = 'theme-stylesheet';
        link.rel = 'stylesheet';
        link.href = theme === THEMES.DARK
            ? 'assets/css/style-dark-modern.css'
            : 'assets/css/style-light-modern.css';

        // Adiciona antes do style-filters.css
        const filtersStylesheet = document.querySelector('link[href*="style-filters"]');
        if (filtersStylesheet) {
            filtersStylesheet.parentNode.insertBefore(link, filtersStylesheet);
        } else {
            document.head.appendChild(link);
        }

        themeStylesheet = link;

        // Atualiza ícone
        updateIcon(theme, animate);

        // Atualiza atributo do body
        document.body.setAttribute('data-theme', theme);

        // Adiciona classe de transição se animate for true
        if (animate) {
            document.body.classList.add('theme-transitioning');
            setTimeout(() => {
                document.body.classList.remove('theme-transitioning');
            }, 300);
        }

        // Salva preferência
        localStorage.setItem(THEME_KEY, theme);

        // Dispara evento customizado
        document.dispatchEvent(new CustomEvent('themeChanged', {
            detail: { theme: theme }
        }));
    }

    /**
     * Atualiza o ícone do botão
     */
    function updateIcon(theme, animate) {
        if (!themeIcon) return;

        const isDark = theme === THEMES.DARK;
        const newIconClass = isDark ? 'bi-moon-stars-fill' : 'bi-sun-fill';
        const oldIconClass = isDark ? 'bi-sun-fill' : 'bi-moon-stars-fill';

        if (animate) {
            // Animação de rotação
            themeIcon.style.transform = 'rotate(360deg)';

            setTimeout(() => {
                themeIcon.classList.remove(oldIconClass);
                themeIcon.classList.add(newIconClass);
                themeIcon.style.transform = 'rotate(0deg)';
            }, 150);
        } else {
            themeIcon.classList.remove(oldIconClass);
            themeIcon.classList.add(newIconClass);
        }

        // Atualiza título
        const newTitle = isDark
            ? 'Mudar para tema claro'
            : 'Mudar para tema escuro';
        themeToggle.setAttribute('title', newTitle);
        themeToggle.setAttribute('aria-label', newTitle);
    }

    /**
     * Configura event listeners
     */
    function setupEventListeners() {
        if (themeToggle) {
            themeToggle.addEventListener('click', toggleTheme);
        }

        // Atalho de teclado: Ctrl/Cmd + Shift + D
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                e.preventDefault();
                toggleTheme();
            }
        });
    }

    /**
     * Alterna entre os temas
     */
    function toggleTheme() {
        const currentTheme = localStorage.getItem(THEME_KEY) || THEMES.LIGHT;
        const newTheme = currentTheme === THEMES.DARK ? THEMES.LIGHT : THEMES.DARK;

        applyTheme(newTheme, true);

        // Feedback visual
        showToast(
            newTheme === THEMES.DARK
                ? 'Tema escuro ativado'
                : 'Tema claro ativado'
        );
    }

    /**
     * Mostra toast de feedback
     */
    function showToast(message) {
        // Remove toast existente se houver
        const existingToast = document.querySelector('.theme-toast');
        if (existingToast) {
            existingToast.remove();
        }

        // Cria novo toast
        const toast = document.createElement('div');
        toast.className = 'theme-toast';
        toast.innerHTML = `
            <i class="bi bi-check-circle-fill me-2"></i>
            <span>${message}</span>
        `;

        document.body.appendChild(toast);

        // Anima entrada
        setTimeout(() => toast.classList.add('show'), 10);

        // Remove após 2 segundos
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }

    // Expõe funções globalmente
    window.ThemeSwitcher = {
        toggle: toggleTheme,
        setTheme: applyTheme,
        getTheme: () => localStorage.getItem(THEME_KEY) || THEMES.LIGHT,
        THEMES: THEMES
    };

})();

// Adiciona estilos do Theme Switcher
(function () {
    const style = document.createElement('style');
    style.textContent = `
        /* Theme Toggle Button */
        .theme-toggle-container {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 996;   
            display: flex;
            align-items: center;
        }

        .theme-toggle {
            padding: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid var(--border-color, #e0e0e0);
            background: var(--primary-light, #ffffff);
            color: var(--text-primary, #1a1a1a);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .theme-toggle::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(0, 100, 0, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.3s ease, height 0.3s ease;
        }

        .theme-toggle:hover::before {
            width: 100%;
            height: 100%;
        }

        .theme-toggle:hover {
            border-color: #006400;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .theme-toggle:active {
            transform: scale(0.95);
        }

        .theme-toggle i {
            font-size: 1.1rem;
            transition: transform 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .theme-toggle:hover i {
            color: #006400;
        }

        /* Tema Escuro - Ajustes do botão */
        body[data-theme="dark"] .theme-toggle {
            background: var(--gray-dark, #1a2d1a);
            border-color: var(--border-color, #373e47);
            color: var(--text-primary, #e6edf3);
        }

        body[data-theme="dark"] .theme-toggle::before {
            background: rgba(0, 100, 0, 0.2);
        }

        /* Toast de Feedback */
        .theme-toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--primary-light, #ffffff);
            color: var(--text-primary, #1a1a1a);
            padding: 1rem 1.5rem;
            border-radius: 8px;
            border: 1px solid var(--border-color, #e0e0e0);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            z-index: 10000;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            pointer-events: none;
        }

        body[data-theme="dark"] .theme-toast {
            background: var(--secondary-dark, #0a2f0a);
            border-color: var(--border-color, #373e47);
            color: var(--text-primary, #e6edf3);
        }

        .theme-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .theme-toast i {
            color: #006400;
            font-size: 1.25rem;
        }

        /* Transição suave entre temas */
        body.theme-transitioning,
        body.theme-transitioning * {
            transition: background-color 0.3s ease, 
                        color 0.3s ease, 
                        border-color 0.3s ease,
                        box-shadow 0.3s ease !important;
        }

        /* Acessibilidade - Focus */
        .theme-toggle:focus-visible {
            outline: 2px solid #006400;
            outline-offset: 2px;
        }

        /* Animação de pulso no primeiro uso */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        .theme-toggle.first-time {
            animation: pulse 2s ease-in-out 3;
        }
    `;
    document.head.appendChild(style);
})();
