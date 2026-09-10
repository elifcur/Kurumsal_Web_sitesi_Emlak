@props(['model' => null])
<div class="card">
    <div class="card-header"><h2 class="font-semibold text-slate-900">SEO</h2></div>
    <div class="card-body space-y-4">
        <x-admin.input name="meta_title" label="SEO Başlığı" :value="$model?->meta_title" maxlength="255" help="Boş bırakılırsa başlık kullanılır." />
        <x-admin.textarea name="meta_description" label="SEO Açıklaması" :value="$model?->meta_description" maxlength="320" rows="3" help="Arama sonuçlarında görünen açıklama (160 karakter önerilir)." />
    </div>
</div>
