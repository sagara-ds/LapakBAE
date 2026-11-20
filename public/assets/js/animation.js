(() => {
  'use strict';

  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const gsap = window.gsap || null;
  const ScrollTrigger = window.ScrollTrigger || null;
  if (gsap && ScrollTrigger) gsap.registerPlugin(ScrollTrigger);

  // ScrollTrigger refresh re-measures pinned sections and can blur focused elements inside them.
  const refreshTriggersPreservingFocus = () => {
    if (!ScrollTrigger) return;
    const active = document.activeElement;
    ScrollTrigger.refresh();
    if (
      active &&
      active !== document.body &&
      !active.closest('.splash-wipe.is-done, .splash-wipe[hidden]') &&
      document.activeElement !== active &&
      typeof active.focus === 'function'
    ) {
      try {
        active.focus({ preventScroll: true });
      } catch (error) {
        active.focus();
      }
    }
  };

  let lenis = null;

  // Synchronize Lenis smooth scroll with GSAP ticker and ScrollTrigger.
  if (!reducedMotion && typeof window.Lenis === 'function') {
    lenis = new window.Lenis({
      lerp: 0.1,
      wheelMultiplier: 1,
      smoothWheel: true
    });
    window.__lapakLenis = lenis;

    if (gsap) {
      if (ScrollTrigger) lenis.on('scroll', ScrollTrigger.update);
      gsap.ticker.add(time => { lenis.raf(time * 1000); });
      gsap.ticker.lagSmoothing(0);
    } else {
      const raf = time => {
        lenis.raf(time);
        window.requestAnimationFrame(raf);
      };
      window.requestAnimationFrame(raf);
    }
  }

  // Smooth anchor scrolling for same-page hash navigation.
  document.querySelectorAll('a[href*="#"]').forEach(link => {
    link.addEventListener('click', event => {
      const url = new URL(link.href, location.href);
      if (url.pathname !== location.pathname || !url.hash || url.hash === '#') return;
      const target = document.getElementById(url.hash.slice(1));
      if (!target) return;
      event.preventDefault();
      if (lenis) lenis.scrollTo(target, { offset: -84 });
      else target.scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth' });
      history.pushState(null, '', url.hash);
    });
  });

  // Top editorial scroll progress hairline synced to page scroll position.
  const progressBar = document.querySelector('[data-scroll-progress]');
  if (progressBar) {
    const updateProgress = () => {
      const scrollable = document.documentElement.scrollHeight - window.innerHeight;
      const ratio = scrollable > 0 ? Math.min(1, Math.max(0, window.scrollY / scrollable)) : 0;
      progressBar.style.transform = `scaleX(${ratio.toFixed(4)})`;
    };
    if (lenis) lenis.on('scroll', updateProgress);
    else window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();
  }

  // Opening splash screen: store hub with 6 UMKM service logos scrubbed by scroll.
  const splashRoot = document.querySelector('[data-splash-scroll]');
  const splashWipe = document.querySelector('[data-splash-wipe]');
  const willShowWipe = Boolean(
    splashWipe && !splashWipe.hidden && !reducedMotion && gsap && window.scrollY <= 8 && !window.location.hash
  );
  let triggerSplashSettle = null;

  if (splashRoot && !reducedMotion && gsap) {
    const splashCenter = splashRoot.querySelector('[data-splash-center]');
    const splashNodes = [...splashRoot.querySelectorAll('[data-splash-node]')];
    const splashOrbit = splashRoot.querySelector('.splash-orbit-svg');
    const splashCue = splashRoot.querySelector('[data-splash-cue]');

    // Keep splash logos opaque behind the wipe glass so partial strokes reveal them immediately.
    if (willShowWipe) {
      triggerSplashSettle = () => {
        if (splashCenter) {
          gsap.fromTo(
            splashCenter,
            { scale: 0.96 },
            { scale: 1, duration: 0.48, ease: 'back.out(1.25)', clearProps: 'transform' }
          );
        }
        if (splashNodes.length) {
          gsap.fromTo(
            splashNodes,
            { scale: 0.94 },
            { scale: 1, duration: 0.52, stagger: 0.035, ease: 'back.out(1.2)', clearProps: 'transform' }
          );
        }
      };
    } else {
      const introTl = gsap.timeline({ defaults: { ease: 'back.out(1.25)' } });
      if (splashCenter) {
        introTl.from(splashCenter, {
          scale: 0.86,
          opacity: 0,
          duration: 0.65,
          clearProps: 'transform,opacity'
        });
      }
      if (splashNodes.length) {
        introTl.from(splashNodes, {
          scale: 0.65,
          opacity: 0,
          duration: 0.7,
          stagger: 0.06,
          clearProps: 'transform,opacity'
        }, '-=0.42');
      }
    }

    if (ScrollTrigger) {
      // Radial vectors push nodes outward at mid-scroll before converging into the center hub.
      const orbitVectors = [
        [-42, -32, -5],
        [42, -32, 5],
        [-54, 0, -4],
        [54, 0, 4],
        [-42, 32, 5],
        [42, 32, -5]
      ];
      const convergeVectors = [
        [150, 95],
        [-150, 95],
        [185, 0],
        [-185, 0],
        [150, -95],
        [-150, -95]
      ];

      const buildSplashTimeline = isPinned => {
        const tl = gsap.timeline({
          scrollTrigger: {
            trigger: splashRoot,
            pin: isPinned,
            scrub: isPinned ? 0.6 : 0.4,
            start: isPinned ? 'top top+=76' : 'top top',
            end: isPinned ? '+=80%' : 'bottom 30%',
            invalidateOnRefresh: true
          }
        });

        if (!isPinned) {
          if (splashCue) {
            tl.to(splashCue, { opacity: 0, y: 8, duration: 0.25, ease: 'none' }, 0);
          }
          if (splashNodes.length) {
            tl.to(splashNodes, {
              y: -8,
              scale: 1.015,
              duration: 0.4,
              stagger: 0.03,
              ease: 'power1.out'
            }, 0).to(splashNodes, {
              y: -14,
              scale: 0.97,
              opacity: 0.42,
              duration: 0.6,
              stagger: 0.03,
              ease: 'power1.inOut'
            }, 0.4);
          }
          if (splashCenter) {
            tl.to(splashCenter, { scale: 1.035, duration: 0.6, ease: 'power1.inOut' }, 0.4);
          }
          return tl;
        }

        if (splashOrbit) {
          tl.to(splashOrbit, { rotation: 40, scale: 1.08, duration: 0.5, ease: 'none' }, 0)
            .to(splashOrbit, { rotation: 75, scale: 0.72, opacity: 0.2, duration: 0.5, ease: 'none' }, 0.5);
        }

        splashNodes.forEach((node, idx) => {
          const [ox, oy, rot] = orbitVectors[idx] || [0, 0, 0];
          const [cx, cy] = convergeVectors[idx] || [0, 0];
          tl.to(node, {
            x: ox,
            y: oy,
            rotation: rot,
            scale: 1.04,
            duration: 0.5,
            ease: 'power1.out'
          }, 0).to(node, {
            x: cx,
            y: cy,
            rotation: 0,
            scale: 0.76,
            opacity: 0.16,
            duration: 0.5,
            ease: 'power2.in'
          }, 0.5);
        });

        if (splashCenter) {
          tl.to(splashCenter, { scale: 1.07, duration: 0.5, ease: 'power1.out' }, 0.5);
        }
        if (splashCue) {
          tl.to(splashCue, { opacity: 0, y: 10, duration: 0.3, ease: 'none' }, 0.1);
        }
        return tl;
      };

      const mm = gsap.matchMedia();
      mm.add('(min-width: 901px)', () => buildSplashTimeline(true));
      mm.add('(max-width: 900px)', () => buildSplashTimeline(false));
    }
  }

  // Full-screen auto-play splash wipe: plaid cloth clears the misty overlay into the intro.
  if (splashWipe) {
    const wipeCanvas = splashWipe.querySelector('[data-splash-wipe-canvas]');
    const wipeCloth = splashWipe.querySelector('[data-splash-wipe-cloth]');
    const wipeSkip = splashWipe.querySelector('[data-splash-wipe-skip]');
    const wipeStatus = document.querySelector('[data-splash-wipe-status]');
    let wipeDone = false;
    let wipeTl = null;
    let usedKeyboard = false;

    const unlockPage = () => {
      document.documentElement.classList.remove('is-splash-wipe-locked');
      document.documentElement.style.removeProperty('--splash-wipe-scrollbar');
      if (lenis) lenis.start();
    };

    const finishWipe = ({ instant = false } = {}) => {
      if (wipeDone) return;
      wipeDone = true;
      window.__lapakSplashWipeReady = true;
      wipeTl?.kill();
      const hadFocusInside = splashWipe.contains(document.activeElement);
      splashWipe.classList.add('is-done');
      if (wipeStatus) wipeStatus.textContent = 'Layar jernih. Pengantar LapakBae terbuka.';
      unlockPage();
      refreshTriggersPreservingFocus();
      if (!instant && typeof triggerSplashSettle === 'function') triggerSplashSettle();
      if (hadFocusInside) {
        if (usedKeyboard) {
          const focusTarget = document.querySelector('.splash-skip') || splashRoot;
          if (focusTarget) {
            if (focusTarget === splashRoot && !focusTarget.hasAttribute('tabindex')) {
              focusTarget.setAttribute('tabindex', '-1');
            }
            try {
              focusTarget.focus({ preventScroll: true });
            } catch (error) {
              focusTarget.focus();
            }
          }
        } else if (document.activeElement && typeof document.activeElement.blur === 'function') {
          document.activeElement.blur();
        }
      }
      if (instant) {
        splashWipe.hidden = true;
      } else {
        window.setTimeout(() => {
          splashWipe.hidden = true;
        }, 480);
      }
    };

    if (splashWipe.hidden) {
      unlockPage();
    } else if (reducedMotion || !gsap || !wipeCanvas || window.scrollY > 8 || window.location.hash) {
      finishWipe({ instant: true });
    } else {
      if (lenis) lenis.stop();

      const ctx = wipeCanvas.getContext('2d', { alpha: true });
      if (!ctx) {
        finishWipe({ instant: true });
      } else {
        let width = 0;
        let height = 0;
        let dpr = 1;
        let brush = 110;
        let lastX = 0;
        let lastY = 0;
        let initialised = false;
        let resizeTimer = 0;
        let textureCanvas = null;
        let brushStampCanvas = null;
        let brushStampRadius = 0;

        const buildMistTextureCanvas = (w, h, scale) => {
          const offscreen = document.createElement('canvas');
          offscreen.width = Math.max(1, Math.round(w * scale));
          offscreen.height = Math.max(1, Math.round(h * scale));
          const tctx = offscreen.getContext('2d');
          if (!tctx) return offscreen;

          tctx.setTransform(scale, 0, 0, scale, 0, 0);

          const base = tctx.createLinearGradient(0, 0, w, h);
          base.addColorStop(0, 'rgba(238, 242, 232, 0.96)');
          base.addColorStop(0.5, 'rgba(229, 235, 220, 0.95)');
          base.addColorStop(1, 'rgba(217, 226, 207, 0.96)');
          tctx.fillStyle = base;
          tctx.fillRect(0, 0, w, h);

          const fogColors = [
            'rgba(255, 255, 255, 0.44)',
            'rgba(244, 246, 240, 0.32)',
            'rgba(186, 200, 174, 0.16)'
          ];
          const cloudCount = Math.max(10, Math.round((w * h) / 72000));
          for (let i = 0; i < cloudCount; i += 1) {
            const x = Math.random() * w;
            const y = Math.random() * h;
            const radius = 90 + Math.random() * Math.min(w, h) * 0.28;
            const cloud = tctx.createRadialGradient(x, y, 0, x, y, radius);
            cloud.addColorStop(0, fogColors[i % fogColors.length]);
            cloud.addColorStop(1, 'rgba(255, 255, 255, 0)');
            tctx.fillStyle = cloud;
            tctx.fillRect(x - radius, y - radius, radius * 2, radius * 2);
          }

          const microMist = Math.round((w * h) / 2400);
          for (let i = 0; i < microMist; i += 1) {
            const x = Math.random() * w;
            const y = Math.random() * h;
            const size = Math.random() < 0.85 ? 1 : 2;
            tctx.fillStyle = Math.random() < 0.65 ? 'rgba(255, 255, 255, 0.42)' : 'rgba(92, 110, 82, 0.08)';
            tctx.fillRect(x, y, size, size);
          }

          const dropletCount = Math.max(26, Math.min(44, Math.round((w * h) / 30000)));
          for (let i = 0; i < dropletCount; i += 1) {
            const x = Math.random() * w;
            const y = Math.random() * h;
            const r = 1.6 + Math.random() * 2.8;
            tctx.fillStyle = 'rgba(45, 79, 30, 0.16)';
            tctx.beginPath();
            tctx.arc(x + 0.4, y + 0.8, r, 0, Math.PI * 2);
            tctx.fill();

            tctx.fillStyle = 'rgba(255, 255, 255, 0.78)';
            tctx.beginPath();
            tctx.arc(x - r * 0.28, y - r * 0.28, r * 0.42, 0, Math.PI * 2);
            tctx.fill();
          }

          for (let i = 0; i < 4; i += 1) {
            const x = w * (0.15 + Math.random() * 0.7);
            const y = h * (0.08 + Math.random() * 0.35);
            const length = 50 + Math.random() * 90;
            const trail = tctx.createLinearGradient(x, y, x, y + length);
            trail.addColorStop(0, 'rgba(255, 255, 255, 0.34)');
            trail.addColorStop(1, 'rgba(45, 79, 30, 0.08)');
            tctx.strokeStyle = trail;
            tctx.lineWidth = 1.4;
            tctx.beginPath();
            tctx.moveTo(x, y);
            tctx.lineTo(x, y + length);
            tctx.stroke();
          }

          const vignette = tctx.createRadialGradient(w / 2, h / 2, Math.min(w, h) * 0.28, w / 2, h / 2, Math.max(w, h) * 0.82);
          vignette.addColorStop(0, 'rgba(45, 65, 36, 0)');
          vignette.addColorStop(1, 'rgba(45, 65, 36, 0.14)');
          tctx.fillStyle = vignette;
          tctx.fillRect(0, 0, w, h);

          return offscreen;
        };

        const buildBrushStamp = radius => {
          const size = Math.max(2, Math.ceil(radius * 2));
          const stamp = document.createElement('canvas');
          stamp.width = size;
          stamp.height = size;
          const sctx = stamp.getContext('2d');
          if (!sctx) return stamp;
          const center = size / 2;
          const fade = sctx.createRadialGradient(center, center, radius * 0.22, center, center, radius);
          fade.addColorStop(0, 'rgba(0, 0, 0, 1)');
          fade.addColorStop(0.58, 'rgba(0, 0, 0, 0.94)');
          fade.addColorStop(0.84, 'rgba(0, 0, 0, 0.45)');
          fade.addColorStop(1, 'rgba(0, 0, 0, 0)');
          sctx.fillStyle = fade;
          sctx.fillRect(0, 0, size, size);
          return stamp;
        };

        const setCanvasSize = () => {
          let previous = null;
          if (initialised && wipeCanvas.width && wipeCanvas.height) {
            previous = document.createElement('canvas');
            previous.width = wipeCanvas.width;
            previous.height = wipeCanvas.height;
            previous.getContext('2d').drawImage(wipeCanvas, 0, 0);
          }

          width = window.innerWidth;
          height = window.innerHeight;
          dpr = Math.min(window.devicePixelRatio || 1, 2);
          brush = Math.max(82, Math.min(156, Math.round(Math.max(width, height) * 0.115)));
          if (!brushStampCanvas || brushStampRadius !== brush) {
            brushStampRadius = brush;
            brushStampCanvas = buildBrushStamp(brush);
          }

          wipeCanvas.width = Math.max(1, Math.round(width * dpr));
          wipeCanvas.height = Math.max(1, Math.round(height * dpr));
          wipeCanvas.style.width = `${width}px`;
          wipeCanvas.style.height = `${height}px`;

          ctx.setTransform(1, 0, 0, 1, 0, 0);
          ctx.clearRect(0, 0, wipeCanvas.width, wipeCanvas.height);

          if (!previous) {
            if (!textureCanvas) textureCanvas = buildMistTextureCanvas(width, height, dpr);
            ctx.drawImage(textureCanvas, 0, 0, wipeCanvas.width, wipeCanvas.height);
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            initialised = true;
            return;
          }

          ctx.drawImage(previous, 0, 0, previous.width, previous.height, 0, 0, wipeCanvas.width, wipeCanvas.height);
          ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        };

        const eraseAt = (x, y) => {
          if (!brushStampCanvas) return;
          ctx.save();
          ctx.globalCompositeOperation = 'destination-out';
          ctx.drawImage(brushStampCanvas, x - brush, y - brush, brush * 2, brush * 2);
          ctx.restore();
        };

        const strokeTo = (x, y) => {
          const dx = x - lastX;
          const dy = y - lastY;
          const distance = Math.hypot(dx, dy);
          const step = Math.max(4, brush * 0.28);
          const steps = Math.max(1, Math.ceil(distance / step));
          for (let i = 1; i <= steps; i += 1) {
            eraseAt(lastX + (dx * i) / steps, lastY + (dy * i) / steps);
          }
          lastX = x;
          lastY = y;
        };

        wipeSkip?.addEventListener('click', () => finishWipe());

        document.addEventListener('keydown', event => {
          if (wipeDone) return;
          if (event.key === 'Tab' || event.key === 'Enter' || event.key === ' ') {
            usedKeyboard = true;
          }
          if (event.key === 'Escape') {
            usedKeyboard = true;
            event.preventDefault();
            finishWipe();
          }
        });

        window.addEventListener('resize', () => {
          window.clearTimeout(resizeTimer);
          resizeTimer = window.setTimeout(() => {
            if (wipeDone) return;
            setCanvasSize();
          }, 150);
        });

        try {
          setCanvasSize();
          splashWipe.classList.add('is-canvas-ready');
          window.__lapakSplashWipeReady = true;

          const wiper = { x: width * 0.1, y: height * 0.14, rot: -10, scale: 0.88 };
          lastX = wiper.x;
          lastY = wiper.y;

          if (wipeCloth) {
            gsap.set(wipeCloth, {
              xPercent: -50,
              yPercent: -50,
              x: wiper.x,
              y: wiper.y,
              rotation: wiper.rot,
              scale: wiper.scale,
              opacity: 0
            });
          }

          eraseAt(wiper.x, wiper.y);

          const syncWiper = () => {
            if (wipeDone) return;
            if (wipeCloth) {
              gsap.set(wipeCloth, {
                x: wiper.x,
                y: wiper.y,
                rotation: wiper.rot,
                scale: wiper.scale
              });
            }
            strokeTo(wiper.x, wiper.y);
          };

          if (wipeStatus) wipeStatus.textContent = 'Membersihkan layar pembuka LapakBae.';

          wipeTl = gsap.timeline({
            onUpdate: syncWiper,
            onComplete: () => {
              if (wipeDone) return;
              if (wipeCloth) {
                gsap.to(wipeCloth, {
                  opacity: 0,
                  scale: 0.9,
                  y: '+=16',
                  duration: 0.26,
                  ease: 'power2.in'
                });
              }
              finishWipe();
            }
          });

          if (wipeCloth) {
            wipeTl.to(wipeCloth, { opacity: 1, duration: 0.16, ease: 'power2.out' }, 0);
          }
          wipeTl
            .to(wiper, { x: width * 0.9, y: height * 0.22, rot: 8, scale: 1, duration: 0.48, ease: 'sine.inOut' }, 0)
            .to(wiper, { x: width * 0.1, y: height * 0.44, rot: -11, duration: 0.5, ease: 'sine.inOut' })
            .to(wiper, { x: width * 0.9, y: height * 0.64, rot: 9, duration: 0.52, ease: 'sine.inOut' })
            .to(wiper, { x: width * 0.14, y: height * 0.85, rot: -7, duration: 0.5, ease: 'sine.inOut' });
        } catch (error) {
          finishWipe({ instant: true });
        }

        const skipIfScrolled = () => {
          if (!wipeDone && window.scrollY > 8) finishWipe({ instant: true });
        };
        window.addEventListener('scroll', skipIfScrolled, { passive: true });
        window.addEventListener('load', skipIfScrolled, { once: true });
        window.setTimeout(skipIfScrolled, 400);
      }
    }
  }

  // Interactive pinned-chapters workflow controller (click + keyboard + ScrollTrigger).
  const chapterRoot = document.querySelector('[data-pinned-chapters]');
  if (chapterRoot) {
    const triggers = [...chapterRoot.querySelectorAll('[data-chapter-trigger]')];
    const panels = [...chapterRoot.querySelectorAll('[data-chapter-panel]')];
    const progressFill = chapterRoot.querySelector('[data-chapter-progress]');
    let currentIndex = 0;

    const setChapter = index => {
      const clamped = Math.max(0, Math.min(index, triggers.length - 1));
      currentIndex = clamped;
      triggers.forEach((button, idx) => {
        const active = idx === clamped;
        button.classList.toggle('is-active', active);
        button.setAttribute('aria-selected', String(active));
      });
      panels.forEach((panel, idx) => {
        panel.classList.toggle('is-active', idx === clamped);
      });
      if (progressFill && triggers.length) {
        progressFill.style.transform = `scaleY(${((clamped + 1) / triggers.length).toFixed(3)})`;
      }
    };

    triggers.forEach((button, idx) => {
      button.addEventListener('click', () => setChapter(idx));
      button.addEventListener('keydown', event => {
        if (event.key === 'ArrowDown' || event.key === 'ArrowRight') {
          event.preventDefault();
          const next = (idx + 1) % triggers.length;
          setChapter(next);
          triggers[next].focus();
        } else if (event.key === 'ArrowUp' || event.key === 'ArrowLeft') {
          event.preventDefault();
          const prev = (idx - 1 + triggers.length) % triggers.length;
          setChapter(prev);
          triggers[prev].focus();
        }
      });
    });

    if (!reducedMotion && gsap && ScrollTrigger) {
      const mm = gsap.matchMedia();
      mm.add('(min-width: 901px)', () => {
        const trigger = ScrollTrigger.create({
          trigger: chapterRoot,
          start: 'top 25%',
          end: 'bottom 75%',
          scrub: true,
          onUpdate: self => {
            const idx = Math.min(triggers.length - 1, Math.floor(self.progress * triggers.length));
            if (idx !== currentIndex) setChapter(idx);
          }
        });
        return () => trigger.kill();
      });
    }
    setChapter(0);
  }

  // Hero entrance and picture-develops settle (never hides content without JS).
  if (!reducedMotion && gsap) {
    const heroCopy = document.querySelector('[data-anim="hero"]');
    if (heroCopy) {
      gsap.from(heroCopy.children, {
        y: 16,
        opacity: 0.72,
        duration: 0.65,
        stagger: 0.07,
        ease: 'power3.out',
        clearProps: 'transform,opacity'
      });
    }

    const developFrame = document.querySelector('[data-develop] img');
    if (developFrame) {
      gsap.fromTo(
        developFrame,
        { scale: 1.04, filter: 'saturate(0.75) contrast(0.95)' },
        { scale: 1, filter: 'saturate(1) contrast(1)', duration: 1.15, ease: 'power2.out', clearProps: 'transform,filter' }
      );
    }

    const stats = [...document.querySelectorAll('[data-anim="stat"]')];
    if (stats.length) {
      gsap.from(stats, {
        y: 10,
        duration: 0.42,
        stagger: 0.05,
        ease: 'power2.out',
        clearProps: 'transform'
      });
    }
  }

  window.LapakBaeMotion = {
    animateGrid: nodes => {
      if (reducedMotion || !gsap || !nodes?.length) return;
      gsap.fromTo(
        nodes,
        { y: 10, opacity: 0.78 },
        { y: 0, opacity: 1, duration: 0.36, stagger: 0.035, ease: 'power2.out', clearProps: 'transform,opacity' }
      );
    },
    scrollTo: target => {
      if (!target) return;
      if (lenis) lenis.scrollTo(target, { offset: -90 });
      else target.scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth', block: 'start' });
    },
    pauseScroll: () => { lenis?.stop(); },
    resumeScroll: () => { lenis?.start(); }
  };
  if (gsap && ScrollTrigger) {
    if (document.readyState === 'complete') refreshTriggersPreservingFocus();
    else window.addEventListener('load', refreshTriggersPreservingFocus, { once: true });
    if (document.fonts?.ready) document.fonts.ready.then(refreshTriggersPreservingFocus).catch(() => {});
  }
})();
