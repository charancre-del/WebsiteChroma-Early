/**
 * Early Start Pro PDF Viewer - Paginated Edition (Hardened)
 * 
 * Handles lazy-loading of PDF.js and rendering of PDF documents inside a custom modal
 * with a classic single-page paginated experience.
 */

document.addEventListener('DOMContentLoaded', function () {
    const debugLog = window.chromaPdfConfig && window.chromaPdfConfig.debug ? console.log.bind(console) : function () {};

    // Viewer State
    const viewerState = {
        pdfDoc: null,
        pageNum: 1,
        pageRendering: false,
        pageNumPending: null,
        scale: 1.5, // Base scale, will be responsive
        canvas: null,
        ctx: null,
        loading: false
    };

    // Cache DOM Elements
    const modal = document.getElementById('chroma-pdf-modal');
    if (!modal) return;

    const canvasContainer = document.getElementById('chroma-pdf-canvas-container');
    const loadingSpinner = document.getElementById('chroma-pdf-loader');
    const closeBtn = document.getElementById('chroma-pdf-close');
    const prevBtn = document.getElementById('chroma-pdf-prev');
    const nextBtn = document.getElementById('chroma-pdf-next');
    const pageNumSpan = document.getElementById('chroma-pdf-page-num');
    const pageCountSpan = document.getElementById('chroma-pdf-page-count');
    const downloadBtn = document.getElementById('chroma-pdf-download');
    const titleSpan = document.getElementById('chroma-pdf-title');
    const backdrop = document.getElementById('chroma-pdf-backdrop');

    function setLoadingError() {
        if (!loadingSpinner) return;

        loadingSpinner.textContent = '';

        const errorBox = document.createElement('div');
        errorBox.className = 'text-white text-center p-10';

        const icon = document.createElement('i');
        icon.className = 'fa-solid fa-circle-exclamation text-4xl mb-4 text-chroma-red';
        icon.setAttribute('aria-hidden', 'true');

        const message = document.createElement('span');
        message.textContent = 'Failed to load document.';

        const guidance = document.createElement('span');
        guidance.textContent = 'Please use the download button above.';

        errorBox.appendChild(icon);
        errorBox.appendChild(document.createElement('br'));
        errorBox.appendChild(message);
        errorBox.appendChild(document.createElement('br'));
        errorBox.appendChild(guidance);
        loadingSpinner.appendChild(errorBox);
    }

    // Discovery helpers
    function ensureCanvas() {
        if (!viewerState.canvas) {
            viewerState.canvas = document.getElementById('chroma-pdf-canvas');
            if (viewerState.canvas) {
                viewerState.ctx = viewerState.canvas.getContext('2d');
                debugLog('PDF Viewer: Canvas and Context discovered');
            }
        }
        return !!(viewerState.canvas && viewerState.ctx);
    }

    /**
     * Get page info from document, resize canvas accordingly, and render page.
     * @param num Page number.
     */
    function renderPage(num) {
        if (!viewerState.pdfDoc) return;
        if (!ensureCanvas()) {
            console.error('PDF Viewer: Fatal - Canvas or Context missing from DOM');
            return;
        }

        viewerState.pageRendering = true;
        debugLog('PDF Viewer: Rendering page ' + num);

        // Fetch page
        viewerState.pdfDoc.getPage(num).then(function (page) {
            // Measure container for scale
            let containerWidth = canvasContainer.clientWidth;
            if (containerWidth <= 0) {
                // If container is not yet painted, fallback to modal or window
                containerWidth = modal.clientWidth || window.innerWidth || 800;
                console.warn('PDF Viewer: Container width 0, using fallback: ' + containerWidth);
            }

            const unscaledViewport = page.getViewport({ scale: 1 });
            let desiredScale = (containerWidth - 60) / unscaledViewport.width;

            // Limit max scale to keep quality, but ensure mobile readability
            if (desiredScale > 2.0) desiredScale = 2.0;
            if (desiredScale < 0.6) desiredScale = 0.6; // Allow more shrink for phone view

            const viewport = page.getViewport({ scale: desiredScale });
            debugLog(`PDF Viewer: Scaling to ${desiredScale.toFixed(2)}. Target res: ${viewport.width}x${viewport.height}`);

            viewerState.canvas.height = viewport.height;
            viewerState.canvas.width = viewport.width;

            // Render task
            const renderContext = {
                canvasContext: viewerState.ctx,
                viewport: viewport
            };

            const renderTask = page.render(renderContext);

            // Wait for render to finish
            renderTask.promise.then(function () {
                viewerState.pageRendering = false;
                debugLog('PDF Viewer: Page ' + num + ' rendered successfully');

                // Hide loader
                if (loadingSpinner) loadingSpinner.style.display = 'none';

                if (viewerState.pageNumPending !== null) {
                    // New page rendering is pending
                    renderPage(viewerState.pageNumPending);
                    viewerState.pageNumPending = null;
                }
            }).catch(err => {
                console.error('PDF Viewer: Render task failed', err);
                viewerState.pageRendering = false;
            });
        }).catch(err => {
            console.error('PDF Viewer: Could not get page ' + num, err);
            viewerState.pageRendering = false;
        });

        // Update page counters
        if (pageNumSpan) pageNumSpan.textContent = num;
        viewerState.pageNum = num;

        // Update button states
        updateNavButtons();
    }

    function updateNavButtons() {
        if (!viewerState.pdfDoc) {
            if (prevBtn) prevBtn.disabled = true;
            if (nextBtn) nextBtn.disabled = true;
            return;
        }

        if (prevBtn) prevBtn.disabled = viewerState.pageNum <= 1;
        if (nextBtn) nextBtn.disabled = viewerState.pageNum >= viewerState.pdfDoc.numPages;
    }

    /**
     * If another page rendering in progress, waits until the rendering is
     * finished. Otherwise, executes rendering immediately.
     */
    function queueRenderPage(num) {
        if (viewerState.pageRendering) {
            viewerState.pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    /**
     * Displays previous page.
     */
    function onPrevPage() {
        if (viewerState.pageNum <= 1) return;
        viewerState.pageNum--;
        queueRenderPage(viewerState.pageNum);
    }

    /**
     * Displays next page.
     */
    function onNextPage() {
        if (!viewerState.pdfDoc) return;
        if (viewerState.pageNum >= viewerState.pdfDoc.numPages) return;
        viewerState.pageNum++;
        queueRenderPage(viewerState.pageNum);
    }

    /**
     * Asynchronously downloads PDF.js
     */
    function loadPdfLibrary(callback) {
        if (window.pdfjsLib) {
            callback();
            return;
        }

        if (!window.chromaPdfConfig || !window.chromaPdfConfig.pdfJsUrl || !window.chromaPdfConfig.pdfWorkerUrl) {
            setLoadingError();
            return;
        }

        const script = document.createElement('script');
        script.src = window.chromaPdfConfig.pdfJsUrl;
        script.onload = function () {
            window.pdfjsLib.GlobalWorkerOptions.workerSrc = window.chromaPdfConfig.pdfWorkerUrl;
            callback();
        };
        script.onerror = setLoadingError;
        document.body.appendChild(script);
    }

    /**
     * Opens the viewer for a specific URL
     */
    function openViewer(url, title) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        if (loadingSpinner) loadingSpinner.style.display = 'flex';

        // Reset State
        viewerState.pageNum = 1;
        viewerState.pdfDoc = null;
        viewerState.pageRendering = false;
        viewerState.pageNumPending = null;
        updateNavButtons();
        if (pageNumSpan) pageNumSpan.textContent = '1';
        if (pageCountSpan) pageCountSpan.textContent = '--';

        // Clear canvas while loading new doc
        if (ensureCanvas()) {
            viewerState.ctx.clearRect(0, 0, viewerState.canvas.width, viewerState.canvas.height);
        }

        if (titleSpan) titleSpan.textContent = title || 'Document';
        if (downloadBtn) {
            downloadBtn.href = url;
            downloadBtn.removeAttribute('aria-disabled');
            downloadBtn.removeAttribute('tabindex');
        }

        loadPdfLibrary(function () {
            window.pdfjsLib.getDocument(url).promise.then(function (pdfDoc_) {
                viewerState.pdfDoc = pdfDoc_;
                if (pageCountSpan) pageCountSpan.textContent = pdfDoc_.numPages;

                // Render first page (with a small delay for layout stabilization)
                setTimeout(() => renderPage(viewerState.pageNum), 50);
            }).catch(err => {
                console.error('PDF Error:', err);
                setLoadingError();
            });
        });
    }

    function closeViewer() {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        if (downloadBtn) {
            downloadBtn.removeAttribute('href');
            downloadBtn.setAttribute('aria-disabled', 'true');
            downloadBtn.setAttribute('tabindex', '-1');
        }
    }

    // Event Listeners
    if (prevBtn) prevBtn.addEventListener('click', onPrevPage);
    if (nextBtn) nextBtn.addEventListener('click', onNextPage);
    if (closeBtn) closeBtn.addEventListener('click', closeViewer);
    if (backdrop) backdrop.addEventListener('click', closeViewer);
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function (e) {
            if (!downloadBtn.href || downloadBtn.getAttribute('aria-disabled') === 'true') {
                e.preventDefault();
            }
        });
    }

    // Keyboard support
    document.addEventListener('keydown', function (e) {
        if (modal.classList.contains('hidden')) return;
        if (e.key === 'Escape') closeViewer();
        if (e.key === 'ArrowLeft') onPrevPage();
        if (e.key === 'ArrowRight') onNextPage();
    });

    // Handle Window Resize (Debounced re-render)
    let resizeTimeout;
    window.addEventListener('resize', function () {
        if (modal.classList.contains('hidden') || !viewerState.pdfDoc) return;

        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function () {
            renderPage(viewerState.pageNum);
        }, 200);
    });

    window.chromaOpenPdf = openViewer;

    function attachTriggers() {
        document.addEventListener('click', function (e) {
            const trigger = e.target.closest('.chroma-pdf-trigger');
            if (trigger) {
                e.preventDefault();
                const url = trigger.getAttribute('data-pdf-url');
                const title = trigger.getAttribute('data-pdf-title');
                if (url) openViewer(url, title);
            }
        });
    }

    attachTriggers();
});


