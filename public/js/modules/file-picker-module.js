(function () {
    'use strict';

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function parseExistingFiles(input) {
        try {
            const files = JSON.parse(input.dataset.existingFiles || '[]');
            return Array.isArray(files) ? files : [];
        } catch {
            return [];
        }
    }

    function getFileKey(file) {
        return `${file.name}__${file.size}__${file.lastModified}`;
    }

    function updateNativeInputFiles(input, files) {
        const dataTransfer = new DataTransfer();
        files.forEach((file) => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
    }

    function ensureInputId(input, sequence) {
        if (input.id) {
            return input.id;
        }

        const generatedId = `file-picker-${sequence.value++}`;
        input.id = generatedId;
        return generatedId;
    }

    class FilePickerModule {
        constructor(options = {}) {
            this.inputSelector = options.inputSelector || '.js-file-input';
            this.previewButtonSelector = options.previewButtonSelector || '.file-preview-item button';
            this.selectedFilesByInput = new Map();
            this.removedExistingPathsByInput = new Map();
            this.idSequence = { value: 1 };
            this.isPreviewClickBound = false;
        }

        init(root = document) {
            const inputs = root.querySelectorAll(this.inputSelector);
            inputs.forEach((input) => this.bindInput(input));
            this.bindPreviewClickHandler();
        }

        bindInput(input) {
            if (!input || input.dataset.filePickerBound === '1') {
                return;
            }

            const inputId = ensureInputId(input, this.idSequence);
            this.selectedFilesByInput.set(inputId, Array.from(input.files || []));
            this.ensureRemovedPaths(inputId);

            input.addEventListener('change', () => {
                this.handleInputChange(input);
            });

            input.dataset.filePickerBound = '1';
            this.renderInputPreview(input);
        }

        handleInputChange(input) {
            const inputId = input.id;
            const incomingFiles = Array.from(input.files || []);
            const currentFiles = this.selectedFilesByInput.get(inputId) || [];
            const mergedFiles = [...currentFiles, ...incomingFiles];
            const uniqueFiles = new Map();

            mergedFiles.forEach((file) => uniqueFiles.set(getFileKey(file), file));

            let orderedFiles = Array.from(uniqueFiles.values())
                .sort((a, b) => a.name.localeCompare(b.name));

            const maxFiles = Number.parseInt(input.dataset.maxFiles || '0', 10);
            if (maxFiles > 0 && orderedFiles.length > maxFiles) {
                orderedFiles = orderedFiles.slice(0, maxFiles);
            }

            this.selectedFilesByInput.set(inputId, orderedFiles);
            updateNativeInputFiles(input, orderedFiles);
            this.renderInputPreview(input);
        }

        clearInput(input) {
            if (!input?.id) {
                return;
            }

            this.selectedFilesByInput.set(input.id, []);
            this.ensureRemovedPaths(input.id).clear();
            updateNativeInputFiles(input, []);
            this.renderInputPreview(input);
        }

        bindPreviewClickHandler() {
            if (this.isPreviewClickBound) {
                return;
            }

            document.addEventListener('click', (event) => {
                const button = event.target.closest(this.previewButtonSelector);
                if (!button) {
                    return;
                }

                const inputId = button.dataset.inputId || '';
                if (!inputId) {
                    return;
                }

                const input = document.getElementById(inputId);
                if (!input) {
                    return;
                }

                const existingPath = button.dataset.existingPath;
                if (existingPath !== undefined) {
                    this.removeExistingFile(input, existingPath);
                    return;
                }

                const fileIndex = Number.parseInt(button.dataset.fileIndex || '-1', 10);
                if (fileIndex < 0) {
                    return;
                }

                this.removeSelectedFile(input, fileIndex);
            });

            this.isPreviewClickBound = true;
        }

        removeExistingFile(input, encodedPath) {
            const decodedPath = decodeURIComponent(encodedPath);
            if (!decodedPath) {
                return;
            }

            const removedPaths = this.ensureRemovedPaths(input.id);
            removedPaths.add(decodedPath);
            this.renderInputPreview(input);
        }

        removeSelectedFile(input, fileIndex) {
            const inputId = input.id;
            const currentFiles = [...(this.selectedFilesByInput.get(inputId) || [])];

            currentFiles.splice(fileIndex, 1);
            this.selectedFilesByInput.set(inputId, currentFiles);
            updateNativeInputFiles(input, currentFiles);
            this.renderInputPreview(input);
        }

        ensureRemovedPaths(inputId) {
            if (!this.removedExistingPathsByInput.has(inputId)) {
                this.removedExistingPathsByInput.set(inputId, new Set());
            }

            return this.removedExistingPathsByInput.get(inputId);
        }

        getVisibleExistingFiles(input) {
            const removedPaths = this.ensureRemovedPaths(input.id);
            return parseExistingFiles(input).filter((file) => {
                const path = file?.path || '';
                return !path || !removedPaths.has(path);
            });
        }

        syncRemovedPathInputs(input) {
            const removeInputName = input.dataset.removeName;
            const container = input.closest('.upload-box')?.querySelector('.js-existing-remove-inputs');

            if (!removeInputName || !container) {
                return;
            }

            const removedPaths = this.ensureRemovedPaths(input.id);
            container.innerHTML = '';

            removedPaths.forEach((path) => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = removeInputName;
                hiddenInput.value = path;
                container.appendChild(hiddenInput);
            });
        }

        renderInputPreview(input) {
            const label = document.getElementById(input.dataset.fileLabelId || '');
            const preview = document.getElementById(input.dataset.filePreviewId || '');
            const selectedFiles = this.selectedFilesByInput.get(input.id) || [];
            const existingFiles = this.getVisibleExistingFiles(input);

            if (label) {
                if (selectedFiles.length > 0) {
                    label.textContent = `${selectedFiles.length} file mới đã chọn`;
                } else if (existingFiles.length > 0) {
                    label.textContent = `${existingFiles.length} file hiện có`;
                } else {
                    label.textContent = 'Chưa chọn file';
                }
            }

            if (preview) {
                const existingHtml = existingFiles.map((file) => {
                    const encodedPath = encodeURIComponent(file.path || '');
                    const canRemove = Boolean(file.path);

                    return `
                        <div class="file-preview-item existing-file">
                            <a class="name text-decoration-none text-dark" href="${escapeHtml(file.url || '#')}" target="_blank" title="${escapeHtml(file.name || 'File')}">
                                <i class="fas fa-file me-1 text-secondary"></i>${escapeHtml(file.name || 'File')}
                            </a>
                            <button type="button" data-input-id="${input.id}" data-existing-path="${encodedPath}" title="Xóa file hiện có" ${(input.disabled || !canRemove) ? 'disabled' : ''}>&times;</button>
                        </div>
                    `;
                }).join('');

                const selectedHtml = selectedFiles.map((file, index) => `
                    <div class="file-preview-item">
                        <span class="name" title="${escapeHtml(file.name)}"><i class="fas fa-file me-1 text-secondary"></i>${escapeHtml(file.name)}</span>
                        <button type="button" data-input-id="${input.id}" data-file-index="${index}" title="Xóa file">&times;</button>
                    </div>
                `).join('');

                preview.innerHTML = `${existingHtml}${selectedHtml}`;
            }

            this.syncRemovedPathInputs(input);
        }
    }

    globalThis.AdminFilePicker = {
        create(options = {}) {
            return new FilePickerModule(options);
        },
    };
})();
