import Alpine from 'alpinejs';
import Quill from 'quill';

window.Alpine = Alpine;

const TR_MAP = { ç: 'c', ğ: 'g', ı: 'i', ö: 'o', ş: 's', ü: 'u', Ç: 'c', Ğ: 'g', İ: 'i', Ö: 'o', Ş: 's', Ü: 'u' };

export function slugify(value) {
    return String(value)
        .replace(/[çğıöşüÇĞİÖŞÜ]/g, (ch) => TR_MAP[ch])
        .toLowerCase()
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

/* Admin shell: sidebar toggle, flash auto-hide */
Alpine.data('adminShell', () => ({
    sidebarOpen: false,
    userMenu: false,
}));

/* Title -> slug helper */
Alpine.data('slugField', (initialSlug = '', locked = false) => ({
    slug: initialSlug,
    locked: locked || initialSlug !== '',
    update(title) {
        if (!this.locked) {
            this.slug = slugify(title);
        }
    },
}));

/* Rich text editor bound to a hidden textarea */
Alpine.data('richEditor', (name) => ({
    init() {
        const textarea = this.$refs.input;
        const editor = new Quill(this.$refs.editor, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ header: [2, 3, 4, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ align: [] }],
                    ['blockquote', 'link', 'image'],
                    ['clean'],
                ],
            },
        });

        const toolbar = editor.getModule('toolbar');
        toolbar.addHandler('image', () => {
            window.dispatchEvent(
                new CustomEvent('open-media-picker', {
                    detail: {
                        onSelect: (media) => {
                            const range = editor.getSelection(true);
                            editor.insertEmbed(range.index, 'image', media.url, 'user');
                        },
                    },
                })
            );
        });

        editor.clipboard.dangerouslyPasteHTML(textarea.value || '');
        editor.on('text-change', () => {
            textarea.value = editor.getSemanticHTML();
        });
    },
}));

/* Media picker modal (single instance per page) */
Alpine.data('mediaPicker', () => ({
    open: false,
    loading: false,
    items: [],
    page: 1,
    lastPage: 1,
    search: '',
    onSelect: null,
    init() {
        window.addEventListener('open-media-picker', (e) => {
            this.onSelect = e.detail?.onSelect ?? null;
            this.open = true;
            this.page = 1;
            this.load();
        });
    },
    async load() {
        this.loading = true;
        try {
            const params = new URLSearchParams({ page: this.page, q: this.search });
            const res = await fetch(`${window.ADMIN_MEDIA_URL}?${params}`, { headers: { Accept: 'application/json' } });
            const data = await res.json();
            this.items = data.data;
            this.lastPage = data.last_page;
        } finally {
            this.loading = false;
        }
    },
    async upload(event) {
        const files = event.target.files;
        if (!files.length) return;
        const form = new FormData();
        for (const f of files) form.append('files[]', f);
        this.loading = true;
        try {
            const res = await fetch(window.ADMIN_MEDIA_URL, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, Accept: 'application/json' },
                body: form,
            });
            if (!res.ok) {
                const err = await res.json().catch(() => ({}));
                alert(err.message || 'Yükleme başarısız.');
            }
            this.page = 1;
            await this.load();
        } finally {
            this.loading = false;
            event.target.value = '';
        }
    },
    select(item) {
        if (this.onSelect) this.onSelect(item);
        this.open = false;
    },
}));

/* Image field: hidden path input + preview + pick/clear buttons */
Alpine.data('imageField', (initialPath = '', initialUrl = '') => ({
    path: initialPath,
    url: initialUrl,
    pick() {
        window.dispatchEvent(
            new CustomEvent('open-media-picker', {
                detail: {
                    onSelect: (media) => {
                        this.path = media.path;
                        this.url = media.url;
                    },
                },
            })
        );
    },
    clear() {
        this.path = '';
        this.url = '';
    },
}));

/* Gallery field: JSON array of paths */
Alpine.data('galleryField', (initial = []) => ({
    items: initial,
    add() {
        window.dispatchEvent(
            new CustomEvent('open-media-picker', {
                detail: { onSelect: (media) => this.items.push({ path: media.path, url: media.url }) },
            })
        );
    },
    remove(index) {
        this.items.splice(index, 1);
    },
}));

/* Key/value repeater (product specs, module features) */
Alpine.data('repeaterField', (initial = []) => ({
    rows: initial.length ? initial : [],
    add() {
        this.rows.push({ label: '', value: '' });
    },
    remove(index) {
        this.rows.splice(index, 1);
    },
}));

/* Confirm helper used by delete forms */
window.confirmDelete = (message = 'Bu kaydı silmek istediğinize emin misiniz?') => window.confirm(message);


/* Drag & drop ordering for table rows (services) */
Alpine.data('sortableList', (url) => ({
    dragging: null,
    saved: false,
    dragStart(e) {
        this.dragging = e.currentTarget;
        e.dataTransfer.effectAllowed = 'move';
    },
    dragOver(e) {
        const target = e.currentTarget;
        if (!this.dragging || target === this.dragging) return;
        const rect = target.getBoundingClientRect();
        const after = e.clientY - rect.top > rect.height / 2;
        target.parentNode.insertBefore(this.dragging, after ? target.nextSibling : target);
    },
    drop() {},
    async dragEnd() {
        this.dragging = null;
        const order = [...this.$refs.list.querySelectorAll('tr[data-id]')].map((tr) => Number(tr.dataset.id));
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, Accept: 'application/json' },
            body: JSON.stringify({ order }),
        });
        if (res.ok) {
            this.saved = true;
            setTimeout(() => (this.saved = false), 2000);
        }
    },
}));

Alpine.start();
