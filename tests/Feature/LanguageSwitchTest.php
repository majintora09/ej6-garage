<?php

namespace Tests\Feature;

use App\Support\UploadedMedia;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LanguageSwitchTest extends TestCase
{
    public function test_language_switch_preserves_the_requested_page(): void
    {
        $response = $this->get('/language/de?redirect='.urlencode('/gallery?album=mods'));

        $response->assertRedirect('/gallery?album=mods');
        $this->assertSame('de', session('locale'));
    }

    public function test_language_switch_rejects_external_redirects(): void
    {
        $response = $this->from('/community')->get('/language/fr?redirect='.urlencode('https://example.com/bad'));

        $response->assertRedirect('/community');
        $this->assertSame('fr', session('locale'));
    }

    public function test_uploaded_media_urls_are_root_relative(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('profiles/1/avatar/avatar.png', 'image-bytes');

        $this->assertSame('/media/profiles/1/avatar/avatar.png', UploadedMedia::url('profiles/1/avatar/avatar.png'));
        $this->assertNull(UploadedMedia::url('missing.png'));
    }
}
