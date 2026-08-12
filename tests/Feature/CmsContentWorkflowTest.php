<?php

namespace Tests\Feature;

use App\Models\CmsContentBlock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CmsContentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_edit_cms_content(): void
    {
        $this->get('/admin/cms/content/home')->assertRedirect('/login');
    }

    public function test_admin_can_update_text_and_frontend_renders_it(): void
    {
        $admin = User::factory()->create(['role'=>'administrator', 'is_active'=>true]);
        $this->actingAs($admin)->put('/admin/cms/content/home', [
            'content' => ['hero_title'=>'A CMS managed safari heading'],
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('cms_content_blocks', ['page'=>'home','key'=>'hero_title','value'=>'A CMS managed safari heading']);
        CmsContentBlock::flushPage('home');
        $this->get('/')->assertOk()->assertSee('A CMS managed safari heading');
    }

    public function test_admin_can_upload_a_valid_image_and_invalid_files_are_rejected(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role'=>'administrator', 'is_active'=>true]);

        $this->actingAs($admin)->put('/admin/cms/content/contact', [
            'uploads' => ['hero_image'=>UploadedFile::fake()->image('hero.webp', 1200, 700)],
        ])->assertSessionHasNoErrors();
        $path = CmsContentBlock::where(['page'=>'contact','key'=>'hero_image'])->value('value');
        Storage::disk('public')->assertExists($path);

        $this->actingAs($admin)->put('/admin/cms/content/contact', [
            'uploads' => ['hero_image'=>UploadedFile::fake()->create('payload.php', 10, 'application/x-php')],
        ])->assertSessionHasErrors('uploads.hero_image');
    }
}
