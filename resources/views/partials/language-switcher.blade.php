@php
    $languageOptions = [
        'en' => 'English',
        'de' => 'Deutsch',
        'fr' => 'Français',
        'pt' => 'Português (PT)',
        'lb' => 'Lëtzebuergesch',
    ];

    $activeLocale = app()->getLocale();
@endphp

<details class="language-switcher">
    <summary aria-label="{{ __('ui.language.choose') }}">
        <span>{{ strtoupper($activeLocale) }}</span>
    </summary>

    <div class="language-options" aria-label="{{ __('ui.language.choices') }}">
        @foreach ($languageOptions as $locale => $label)
            <a
                href="{{ route('language.switch', $locale) }}"
                class="{{ $activeLocale === $locale ? 'active' : '' }}"
                lang="{{ $locale === 'pt' ? 'pt-PT' : $locale }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>
</details>
