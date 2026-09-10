@props(['subject' => null])
<form method="post" action="{{ route('contact.store') }}" class="space-y-5">
    @csrf
    <div class="hidden" aria-hidden="true"><label>Web sitesi <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
    <div class="grid gap-5 sm:grid-cols-2">
        <div><label for="name" class="form-label">Ad Soyad *</label><input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="120" class="form-input" autocomplete="name">@error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div><label for="email" class="form-label">E-posta *</label><input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="190" class="form-input" autocomplete="email">@error('email')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div><label for="phone" class="form-label">Telefon</label><input id="phone" name="phone" type="tel" value="{{ old('phone') }}" maxlength="40" class="form-input" autocomplete="tel">@error('phone')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div><label for="subject" class="form-label">Konu</label>
            <select id="subject" name="subject" class="form-input">
                @foreach($template->contactSubjects() as $option)
                    <option value="{{ $option }}" @selected(old('subject', $subject ?? request('konu')) === $option)>{{ $option }}</option>
                @endforeach
            </select>@error('subject')<p class="form-error">{{ $message }}</p>@enderror</div>
    </div>
    <div><label for="message" class="form-label">Mesajınız *</label><textarea id="message" name="message" rows="5" required minlength="10" maxlength="5000" class="form-input">{{ old('message') }}</textarea>@error('message')<p class="form-error">{{ $message }}</p>@enderror</div>
    <div>
        <label class="flex items-start gap-3 text-sm text-slate-600">
            <input type="checkbox" name="kvkk" value="1" class="mt-1 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" @checked(old('kvkk'))>
            <span><a href="{{ route('page.show', 'kvkk') }}" target="_blank" class="font-medium text-primary underline">KVKK Aydınlatma Metni</a>'ni okudum ve kişisel verilerimin işlenmesini kabul ediyorum.</span>
        </label>
        @error('kvkk')<p class="form-error">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="btn-primary w-full sm:w-auto">Gönder <x-icon name="arrow-right" class="h-4 w-4" /></button>
</form>
