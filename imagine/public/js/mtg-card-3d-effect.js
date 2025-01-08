class MTGCard3DTiltEffect {
    constructor(cardElement) {
        this.card = cardElement;
        if (!this.card) throw new Error('No card element provided');

        this.shine = this.createShineElement();
        this.rainbowShine = this.createRainbowShineElement();
        this.rarity = this.card.dataset.rarity;

        this.settings = {
            tiltEffectMaxRotation: this.getRarityBasedRotation(),
            tiltEffectPerspective: 1000,
            tiltEffectScale: this.getRarityBasedScale(),
            shineMovementRange: this.getRarityBasedShineRange(),
            rainbowShineMovementRange: 60,
            glowIntensity: this.getRarityBasedGlowIntensity()
        };

        this.setupEventListeners();
        this.injectStyles();
    }

    createShineElement() {
        return this.createAndAppendElement('shine-effect');
    }

    createRainbowShineElement() {
        const container = this.createAndAppendElement('rainbow-shine-container');
        const effect = this.createAndAppendElement('rainbow-shine-effect');
        container.appendChild(effect);
        return effect;
    }

    createAndAppendElement(className) {
        const element = document.createElement('div');
        element.classList.add(className);
        this.card.appendChild(element);
        return element;
    }

    setupEventListeners() {
        this.card.addEventListener('mouseenter', () => this.setTransition(false));
        this.card.addEventListener('mousemove', (e) => this.handleTilt(e));
        this.card.addEventListener('mouseleave', () => this.resetTilt());
    }

    setTransition(active) {
        const transition = active ? 'all 0.5s ease-out' : 'none';
        this.card.style.transition = transition;
        this.shine.style.transition = transition;
        this.rainbowShine.style.transition = transition;
    }

    handleTilt(e) {
        const { left, top, width, height } = this.card.getBoundingClientRect();
        const angleX = (e.clientX - (left + width / 2)) / (width / 2);
        const angleY = (e.clientY - (top + height / 2)) / (height / 2);

        const rotateX = angleY * this.settings.tiltEffectMaxRotation;
        const rotateY = -angleX * this.settings.tiltEffectMaxRotation;

        this.card.style.transform = `
            perspective(${this.settings.tiltEffectPerspective}px)
            rotateX(${rotateX}deg)
            rotateY(${rotateY}deg)
            scale3d(${this.settings.tiltEffectScale}, ${this.settings.tiltEffectScale}, ${this.settings.tiltEffectScale})
        `;

        this.updateShineEffect(this.shine, angleX, angleY, this.settings.shineMovementRange);
        this.updateShineEffect(this.rainbowShine, angleX, angleY, this.settings.rainbowShineMovementRange);
    }

    updateShineEffect(element, angleX, angleY, range) {
        const x = -angleX * range;
        const y = -angleY * range;
        element.style.transform = `translate(${x}%, ${y}%)`;
        element.style.opacity = '1';
    }

    resetTilt() {
        this.setTransition(true);
        this.card.style.transform = `
            perspective(${this.settings.tiltEffectPerspective}px)
            rotateX(0deg)
            rotateY(0deg)
            scale3d(1, 1, 1)
        `;
        this.resetShineEffect(this.shine);
        this.resetShineEffect(this.rainbowShine);
    }

    resetShineEffect(element) {
        element.style.transform = 'translate(0%, 0%)';
        element.style.opacity = '0';
    }

    getRarityBasedRotation() {
        switch(this.rarity) {
            case 'Mythic Rare': return 20;
            case 'Rare': return 15;
            case 'Uncommon': return 12;
            default: return 10;
        }
    }

    getRarityBasedScale() {
        switch(this.rarity) {
            case 'Mythic Rare': return 1.08;
            case 'Rare': return 1.06;
            case 'Uncommon': return 1.04;
            default: return 1.02;
        }
    }

    getRarityBasedShineRange() {
        switch(this.rarity) {
            case 'Mythic Rare': return 120;
            case 'Rare': return 100;
            case 'Uncommon': return 80;
            default: return 60;
        }
    }

    getRarityBasedGlowIntensity() {
        switch(this.rarity) {
            case 'Mythic Rare': return '0 0 40px rgba(255,140,0,0.4)';
            case 'Rare': return '0 0 30px rgba(255,215,0,0.3)';
            case 'Uncommon': return '0 0 20px rgba(192,192,192,0.2)';
            default: return 'none';
        }
    }

    injectStyles() {
        if (!document.getElementById('mtg-card-3d-tilt-effect-styles')) {
            const style = document.createElement('style');
            style.id = 'mtg-card-3d-tilt-effect-styles';
            style.textContent = `
                @keyframes rotate-slow {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                
                .animate-rotate-slow {
                    animation: rotate-slow 8s linear infinite;
                }
                
                .mtg-card {
                    transition: transform 0.1s ease-out;
                    transform-style: preserve-3d;
                    will-change: transform;
                    position: relative;
                    overflow: hidden;
                }
                .shine-effect {
                    position: absolute;
                    top: -50%;
                    left: -50%;
                    right: -50%;
                    bottom: -50%;
                    background: radial-gradient(
                        circle at 50% 50%,
                        rgba(255, 255, 255, 0.8) 0%,
                        rgba(255, 255, 255, 0.5) 25%,
                        rgba(255, 255, 255, 0.3) 50%,
                        rgba(255, 255, 255, 0.1) 75%,
                        rgba(255, 255, 255, 0) 100%
                    );
                    pointer-events: none;
                    opacity: 0;
                    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
                    mix-blend-mode: soft-light;
                }
                .rainbow-shine-container {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    overflow: hidden;
                    pointer-events: none;
                }
                .mythic-holographic {
                    background: linear-gradient(125deg, 
                        rgba(255,0,0,0.3),
                        rgba(255,165,0,0.3),
                        rgba(255,255,0,0.3),
                        rgba(0,255,0,0.3),
                        rgba(0,0,255,0.3),
                        rgba(75,0,130,0.3),
                        rgba(238,130,238,0.3)
                    );
                    animation: holographic 3s linear infinite;
                }

                .rare-holographic {
                    background: linear-gradient(125deg,
                        rgba(255,215,0,0.2),
                        rgba(255,255,255,0.3),
                        rgba(255,215,0,0.2)
                    );
                    animation: holographic 2s linear infinite;
                }

                .rainbow-shine-effect {
                    position: absolute;
                    top: -50%;
                    left: -50%;
                    right: -50%;
                    bottom: -50%;
                    background: conic-gradient(
                        from 0deg,
                        rgba(255,0,0,0.3) 0deg,
                        rgba(255,165,0,0.3) 60deg,
                        rgba(255,255,0,0.3) 120deg,
                        rgba(0,255,0,0.3) 180deg,
                        rgba(0,0,255,0.3) 240deg,
                        rgba(75,0,130,0.3) 300deg,
                        rgba(238,130,238,0.3) 360deg
                    );
                    opacity: 0;
                    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
                    mix-blend-mode: color-dodge;
                    filter: blur(8px);
                    animation: rotate-shine 6s linear infinite;
                }

                @keyframes holographic {
                    0% { filter: hue-rotate(0deg) brightness(1); }
                    50% { filter: hue-rotate(180deg) brightness(1.2); }
                    100% { filter: hue-rotate(360deg) brightness(1); }
                }

                @keyframes rotate-shine {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }

                .mythic-rare-card {
                    box-shadow: ${this.getRarityBasedGlowIntensity()};
                }

                .rare-card {
                    box-shadow: ${this.getRarityBasedGlowIntensity()};
                }
            `;
            document.head.appendChild(style);
        }
    }
}
