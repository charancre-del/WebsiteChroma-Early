/**
 * Chroma LLM Admin JavaScript
 */
jQuery(function ($) {
    'use strict';

    function escapeHtml(value) {
        var text = value === undefined || value === null ? '' : String(value);

        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function boundedNumber(value, fallback, min, max) {
        var number = Number(value);

        if (!Number.isFinite(number)) {
            number = fallback;
        }

        return Math.min(max, Math.max(min, number));
    }

    // Schema Preview Generator
    window.ChromaSchemaPreview = {
        generate: function (schema) {
            if (!schema || !schema['@type']) return '';

            var type = schema['@type'];
            var html = '<div class="chroma-schema-preview"><div class="serp-result">';

            // Title
            html += '<div class="serp-title">' + escapeHtml(schema.name || 'Untitled') + '</div>';

            // URL
            html += '<div class="serp-url">' + escapeHtml(schema.url || window.location.href) + '</div>';

            // Description
            if (schema.description) {
                html += '<div class="serp-description">' + escapeHtml(String(schema.description).substring(0, 160)) + '...</div>';
            }

            // Rating
            if (schema.aggregateRating) {
                var rating = schema.aggregateRating;
                var ratingValue = boundedNumber(rating.ratingValue, 0, 0, 5);
                var reviewCount = boundedNumber(rating.reviewCount, 0, 0, 999999);
                var roundedRating = Math.round(ratingValue);
                var stars = '★'.repeat(roundedRating) + '☆'.repeat(5 - roundedRating);
                html += '<div class="serp-rating">' + stars + ' ' + escapeHtml(ratingValue.toFixed(1)) + ' (' + escapeHtml(Math.round(reviewCount)) + ' reviews)</div>';
            }

            // Hours
            if (type === 'LocalBusiness' || type === 'ChildCare') {
                html += '<div class="serp-hours">Hours: Mon-Fri 6am-6pm</div>';
            }

            html += '</div></div>';
            return html;
        }
    };

    // Confidence Bar Renderer
    window.ChromaConfidence = {
        render: function (score) {
            var percent = Math.round(boundedNumber(score, 0, 0, 1) * 100);
            var level = percent >= 80 ? 'high' : (percent >= 50 ? 'medium' : 'low');

            return '<div class="confidence-bar">' +
                '<div class="fill ' + level + '" style="width: ' + percent + '%;"></div>' +
                '</div>' + percent + '%';
        }
    };

    // Bulk Operations Handler
    var BulkOps = {
        init: function () {
            $('#select-all-gaps').off('change.chromaLLMBulk').on('change.chromaLLMBulk', function () {
                $('.gap-checkbox').prop('checked', $(this).is(':checked'));
            });

            if ($('#chroma-generate-selected').length && !$('#chroma-reset-schema').length) {
                $('<button id="chroma-reset-schema" class="button button-secondary" style="margin-left: 10px; color: #d63638; border-color: #d63638;">Reset Schema</button>').insertAfter('#chroma-generate-selected');
                $('<button id="chroma-reset-faq" class="button button-secondary" style="margin-left: 10px; color: #d63638; border-color: #d63638;">Reset FAQs</button>').insertAfter('#chroma-reset-schema');
            }

            $('#chroma-generate-selected')
                .off('click.chromaLLMBulk')
                .on('click.chromaLLMBulk', this.startBulk.bind(this));
            $('#chroma-cancel-bulk')
                .off('click.chromaLLMBulk')
                .on('click.chromaLLMBulk', this.cancelBulk.bind(this));
            $('#chroma-reset-schema')
                .off('click.chromaLLMBulk')
                .on('click.chromaLLMBulk', function (e) {
                    e.preventDefault();
                    BulkOps.resetBulk('schema');
                });
            $('#chroma-reset-faq')
                .off('click.chromaLLMBulk')
                .on('click.chromaLLMBulk', function (e) {
                    e.preventDefault();
                    BulkOps.resetBulk('faq');
                });

            // Poll status if in progress
            if ($('.chroma-bulk-status').length) {
                this.pollStatus();
            }
        },

        startBulk: function () {
            var selected = $('.gap-checkbox:checked').map(function () {
                return $(this).val();
            }).get();

            if (!selected.length) {
                alert('Please select at least one post');
                return;
            }

            $.post(chromaLLM.ajaxUrl, {
                action: 'earlystart_bulk_generate_start',
                nonce: chromaLLM.nonce,
                post_ids: selected,
                type: 'schema'
            }, function (response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error: ' + response.data.message);
                }
            });
        },

        cancelBulk: function () {
            $.post(chromaLLM.ajaxUrl, {
                action: 'earlystart_bulk_generate_cancel',
                nonce: chromaLLM.nonce
            }, function () {
                location.reload();
            });
        },

        resetBulk: function (type) {
            var selected = $('.gap-checkbox:checked').map(function () {
                return $(this).val();
            }).get();
            var resetAll = false;

            if (!selected.length) {
                if (!confirm('No posts selected. Reset all ' + type + ' data across the entire site? This cannot be undone.')) {
                    return;
                }
                resetAll = true;
            } else if (!confirm('Reset ' + type + ' for ' + selected.length + ' selected item(s)?')) {
                return;
            }

            $.post(chromaLLM.ajaxUrl, {
                action: type === 'faq' ? 'earlystart_bulk_reset_faq' : 'earlystart_bulk_reset_schema',
                nonce: chromaLLM.nonce,
                post_ids: selected,
                reset_all: resetAll
            }, function (response) {
                if (response.success) {
                    alert(response.data.message);
                    location.reload();
                } else {
                    alert('Error: ' + (response.data.message || 'Unknown error'));
                }
            }).fail(function () {
                alert('Request failed. Please try again.');
            });
        },

        pollStatus: function () {
            var self = this;

            $.post(chromaLLM.ajaxUrl, {
                action: 'earlystart_bulk_generate_status',
                nonce: chromaLLM.nonce
            }, function (response) {
                if (response.success && response.data.status.in_progress) {
                    var s = response.data.status;
                    var percent = Math.round((s.completed / s.total) * 100);

                    $('.chroma-progress .bar').css('width', percent + '%');
                    $('.chroma-progress-text').text(s.completed + ' / ' + s.total);

                    setTimeout(function () { self.pollStatus(); }, 3000);
                } else {
                    location.reload();
                }
            });
        }
    };

    // Review Queue Handler
    var ReviewQueue = {
        init: function () {
            $('.approve-btn').on('click', function () {
                var postId = $(this).data('post');
                var $row = $(this).closest('tr');

                $.post(chromaLLM.ajaxUrl, {
                    action: 'earlystart_review_schema',
                    nonce: chromaLLM.nonce,
                    post_id: postId,
                    review_action: 'approve'
                }, function (response) {
                    if (response.success) {
                        $row.fadeOut();
                    }
                });
            });
        }
    };

    // GMB Sync Handler
    var GMBSync = {
        init: function () {
            $('#chroma-sync-gmb').on('click', function () {
                var postId = $(this).data('post');
                var $btn = $(this);

                $btn.prop('disabled', true).text('Syncing...');

                $.post(chromaLLM.ajaxUrl, {
                    action: 'earlystart_sync_gmb_data',
                    nonce: chromaLLM.nonce,
                    post_id: postId
                }, function (response) {
                    if (response.success) {
                        $btn.text('Synced!');
                        setTimeout(function () { location.reload(); }, 1000);
                    } else {
                        $btn.text('Error').prop('disabled', false);
                        alert(response.data.message);
                    }
                });
            });
        }
    };

    // Initialize all
    BulkOps.init();
    ReviewQueue.init();
    GMBSync.init();
});
