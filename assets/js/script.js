/**
 * JL Comércio e Serviços - Engine de Efeitos de UI Nativos
 * @author Dra. Helena Costa
 */
'use strict';

document.addEventListener('DOMContentLoaded', () => {
    UIAnimationEngine.init();
    UIInteractionEngine.initForms();
});

/**
 * Gerencia efeitos de transição por detecção de scroll usando IntersectionObserver API
 * Substitui bibliotecas pesadas de animação com performance nativa (sem travamentos de thread)
 */
const UIAnimationEngine = {
    init() {
        const elementsToAnimate = document.querySelectorAll('.animate-reveal');
        
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        // Desobserva o elemento após execução da transição para poupar processamento
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            elementsToAnimate.forEach(element => observer.observe(element));
        } else {
            // Fallback imediato para navegadores antigos sem suporte à API
            elementsToAnimate.forEach(element => element.classList.add('active'));
        }
    }
};

/**
 * Adiciona micro-interações refinadas nos formulários e inputs sem quebrar acessibilidade
 */
const UIInteractionEngine = {
    initForms() {
        const inputs = document.querySelectorAll('.form-control');

        inputs.forEach(input => {
            // Adiciona efeito visual dinâmico na caixa pai (wrapper) ao focar
            input.addEventListener('focus', (e) => {
                const group = e.target.closest('.form-group');
                if (group) {
                    group.style.transform = 'translateX(4px)';
                    group.style.transition = 'transform 0.2s ease';
                }
            });

            // Remove o deslocamento ao retirar o foco
            input.addEventListener('blur', (e) => {
                const group = e.target.closest('.form-group');
                if (group) {
                    group.style.transform = 'translateX(0)';
                }
            });
        });
    }
};