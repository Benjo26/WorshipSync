<script>
    (() => {
        const createLiveSetItem = (song) => {
            const item = document.createElement('li');
            item.className = 'live-set-item';
            item.dataset.songId = String(song.id);
            item.innerHTML = `
                <input type="hidden" name="songs[]" value="${song.id}">
                <span class="live-set-order-pill" data-live-position></span>
                <div class="live-set-item-copy">
                    <strong>${song.title}</strong>
                    <small>${song.artist} · ${song.default_key} · ${song.bpm} BPM</small>
                </div>
                <div class="live-set-item-actions">
                    <button type="button" class="button subtle" data-live-up>Up</button>
                    <button type="button" class="button subtle" data-live-down>Down</button>
                    <button type="button" class="button danger-outline" data-live-remove>Remove</button>
                </div>
            `;

            return item;
        };

        const bootLiveSetBuilder = (root) => {
            const selection = root.querySelector('[data-live-selection]');
            const emptyState = root.querySelector('[data-live-empty]');
            const count = root.querySelector('[data-live-count]');

            if (!selection || !emptyState || !count) {
                return;
            }

            const sync = () => {
                const items = Array.from(selection.querySelectorAll('[data-song-id]'));

                items.forEach((item, index) => {
                    const position = item.querySelector('[data-live-position]');

                    if (position) {
                        position.textContent = String(index + 1).padStart(2, '0');
                    }
                });

                count.textContent = `${items.length} selected`;
                emptyState.classList.toggle('is-hidden', items.length > 0);

                root.querySelectorAll('[data-live-add]').forEach((button) => {
                    const song = JSON.parse(button.dataset.song || '{}');
                    const exists = selection.querySelector(`[data-song-id="${song.id}"]`);
                    button.disabled = Boolean(exists);
                    button.textContent = exists ? 'Added' : 'Add';
                });
            };

            root.addEventListener('click', (event) => {
                const addButton = event.target.closest('[data-live-add]');

                if (addButton) {
                    const song = JSON.parse(addButton.dataset.song || '{}');

                    if (!selection.querySelector(`[data-song-id="${song.id}"]`)) {
                        selection.appendChild(createLiveSetItem(song));
                        sync();
                    }

                    return;
                }

                const item = event.target.closest('.live-set-item');

                if (!item) {
                    return;
                }

                if (event.target.closest('[data-live-remove]')) {
                    item.remove();
                    sync();
                    return;
                }

                if (event.target.closest('[data-live-up]')) {
                    const previous = item.previousElementSibling;

                    if (previous) {
                        selection.insertBefore(item, previous);
                        sync();
                    }

                    return;
                }

                if (event.target.closest('[data-live-down]')) {
                    const next = item.nextElementSibling;

                    if (next) {
                        selection.insertBefore(next, item);
                        sync();
                    }
                }
            });

            sync();
        };

        document.querySelectorAll('[data-live-set-builder]').forEach(bootLiveSetBuilder);
    })();
</script>
