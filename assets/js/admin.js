(function() {
    const singularInput = document.getElementById('singular_label');
    const slugInput = document.getElementById('slug');
    const taxonomySingular = document.getElementById('taxonomy_singular_label');
    const taxonomySlug = document.getElementById('taxonomy_slug');

    function generateSlug(value) {
        return value
            .toLowerCase()
            .replace(/[^a-z0-9\s_-]/g, '')
            .trim()
            .replace(/\s+/g, '-');
    }

    function bindSlugAutoFill(source, target) {
        if (!source || !target) return;
        source.addEventListener('input', function() {
            if (target.dataset.touched === 'true') {
                return;
            }
            target.value = generateSlug(source.value);
        });
        target.addEventListener('input', function() {
            target.dataset.touched = 'true';
        });
    }

    bindSlugAutoFill(singularInput, slugInput);
    bindSlugAutoFill(taxonomySingular, taxonomySlug);

    // Field builder
    const addFieldButton = document.getElementById('post-type-studio-add-field');
    const fieldContainer = document.getElementById('post-type-studio-fields-container');
    const fieldTemplate = document.getElementById('post-type-studio-field-template');
    const emptyState = document.getElementById('post-type-studio-empty-state');
    let fieldIndex = 0;

    if (fieldContainer && fieldContainer.dataset.fieldCount) {
        const parsed = parseInt(fieldContainer.dataset.fieldCount, 10);
        if (!Number.isNaN(parsed)) {
            fieldIndex = parsed;
        }
    }

    function refreshEmptyState() {
        if (!emptyState) return;
        const hasFields = fieldContainer && fieldContainer.querySelector('.post-type-studio__field-row');
        emptyState.style.display = hasFields ? 'none' : 'block';
    }

    function toggleChoices(selectEl) {
        const fieldRow = selectEl.closest('.post-type-studio__field-row');
        const choices = fieldRow.querySelector('.post-type-studio__choices');
        if (!choices) return;
        choices.hidden = selectEl.value !== 'select';
    }

    function addField() {
        if (!fieldTemplate || !fieldContainer) return;
        const fragment = fieldTemplate.content.cloneNode(true);
        const html = fragment.querySelectorAll('[name]');
        html.forEach(function(input) {
            input.name = input.name.replace('__INDEX__', fieldIndex.toString());
        });

        const select = fragment.querySelector('select');
        if (select) {
            select.addEventListener('change', function() {
                toggleChoices(select);
            });
        }

        const removeBtn = fragment.querySelector('.post-type-studio__remove-field');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                removeBtn.closest('.post-type-studio__field-row').remove();
                refreshEmptyState();
            });
        }

        fieldContainer.appendChild(fragment);
        fieldIndex += 1;
        refreshEmptyState();
    }

    if (addFieldButton) {
        addFieldButton.addEventListener('click', addField);
    }

    document.querySelectorAll('.post-type-studio__remove-field').forEach(function(button) {
        button.addEventListener('click', function() {
            button.closest('.post-type-studio__field-row').remove();
            refreshEmptyState();
        });
    });

    document.addEventListener('change', function(event) {
        const target = event.target;
        if (target.matches('.post-type-studio__field-row select')) {
            toggleChoices(target);
        }
    });

    const confirmModal = document.getElementById('post-type-studio-confirm');
    const confirmMessage = document.getElementById('post-type-studio-confirm__message');
    const confirmAccept = document.getElementById('post-type-studio-confirm__accept');
    const confirmCancel = document.getElementById('post-type-studio-confirm__cancel');
    const confirmOverlay = document.querySelector('#post-type-studio-confirm .post-type-studio__modal__overlay');
    let pendingForm = null;
    let lastFocusedElement = null;

    function closeConfirmDialog() {
        if (!confirmModal) return;
        confirmModal.classList.remove('is-visible');
        confirmModal.setAttribute('aria-hidden', 'true');
        confirmModal.hidden = true;
        if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
            lastFocusedElement.focus();
        }
        pendingForm = null;
    }

    function openConfirmDialog(message, form) {
        if (!confirmModal || !confirmMessage || !confirmAccept) {
            return window.confirm(message);
        }

        lastFocusedElement = document.activeElement;
        confirmMessage.textContent = message;
        confirmModal.hidden = false;
        confirmModal.classList.add('is-visible');
        confirmModal.setAttribute('aria-hidden', 'false');
        pendingForm = form;
        confirmAccept.focus();
        return null;
    }

    if (confirmCancel) {
        confirmCancel.addEventListener('click', function() {
            closeConfirmDialog();
        });
    }

    if (confirmAccept) {
        confirmAccept.addEventListener('click', function() {
            if (pendingForm) {
                pendingForm.submit();
            }
            closeConfirmDialog();
        });
    }

    if (confirmOverlay) {
        confirmOverlay.addEventListener('click', function() {
            closeConfirmDialog();
        });
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeConfirmDialog();
        }
    });

    document.addEventListener('submit', function(event) {
        const target = event.target;
        if (target.classList && target.classList.contains('post-type-studio__delete-form')) {
            const message = target.dataset.confirmMessage || 'Are you sure?';
            const shouldSubmit = openConfirmDialog(message, target);
            if (shouldSubmit === false) {
                event.preventDefault();
                return;
            }
            if (shouldSubmit === null) {
                event.preventDefault();
            }
        }
    });

    refreshEmptyState();
})();
