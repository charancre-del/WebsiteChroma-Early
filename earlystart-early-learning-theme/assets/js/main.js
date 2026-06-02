/**
 * Main JavaScript
 * Data-attribute based modular approach
 *
 * @package EarlyStart_Early_Start
 */

document.addEventListener('DOMContentLoaded', function () {
  /**
   * Performance-first Lucide initialization.
   */
  let lucideLoadPromise = null;

  const onIdle = (callback, timeout = 2000) => {
    if ('requestIdleCallback' in window) {
      window.requestIdleCallback(callback, { timeout });
    } else {
      setTimeout(callback, 100);
    }
  };

  const hasLucideIcons = () => !!document.querySelector('[data-lucide]');

  const loadLucide = () => {
    if (typeof lucide !== 'undefined') {
      return Promise.resolve(lucide);
    }

    if (!hasLucideIcons()) {
      return Promise.resolve(null);
    }

    if (lucideLoadPromise) {
      return lucideLoadPromise;
    }

    if (!window.chromaData || !window.chromaData.themeUrl) {
      return Promise.resolve(null);
    }

    lucideLoadPromise = new Promise((resolve) => {
      const script = document.createElement('script');
      script.src = `${window.chromaData.themeUrl}/assets/js/lucide.min.js`;
      script.async = true;
      script.onload = () => resolve(typeof lucide !== 'undefined' ? lucide : null);
      script.onerror = () => resolve(null);
      document.body.appendChild(script);
    });

    return lucideLoadPromise;
  };

  const refreshIcons = () => {
    if (!hasLucideIcons() && typeof lucide === 'undefined') {
      return;
    }

    onIdle(() => {
      loadLucide().then((icons) => {
        if (icons && typeof icons.createIcons === 'function') {
          icons.createIcons();
        }
      });
    });
  };

  refreshIcons();

  const safeParseJSON = (value, fallback) => {
    try {
      return JSON.parse(value);
    } catch (e) {
      if (window.chromaData && window.chromaData.debug && window.console && typeof window.console.warn === 'function') {
        window.console.warn('Failed to parse JSON payload', e);
      }
      return fallback;
    }
  };

  const getHashTarget = (hash) => {
    if (!hash || hash === '#') return null;

    try {
      return document.getElementById(decodeURIComponent(hash.slice(1)));
    } catch {
      return document.getElementById(hash.slice(1));
    }
  };

  const prefersReducedMotion = () => (
    typeof window.matchMedia === 'function' &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches
  );

  const shouldRunAutoplay = () => !prefersReducedMotion() && document.visibilityState !== 'hidden';

  /**
   * Mobile Nav Toggle
   */
  const mobileNavToggles = document.querySelectorAll('[data-mobile-nav-toggle]');
  const mobileNav = document.querySelector('[data-mobile-nav]');

  const setMobileNavExpanded = (expanded) => {
    mobileNavToggles.forEach((toggle) => {
      toggle.setAttribute('aria-expanded', expanded.toString());
    });
  };

  const closeMobileNav = () => {
    if (!mobileNav) return;

    mobileNav.classList.remove('translate-x-0');
    mobileNav.classList.add('translate-x-full');
    document.body.style.overflow = '';
    setMobileNavExpanded(false);

    setTimeout(() => {
      if (mobileNav.classList.contains('translate-x-full')) {
        mobileNav.classList.add('hidden');
      }
    }, 300);
  };

  mobileNavToggles.forEach((toggle) => {
    toggle.addEventListener('click', () => {
      if (!mobileNav) return;

      const isHidden = mobileNav.classList.contains('hidden');

      if (isHidden) {
        // Opening
        mobileNav.classList.remove('hidden');
        // Small delay to allow transition
        requestAnimationFrame(() => {
          mobileNav.classList.remove('translate-x-full');
          mobileNav.classList.add('translate-x-0');
        });
        document.body.style.overflow = 'hidden';
        setMobileNavExpanded(true);
      } else {
        closeMobileNav();
      }
    });
  });

  // Close menu on link click
  if (mobileNav) {
    mobileNav.querySelectorAll('a[href]').forEach((link) => {
      link.addEventListener('click', () => {
        closeMobileNav();
      });
    });
  }

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && mobileNav && !mobileNav.classList.contains('hidden')) {
      closeMobileNav();
    }
  });

  /**
   * Accordions (Enhanced)
   */
  const accordions = document.querySelectorAll('[data-accordion-group]');

  accordions.forEach((group) => {
    const items = group.querySelectorAll('[data-accordion]');

    items.forEach((item) => {
      const trigger = item.querySelector('[data-accordion-trigger]');
      const content = item.querySelector('[data-accordion-content]');
      const icon = item.querySelector('[data-accordion-icon]');

      if (!trigger || !content) return;

      trigger.addEventListener('click', () => {
        const isExpanded = trigger.getAttribute('aria-expanded') === 'true';

        // Close others in group if needed (default behavior)
        items.forEach((otherItem) => {
          if (otherItem === item) return;
          const otherTrigger = otherItem.querySelector('[data-accordion-trigger]');
          const otherContent = otherItem.querySelector('[data-accordion-content]');
          const otherIcon = otherItem.querySelector('[data-accordion-icon]');

          if (otherTrigger) otherTrigger.setAttribute('aria-expanded', 'false');
          if (otherContent) otherContent.classList.add('hidden');
          if (otherIcon) otherIcon.classList.remove('rotate-45');
          otherItem.classList.remove('active');
        });

        // Toggle current
        const nextState = !isExpanded;
        trigger.setAttribute('aria-expanded', nextState.toString());
        content.classList.toggle('hidden', !nextState);
        item.classList.toggle('active', nextState);
        if (icon) icon.classList.toggle('rotate-45', nextState);
      });
    });
  });

  /**
   * Zip Code Checker (Home Therapy Zones)
   */
  const zipInput = document.getElementById('chroma-zip-input');
  const zipBtn = document.getElementById('chroma-zip-btn');
  const zipMsg = document.getElementById('chroma-zip-message');

  if (zipInput && zipBtn && zipMsg) {
    // Metro Atlanta Zips (Approximate list for demo)
    const validZips = [
      '30004', '30005', '30009', '30022', '30024', '30040', '30041', '30075', '30076', // North Fulton/Forsyth
      '30060', '30062', '30064', '30066', '30067', '30068', // Cobb
      '30043', '30044', '30045', '30046', '30047', // Gwinnett
      '30319', '30328', '30338', '30342', '30350' // Sandy Springs/Dunwoody
    ];

    const checkZip = () => {
      const zip = zipInput.value.replace(/\D/g, '').slice(0, 5);
      zipInput.value = zip;

      if (zip.length !== 5) {
        zipMsg.textContent = 'Please enter a valid 5-digit zip code.';
        zipMsg.className = 'mt-6 text-stone-500 text-sm font-bold min-h-[20px]';
        return;
      }

      const isValid = validZips.includes(zip);
      const renderZipMessage = (icon, text, textClass) => {
        zipMsg.textContent = '';

        const wrapper = document.createElement('span');
        wrapper.className = `${textClass} flex items-center justify-center gap-2`;

        const iconEl = document.createElement('i');
        iconEl.setAttribute('data-lucide', icon);
        iconEl.className = 'w-4 h-4';

        wrapper.appendChild(iconEl);
        wrapper.appendChild(document.createTextNode(text));
        zipMsg.appendChild(wrapper);
        refreshIcons();
      };

      if (isValid) {
        renderZipMessage('check-circle', `Great news! We serve ${zip}.`, 'text-green-600');
      } else {
        renderZipMessage('info', `We're expanding! ${zip} isn't active yet, but contact us to confirm.`, 'text-amber-600');
      }
    };

    zipBtn.addEventListener('click', checkZip);
    zipInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') checkZip();
    });
  }

  /**
   * Generic Tabs Handler (Services, Schedule, etc.)
   */
  const initTabs = (selector, attrPrefix) => {
    const containers = document.querySelectorAll(selector);
    containers.forEach((container) => {
      const tabs = container.querySelectorAll(`[data-${attrPrefix}-tab]`);
      const panels = container.querySelectorAll(`[data-${attrPrefix}-panel]`);
      const tabGroupId = container.id || `${attrPrefix}-tabs`;

      if (!container.hasAttribute('role')) {
        container.setAttribute('role', 'tablist');
      }

      const activateTab = (tab, shouldFocus = false) => {
        const target = tab.getAttribute(`data-${attrPrefix}-tab`);
        if (!target) return;

        // Update tabs
        tabs.forEach((t) => {
          const isActive = t === tab;
          t.classList.toggle('active', isActive);
          t.setAttribute('aria-selected', isActive.toString());
          t.setAttribute('tabindex', isActive ? '0' : '-1');

          // Handle specific styling for therapy tabs
          if (attrPrefix === 'services') {
            t.classList.toggle('bg-rose-600', isActive);
            t.classList.toggle('text-white', isActive);
            t.classList.toggle('bg-white', !isActive);
            t.classList.toggle('text-stone-600', !isActive);
          }
        });

        // Update panels
        panels.forEach((panel) => {
          const isMatch = panel.getAttribute(`data-${attrPrefix}-panel`) === target;
          panel.classList.toggle('hidden', !isMatch);
          panel.toggleAttribute('hidden', !isMatch);
          if (isMatch) {
            panel.classList.add('fade-in');
          }
        });

        if (shouldFocus) {
          tab.focus();
        }

        // Refresh Lucide icons in panels if needed
        refreshIcons();
      };

      tabs.forEach((tab, index) => {
        const key = tab.getAttribute(`data-${attrPrefix}-tab`) || String(index);
        const tabId = tab.id || `${tabGroupId}-${key}-tab`;
        const panelId = `${tabGroupId}-${key}-panel`;
        const panel = container.querySelector(`[data-${attrPrefix}-panel="${key}"]`);
        const isActive = tab.classList.contains('active') || index === 0;

        tab.id = tabId;
        tab.setAttribute('role', 'tab');
        tab.setAttribute('aria-controls', panelId);
        tab.setAttribute('aria-selected', isActive.toString());
        tab.setAttribute('tabindex', isActive ? '0' : '-1');

        if (panel) {
          panel.id = panel.id || panelId;
          panel.setAttribute('role', 'tabpanel');
          panel.setAttribute('aria-labelledby', tabId);
          panel.setAttribute('tabindex', '0');
          panel.toggleAttribute('hidden', !isActive);
          panel.classList.toggle('hidden', !isActive);
        }

        tab.addEventListener('click', () => activateTab(tab));
        tab.addEventListener('keydown', (event) => {
          const keyHandlers = ['ArrowRight', 'ArrowDown', 'ArrowLeft', 'ArrowUp', 'Home', 'End'];
          if (!keyHandlers.includes(event.key)) {
            return;
          }

          event.preventDefault();

          let nextIndex = index;
          if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
            nextIndex = (index + 1) % tabs.length;
          } else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
            nextIndex = (index - 1 + tabs.length) % tabs.length;
          } else if (event.key === 'Home') {
            nextIndex = 0;
          } else if (event.key === 'End') {
            nextIndex = tabs.length - 1;
          }

          activateTab(tabs[nextIndex], true);
        });
      });
    });
  };

  initTabs('[data-services-tabs]', 'services');

  /**
   * Team Bio Modal Logic
   */
  const teamModal = document.getElementById('team-modal');
  const teamModalContent = document.getElementById('team-modal-content');
  const modalClose = document.getElementById('team-modal-close');
  const modalOverlay = document.getElementById('team-modal-overlay');

  if (teamModal && teamModalContent) {
    const triggers = document.querySelectorAll('[data-team-bio-trigger]');
    const focusableSelector = [
      'a[href]',
      'button:not([disabled])',
      'textarea:not([disabled])',
      'input:not([disabled])',
      'select:not([disabled])',
      '[tabindex]:not([tabindex="-1"])'
    ].join(',');
    let lastTeamTrigger = null;

    if (!teamModalContent.hasAttribute('tabindex')) {
      teamModalContent.setAttribute('tabindex', '-1');
    }

    const isTeamModalOpen = () => !teamModal.classList.contains('hidden');

    const getModalFocusableElements = () => {
      return Array.from(teamModal.querySelectorAll(focusableSelector)).filter((element) => {
        return element.offsetParent !== null || element === document.activeElement;
      });
    };

    const trapModalFocus = (event) => {
      if (event.key !== 'Tab' || !isTeamModalOpen()) {
        return;
      }

      const focusableElements = getModalFocusableElements();
      if (!focusableElements.length) {
        event.preventDefault();
        teamModalContent.focus();
        return;
      }

      const firstElement = focusableElements[0];
      const lastElement = focusableElements[focusableElements.length - 1];

      if (event.shiftKey && document.activeElement === firstElement) {
        event.preventDefault();
        lastElement.focus();
      } else if (!event.shiftKey && document.activeElement === lastElement) {
        event.preventDefault();
        firstElement.focus();
      }
    };

    triggers.forEach(trigger => {
      trigger.addEventListener('click', () => {
        const data = safeParseJSON(trigger.getAttribute('data-team-bio-trigger') || '{}', {});
        if (!data.name && !data.bio) return;
        lastTeamTrigger = trigger;

        // Populate modal
        const modalName = document.getElementById('modal-name');
        const modalRole = document.getElementById('modal-role');
        const modalBio = document.getElementById('modal-bio');
        const modalImage = document.getElementById('modal-image');
        const modalImageWrap = document.querySelector('[data-modal-image-wrap]');
        const modalCopyWrap = document.querySelector('[data-modal-copy-wrap]');

        if (modalName) modalName.textContent = data.name || '';
        if (modalRole) modalRole.textContent = data.role || '';
        if (modalBio) modalBio.textContent = data.bio_text || data.bio || '';
        if (modalImage) {
          if (data.image) {
            modalImage.src = data.image;
          } else {
            modalImage.removeAttribute('src');
          }
          modalImage.alt = data.name ? data.name : '';
        }
        if (modalImageWrap) modalImageWrap.classList.toggle('hidden', !data.image);
        if (modalCopyWrap) {
          modalCopyWrap.classList.toggle('md:w-3/5', !!data.image);
          modalCopyWrap.classList.toggle('w-full', !data.image);
        }

        // Show modal
        teamModal.setAttribute('aria-hidden', 'false');
        teamModal.classList.remove('hidden');
        teamModal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
          teamModalContent.classList.remove('scale-95', 'opacity-0');
          teamModalContent.classList.add('scale-100', 'opacity-100');
          if (modalClose) modalClose.focus();
        }, 10);
      });
    });

    const closeModal = () => {
      teamModalContent.classList.remove('scale-100', 'opacity-100');
      teamModalContent.classList.add('scale-95', 'opacity-0');

      setTimeout(() => {
        teamModal.classList.add('hidden');
        teamModal.classList.remove('flex');
        teamModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (lastTeamTrigger && typeof lastTeamTrigger.focus === 'function') {
          lastTeamTrigger.focus();
        }
      }, 300);
    };

    if (modalClose) modalClose.addEventListener('click', closeModal);
    if (modalOverlay) modalOverlay.addEventListener('click', closeModal);
    document.addEventListener('keydown', (event) => {
      if (!isTeamModalOpen()) {
        return;
      }

      if (event.key === 'Escape') {
        closeModal();
      } else {
        trapModalFocus(event);
      }
    });
  }

  /**
   * Programs wizard
   */
  /**
   * Programs wizard
   */
  const wizard = document.querySelector('[data-program-wizard]');
  if (wizard) {
    const options = safeParseJSON(wizard.getAttribute('data-options') || '[]', []);
    const optionButtons = wizard.querySelectorAll('[data-program-wizard-option]');
    const result = wizard.querySelector('[data-program-wizard-result]');
    const title = wizard.querySelector('[data-program-wizard-title]');
    const desc = wizard.querySelector('[data-program-wizard-description]');
    const image = wizard.querySelector('[data-program-wizard-image]');
    const imageWrap = wizard.querySelector('[data-program-wizard-image-wrap]');
    const learnLink = wizard.querySelector('[data-program-wizard-link]');
    const defaultLearnHref = learnLink?.getAttribute('href') || '#';
    const resetBtn = wizard.querySelector('[data-program-wizard-reset]');

    const showResult = (selected) => {
      if (!result) return;

      // Populate data
      if (title) title.textContent = selected.label;
      if (desc) desc.textContent = selected.description;
      if (learnLink) {
        learnLink.setAttribute('href', selected.link || defaultLearnHref);
        learnLink.setAttribute('aria-label', 'Learn more about ' + selected.label);
      }
      if (image) {
        if (selected.image) {
          image.src = selected.image;
          image.alt = selected.label || 'Program preview';
          imageWrap?.classList.remove('hidden');
        } else {
          image.removeAttribute('src');
          image.alt = '';
          imageWrap?.classList.add('hidden');
        }
      }

      // Hide options
      wizard.querySelector('[data-program-wizard-options]')?.classList.add('hidden');

      // Show result with animation
      result.classList.remove('hidden');
      // Small delay to allow display:grid to apply before transitioning opacity
      requestAnimationFrame(() => {
        result.classList.remove('opacity-0', 'translate-y-4');
        result.classList.add('opacity-100', 'translate-y-0');
      });
    };

    const resetWizard = () => {
      if (!result) return;

      // Hide result with animation
      result.classList.add('opacity-0', 'translate-y-4');
      result.classList.remove('opacity-100', 'translate-y-0');

      // Wait for transition to finish before hiding
      setTimeout(() => {
        result.classList.add('hidden');
        wizard.querySelector('[data-program-wizard-options]')?.classList.remove('hidden');
      }, 500); // Matches duration-500
    };

    optionButtons.forEach((btn) => {
      btn.addEventListener('click', () => {
        const key = btn.getAttribute('data-program-wizard-option');
        const selected = options.find((o) => o.key === key);
        if (selected) showResult(selected);
      });
    });

    resetBtn?.addEventListener('click', resetWizard);
  }

  /**
   * Curriculum radar chart
   */
  const curriculumConfigEl = document.querySelector('[data-curriculum-config]');
  const curriculumChartEl = document.querySelector('[data-curriculum-chart]');
  const curriculumButtons = document.querySelectorAll('[data-curriculum-button]');

  if (curriculumConfigEl && curriculumChartEl) {
    const config = safeParseJSON(curriculumConfigEl.textContent || '{}', {});
    const profiles = config.profiles || [];
    const labels = config.labels || [];
    const defaultProfile = profiles[0];
    let chartInstance = null;

    const setActiveProfile = (key) => {
      const profile = profiles.find((p) => p.key === key) || defaultProfile;
      if (!profile) return;

      curriculumButtons.forEach((btn) => {
        const isActive = btn.getAttribute('data-curriculum-button') === profile.key;
        if (isActive) {
          btn.classList.add('bg-chroma-blue', 'text-white', 'shadow-soft');
          btn.classList.remove('text-brand-ink/70', 'bg-white');
        } else {
          btn.classList.remove('bg-chroma-blue', 'text-white', 'shadow-soft');
          btn.classList.add('text-brand-ink/70', 'bg-white');
        }
      });

      const title = document.querySelector('[data-curriculum-title]');
      const description = document.querySelector('[data-curriculum-description]');
      if (title) title.textContent = profile.title;
      if (description) description.textContent = profile.description;

      if (window.Chart && chartInstance) {
        chartInstance.data.datasets[0].data = profile.data;
        chartInstance.data.datasets[0].borderColor = profile.color;
        chartInstance.data.datasets[0].backgroundColor = `${profile.color}33`;
        chartInstance.data.datasets[0].pointBorderColor = profile.color;
        chartInstance.update();
      }
    };

    const initChart = () => {
      // Use double requestAnimationFrame to prevent forced reflow during chart initialization
      requestAnimationFrame(() => {
        requestAnimationFrame(() => {
          chartInstance = new Chart(curriculumChartEl.getContext('2d'), {
            type: 'radar',
            data: {
              labels,
              datasets: [
                {
                  label: 'Focus',
                  data: (defaultProfile && defaultProfile.data) || [],
                  borderColor: defaultProfile?.color || '#4A6C7C',
                  backgroundColor: `${defaultProfile?.color || '#4A6C7C'}33`,
                  borderWidth: 2,
                  pointBackgroundColor: '#ffffff',
                  pointBorderColor: defaultProfile?.color || '#4A6C7C',
                  pointRadius: 4,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: { legend: { display: false } },
              scales: {
                r: {
                  angleLines: { color: '#e5e7eb' },
                  grid: { color: '#e5e7eb' },
                  suggestedMin: 0,
                  suggestedMax: 100,
                  ticks: { display: false },
                  pointLabels: {
                    font: { family: 'Outfit, system-ui, sans-serif', size: 12 },
                    color: '#263238',
                  },
                },
              },
            },
          });
        });
      });
    };

    if (window.Chart) {
      initChart();
    } else {
      const loadChart = () => {
        if (!window.chromaData || !window.chromaData.themeUrl) return;

        const script = document.createElement('script');
        script.src = window.chromaData.chartUrl || `${window.chromaData.themeUrl}/assets/js/chart.min.js`;
        script.async = true;
        script.onload = initChart;
        document.body.appendChild(script);
      };

      if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              observer.disconnect();
              loadChart();
            }
          });
        }, { rootMargin: '200px' });
        observer.observe(curriculumChartEl);
      } else {
        loadChart();
      }
    }

    curriculumButtons.forEach((btn) => {
      btn.addEventListener('click', () => {
        setActiveProfile(btn.getAttribute('data-curriculum-button'));
      });
    });

    if (defaultProfile) {
      setActiveProfile(defaultProfile.key);
    }
  }

  /**
   * Schedule tabs
   */
  const schedule = document.querySelector('[data-schedule]');
  if (schedule) {
    const panels = schedule.querySelectorAll('[data-schedule-panel]');
    const tabs = schedule.querySelectorAll('[data-schedule-tab]');
    const defaultKey = tabs[0]?.getAttribute('data-schedule-tab');
    const tabList = schedule.querySelector('[data-schedule-tabs]');
    const scheduleId = schedule.id || 'schedule-tabs';

    if (tabList && !tabList.hasAttribute('role')) {
      tabList.setAttribute('role', 'tablist');
    }

    const activate = (key, shouldFocus = false) => {
      tabs.forEach((btn) => {
        const isActive = btn.getAttribute('data-schedule-tab') === key;
        btn.classList.toggle('bg-chroma-blue', isActive);
        btn.classList.toggle('text-white', isActive);
        btn.classList.toggle('shadow-soft', isActive);
        btn.classList.toggle('text-brand-ink/60', !isActive);
        // Fix for hover state on active tab (prevents blue text on blue bg)
        btn.classList.toggle('hover:text-chroma-blue', !isActive);

        // Remove inline styles to let CSS classes handle colors
        btn.style.backgroundColor = '';
        btn.style.color = '';
        btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        btn.setAttribute('aria-selected', isActive.toString());
        btn.setAttribute('tabindex', isActive ? '0' : '-1');

        if (isActive && shouldFocus) {
          btn.focus();
        }
      });

      panels.forEach((panel) => {
        const isMatch = panel.getAttribute('data-schedule-panel') === key;
        panel.classList.toggle('hidden', !isMatch);
        panel.classList.toggle('active', isMatch);
        panel.toggleAttribute('hidden', !isMatch);
      });
    };

    tabs.forEach((btn, index) => {
      const key = btn.getAttribute('data-schedule-tab') || String(index);
      const isActive = key === defaultKey;
      const tabId = btn.id || `${scheduleId}-${key}-tab`;
      const panelId = `${scheduleId}-${key}-panel`;
      const panel = schedule.querySelector(`[data-schedule-panel="${key}"]`);

      btn.id = tabId;
      btn.setAttribute('role', 'tab');
      btn.setAttribute('aria-controls', panelId);
      btn.setAttribute('aria-selected', isActive.toString());
      btn.setAttribute('tabindex', isActive ? '0' : '-1');

      if (panel) {
        panel.id = panel.id || panelId;
        panel.setAttribute('role', 'tabpanel');
        panel.setAttribute('aria-labelledby', tabId);
        panel.setAttribute('tabindex', '0');
        panel.toggleAttribute('hidden', !isActive);
      }

      btn.addEventListener('click', () => {
        activate(btn.getAttribute('data-schedule-tab'));
      });

      btn.addEventListener('keydown', (event) => {
        const keyHandlers = ['ArrowRight', 'ArrowDown', 'ArrowLeft', 'ArrowUp', 'Home', 'End'];
        if (!keyHandlers.includes(event.key)) {
          return;
        }

        event.preventDefault();

        let nextIndex = index;
        if (event.key === 'ArrowRight' || event.key === 'ArrowDown') {
          nextIndex = (index + 1) % tabs.length;
        } else if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') {
          nextIndex = (index - 1 + tabs.length) % tabs.length;
        } else if (event.key === 'Home') {
          nextIndex = 0;
        } else if (event.key === 'End') {
          nextIndex = tabs.length - 1;
        }

        activate(tabs[nextIndex].getAttribute('data-schedule-tab'), true);
      });
    });

    if (defaultKey) {
      activate(defaultKey);
    }

    // Handle internal step clicks (Time buttons)
    const stepTriggers = schedule.querySelectorAll('[data-schedule-step-trigger]');
    const setStepTriggerState = (trigger, isActive) => {
      trigger.classList.toggle('bg-brand-ink', isActive);
      trigger.classList.toggle('text-white', isActive);
      trigger.classList.toggle('shadow-md', isActive);
      trigger.classList.toggle('scale-105', isActive);
      trigger.classList.toggle('bg-white', !isActive);
      trigger.classList.toggle('text-brand-ink/70', !isActive);
      trigger.classList.toggle('hover:text-brand-ink', !isActive);
      trigger.classList.toggle('hover:bg-white/80', !isActive);
      trigger.setAttribute('aria-pressed', isActive.toString());
    };

    schedule.querySelectorAll('[data-schedule-content]').forEach((content) => {
      content.setAttribute('aria-live', 'polite');
      content.setAttribute('aria-atomic', 'true');
    });

    stepTriggers.forEach((trigger) => {
      setStepTriggerState(trigger, trigger.classList.contains('bg-brand-ink'));

      trigger.addEventListener('click', function () {
        // Find parent panel
        const panel = this.closest('[data-schedule-panel]');
        if (!panel) return;

        // Reset all triggers in this panel
        const panelTriggers = panel.querySelectorAll('[data-schedule-step-trigger]');
        panelTriggers.forEach((t) => setStepTriggerState(t, false));

        // Activate clicked trigger
        setStepTriggerState(this, true);

        // Update content
        const title = this.getAttribute('data-title');
        const copy = this.getAttribute('data-copy');
        const contentTitle = panel.querySelector('[data-content-title]');
        const contentCopy = panel.querySelector('[data-content-copy]');

        if (contentTitle) contentTitle.textContent = title;
        if (contentCopy) contentCopy.textContent = copy;
      });
    });
  }

  /**
   * Parent Reviews Carousel
   */
  const reviewsCarousel = document.querySelector('[data-reviews-carousel]');
  if (reviewsCarousel && reviewsCarousel.querySelector('[data-reviews-track]') && reviewsCarousel.querySelectorAll('[data-review-slide]').length) {
    const track = reviewsCarousel.querySelector('[data-reviews-track]');
    const dots = reviewsCarousel.querySelectorAll('[data-review-dot]');
    const prevBtn = reviewsCarousel.querySelector('[data-review-prev]');
    const nextBtn = reviewsCarousel.querySelector('[data-review-next]');
    const slides = reviewsCarousel.querySelectorAll('[data-review-slide]');

    let currentIndex = 0;
    const totalSlides = slides.length;
    let autoplayInterval = null;
    let isCarouselVisible = !('IntersectionObserver' in window);

    if (prevBtn && !prevBtn.hasAttribute('aria-label')) {
      prevBtn.setAttribute('aria-label', 'Show previous review');
    }

    if (nextBtn && !nextBtn.hasAttribute('aria-label')) {
      nextBtn.setAttribute('aria-label', 'Show next review');
    }

    const goToSlide = (index) => {
      if (index < 0) index = totalSlides - 1;
      if (index >= totalSlides) index = 0;

      currentIndex = index;
      track.style.transform = `translateX(-${currentIndex * 100}%)`;

      slides.forEach((slide, i) => {
        slide.setAttribute('aria-hidden', (i !== currentIndex).toString());
      });

      // Update dots
      dots.forEach((dot, i) => {
        const isActive = i === currentIndex;
        dot.setAttribute('aria-label', `Show review ${i + 1} of ${totalSlides}`);
        dot.setAttribute('aria-current', isActive ? 'true' : 'false');
        dot.setAttribute('aria-pressed', isActive.toString());

        if (isActive) {
          dot.classList.remove('bg-chroma-blue/30', 'hover:bg-chroma-blue/50', 'w-3');
          dot.classList.add('bg-chroma-red', 'w-8');
        } else {
          dot.classList.remove('bg-chroma-red', 'w-8');
          dot.classList.add('bg-chroma-blue/30', 'hover:bg-chroma-blue/50', 'w-3');
        }
      });
    };

    const nextSlide = () => goToSlide(currentIndex + 1);
    const prevSlide = () => goToSlide(currentIndex - 1);

    // Dot navigation
    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        goToSlide(index);
        resetAutoplay();
      });
    });

    goToSlide(0);

    // Arrow navigation
    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        prevSlide();
        resetAutoplay();
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        nextSlide();
        resetAutoplay();
      });
    }

    // Autoplay
    const startAutoplay = () => {
      if (totalSlides > 1 && isCarouselVisible && !autoplayInterval && shouldRunAutoplay()) {
        autoplayInterval = setInterval(nextSlide, 6000);
      }
    };

    const stopAutoplay = () => {
      if (autoplayInterval) {
        clearInterval(autoplayInterval);
        autoplayInterval = null;
      }
    };

    const resetAutoplay = () => {
      stopAutoplay();
      startAutoplay();
    };

    if ('IntersectionObserver' in window) {
      const carouselObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.target !== reviewsCarousel) {
            return;
          }

          isCarouselVisible = entry.isIntersecting;
          if (isCarouselVisible) {
            startAutoplay();
          } else {
            stopAutoplay();
          }
        });
      }, { threshold: 0.15 });

      carouselObserver.observe(reviewsCarousel);
    } else {
      startAutoplay();
    }

    // Pause on hover
    reviewsCarousel.addEventListener('mouseenter', stopAutoplay);
    reviewsCarousel.addEventListener('mouseleave', startAutoplay);
    document.addEventListener('visibilitychange', () => {
      if (document.visibilityState === 'hidden' || prefersReducedMotion()) {
        stopAutoplay();
      } else {
        startAutoplay();
      }
    });

    // Touch/swipe support
    let touchStartX = 0;
    let touchEndX = 0;

    track.addEventListener('touchstart', (e) => {
      touchStartX = e.changedTouches[0].screenX;
    });

    track.addEventListener('touchend', (e) => {
      touchEndX = e.changedTouches[0].screenX;
      handleSwipe();
    });

    const handleSwipe = () => {
      const swipeThreshold = 50;
      if (touchStartX - touchEndX > swipeThreshold) {
        nextSlide();
        resetAutoplay();
      } else if (touchEndX - touchStartX > swipeThreshold) {
        prevSlide();
        resetAutoplay();
      }
    };
  }

  /**
   * Smooth Scrolling for Anchor Links
   */
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;

      const targetElement = getHashTarget(targetId);
      if (targetElement) {
        e.preventDefault();
        targetElement.scrollIntoView({
          behavior: prefersReducedMotion() ? 'auto' : 'smooth',
        });
      }
    });
  });
  /**
   * Location Hero Carousel
   */
  const locationCarousel = document.querySelector('[data-location-carousel]');
  if (locationCarousel && locationCarousel.querySelector('[data-location-carousel-track]') && locationCarousel.querySelectorAll('[data-location-slide]').length) {
    const track = locationCarousel.querySelector('[data-location-carousel-track]');
    const slides = locationCarousel.querySelectorAll('[data-location-slide]');
    const prevBtn = locationCarousel.querySelector('[data-location-prev]');
    const nextBtn = locationCarousel.querySelector('[data-location-next]');
    const dots = locationCarousel.querySelectorAll('[data-location-dot]');

    let currentIndex = 0;
    const totalSlides = slides.length;
    let autoplayInterval = null;
    let isCarouselVisible = !('IntersectionObserver' in window);

    if (prevBtn && !prevBtn.hasAttribute('aria-label')) {
      prevBtn.setAttribute('aria-label', 'Show previous location');
    }

    if (nextBtn && !nextBtn.hasAttribute('aria-label')) {
      nextBtn.setAttribute('aria-label', 'Show next location');
    }

    const updateCarousel = (index) => {
      if (index < 0) index = totalSlides - 1;
      if (index >= totalSlides) index = 0;

      currentIndex = index;
      track.style.transform = `translateX(-${currentIndex * 100}%)`;

      slides.forEach((slide, i) => {
        slide.setAttribute('aria-hidden', (i !== currentIndex).toString());
      });

      // Update dots
      if (dots.length) {
        dots.forEach((dot, i) => {
          const isActive = i === currentIndex;
          dot.setAttribute('aria-label', `Show location ${i + 1} of ${totalSlides}`);
          dot.setAttribute('aria-current', isActive ? 'true' : 'false');
          dot.setAttribute('aria-pressed', isActive.toString());

          if (isActive) {
            dot.classList.remove('bg-white/50');
            dot.classList.add('bg-white', 'w-6');
          } else {
            dot.classList.remove('bg-white', 'w-6');
            dot.classList.add('bg-white/50');
          }
        });
      }
    };

    updateCarousel(0);

    const nextSlide = () => updateCarousel(currentIndex + 1);
    const prevSlide = () => updateCarousel(currentIndex - 1);

    // Event Listeners
    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        prevSlide();
        resetAutoplay();
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        nextSlide();
        resetAutoplay();
      });
    }

    if (dots.length) {
      dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
          updateCarousel(index);
          resetAutoplay();
        });
      });
    }

    // Autoplay
    const startAutoplay = () => {
      if (totalSlides > 1 && isCarouselVisible && !autoplayInterval && shouldRunAutoplay()) {
        autoplayInterval = setInterval(nextSlide, 5000);
      }
    };

    const stopAutoplay = () => {
      if (autoplayInterval) {
        clearInterval(autoplayInterval);
        autoplayInterval = null;
      }
    };

    const resetAutoplay = () => {
      stopAutoplay();
      startAutoplay();
    };

    if ('IntersectionObserver' in window) {
      const carouselObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.target !== locationCarousel) {
            return;
          }

          isCarouselVisible = entry.isIntersecting;
          if (isCarouselVisible) {
            startAutoplay();
          } else {
            stopAutoplay();
          }
        });
      }, { threshold: 0.15 });

      carouselObserver.observe(locationCarousel);
    } else {
      startAutoplay();
    }

    // Pause on hover
    locationCarousel.addEventListener('mouseenter', stopAutoplay);
    locationCarousel.addEventListener('mouseleave', startAutoplay);
    document.addEventListener('visibilitychange', () => {
      if (document.visibilityState === 'hidden' || prefersReducedMotion()) {
        stopAutoplay();
      } else {
        startAutoplay();
      }
    });
  }

  /**
   * Enhanced Lazy Loading with IntersectionObserver
   * - Uses data-lazy-src for deferred images
   * - Adds smooth fade-in animation
   * - Falls back to native loading="lazy" for unsupported browsers
   */
  const lazyImageSelector = 'img[data-lazy-src]:not([data-lazy-bound])';
  const nativeLazyImageSelector = 'img[loading="lazy"]:not(.no-lazy):not([data-lazy-bound])';
  const anyLazyImageSelector = `${lazyImageSelector}, ${nativeLazyImageSelector}`;

  const initEnhancedLazyLoading = () => {
    // All images with data-lazy-src attribute
    const lazyImages = document.querySelectorAll(lazyImageSelector);

    // Also handle images with loading="lazy" that aren't above-the-fold
    const nativeLazyImages = document.querySelectorAll(nativeLazyImageSelector);

    if (!lazyImages.length && !nativeLazyImages.length) {
      return;
    }

    const markLazyBound = (img) => {
      img.dataset.lazyBound = 'true';
    };

    const completeLazyImage = (img) => {
      img.style.opacity = '1';
      img.classList.add('lazy-loaded');
    };

    const prepareLazyFade = (img) => {
      if (prefersReducedMotion()) {
        completeLazyImage(img);
        return;
      }

      img.style.opacity = '0';
      img.style.transition = 'opacity 0.3s ease-in-out';
    };

    const bindLazyImageState = (img) => {
      if (img.complete) {
        completeLazyImage(img);
        return;
      }

      img.addEventListener('load', () => completeLazyImage(img), { once: true });
      img.addEventListener('error', () => {
        img.style.opacity = '1';
      }, { once: true });
    };

    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            markLazyBound(img);

            // Handle data-lazy-src images
            if (img.dataset.lazySrc) {
              img.src = img.dataset.lazySrc;
              if (img.dataset.lazySrcset) {
                img.srcset = img.dataset.lazySrcset;
              }
              delete img.dataset.lazySrc;
              delete img.dataset.lazySrcset;
            }

            // Add fade-in effect
            prepareLazyFade(img);
            bindLazyImageState(img);

            observer.unobserve(img);
          }
        });
      }, {
        rootMargin: '50px 0px', // Start loading 50px before entering viewport
        threshold: 0.01
      });

      // Observe data-lazy-src images
      lazyImages.forEach(img => {
        markLazyBound(img);
        imageObserver.observe(img);
      });

      // Observe native lazy images for fade-in effect
      nativeLazyImages.forEach(img => {
        markLazyBound(img);
        prepareLazyFade(img);
        bindLazyImageState(img);
      });

    } else {
      // Fallback for browsers without IntersectionObserver
      lazyImages.forEach(img => {
        markLazyBound(img);

        if (img.dataset.lazySrc) {
          img.src = img.dataset.lazySrc;
          if (img.dataset.lazySrcset) {
            img.srcset = img.dataset.lazySrcset;
          }
        }

        completeLazyImage(img);
      });
      nativeLazyImages.forEach((img) => {
        markLazyBound(img);
        completeLazyImage(img);
      });
    }
  };

  // Initialize lazy loading
  initEnhancedLazyLoading();

  // Also handle dynamically added images (for AJAX/SPA scenarios)
  // Optimize MutationObserver to be more selective if possible, 
  // or use a debounced approach to reduce main thread load
  let mutationTimeout;
  // Only observe if we actually have dynamic content areas
  const dynamicContent = document.querySelector('#main-content');
  if (dynamicContent && 'MutationObserver' in window) {
    const lazyLoadObserver = new MutationObserver((mutations) => {
      const shouldRefreshLazyImages = mutations.some(mutation => (
        Array.from(mutation.addedNodes).some(node => (
          node.nodeType === 1 && (
            node.matches?.(anyLazyImageSelector) ||
            node.querySelector(anyLazyImageSelector)
          )
        ))
      ));

      if (!shouldRefreshLazyImages || mutationTimeout) return;

      mutationTimeout = setTimeout(() => {
        initEnhancedLazyLoading();
        mutationTimeout = null;
      }, 500);
    });

    lazyLoadObserver.observe(dynamicContent, { childList: true, subtree: true });
  }

  /**
   * Scroll Reveal Color Animation
   * Transitions elements from grayscale to color when they enter the viewport
   */
  const revealColorImages = document.querySelectorAll('[data-reveal-color]');
  if ('IntersectionObserver' in window && revealColorImages.length > 0) {
    const revealObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          // Add a small delay for a more natural feel
          setTimeout(() => {
            entry.target.classList.remove('grayscale');
          }, 200);
          observer.unobserve(entry.target);
        }
      });
    }, {
      rootMargin: '-50px', // Trigger slightly after entering viewport
      threshold: 0.2 // Trigger when 20% visible
    });

    revealColorImages.forEach(img => revealObserver.observe(img));
  } else if (revealColorImages.length > 0) {
    revealColorImages.forEach(img => img.classList.remove('grayscale'));
  }

  /**
   * Sticky CTA Bar Scroll Logic (Performance Optimized)
   * - Uses one-time trigger to avoid continuous scroll processing
   * - Removes listener after activation for zero ongoing cost
   */
  const stickyCta = document.getElementById('sticky-cta');
  if (stickyCta) {
    // Check if already scrolled on page load
    if (window.scrollY > 300) {
      stickyCta.classList.remove('translate-y-full');
      stickyCta.classList.add('translate-y-0');
    } else {
      // Only add listener if not already past threshold
      const showStickyCta = () => {
        if (window.scrollY > 300) {
          stickyCta.classList.remove('translate-y-full');
          stickyCta.classList.add('translate-y-0');
          // Remove listener after showing - no ongoing cost
          window.removeEventListener('scroll', showStickyCta);
        }
      };
      window.addEventListener('scroll', showStickyCta, { passive: true });
    }
  }
});


