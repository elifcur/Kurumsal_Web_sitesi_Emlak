import Alpine, { startAlpine } from '../../../resources/js/site.js';

/* İlan filtre formu: seçim değişince otomatik gönder (isteğe bağlı) */
Alpine.data('listingFilters', () => ({
    submit() {
        this.$root.requestSubmit();
    },
}));

startAlpine();
