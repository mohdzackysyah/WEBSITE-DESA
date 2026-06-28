<?php

namespace Tests\Feature;

use App\Models\SuratRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuratRequestEditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed default settings/data if necessary
    }

    /**
     * Test editing a letter request as an authenticated operator
     */
    public function test_authenticated_operator_can_edit_letter_request()
    {
        // 1. Create a mock operator user
        $user = User::factory()->create([
            'role' => 'operator_pelayanan'
        ]);

        // 2. Create a mock letter request (waiting verification)
        $surat = SuratRequest::create([
            'nomor_pengajuan' => 'DSA-2026-TESTEDIT',
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Warga Typos',
            'jenis_surat' => 'domisili',
            'form_data' => [
                'alamat_domisili' => 'Jalan Typo No. 4',
                'keperluan' => 'Membuat Paspor'
            ],
            'status' => 'menunggu_verifikasi'
        ]);

        // 3. Request edit page
        $response = $this->actingAs($user)->get("/admin/surat/edit/{$surat->id}");
        $response->assertStatus(200);
        $response->assertSee('Edit Pengajuan');
        $response->assertSee('Warga Typos');

        // 4. Submit updated data
        $postData = [
            'nama_lengkap' => 'Warga Yang Sudah Diedit',
            'nik' => '9876543210987654',
            'nomor_surat' => '470/123/DSA/VI/2026',
            'form_data' => [
                'alamat_domisili' => 'Jalan Rapi No. 12',
                'keperluan' => 'Membuka Rekening Bank'
            ]
        ];

        $response2 = $this->actingAs($user)->post("/admin/surat/edit/{$surat->id}", $postData);
        $response2->assertRedirect("/admin/surat/detail/{$surat->id}");
        $response2->assertSessionHas('success');

        // 5. Verify database update
        $surat->refresh();
        $this->assertEquals('Warga Yang Sudah Diedit', $surat->nama_lengkap);
        $this->assertEquals('9876543210987654', $surat->nik);
        $this->assertEquals('470/123/DSA/VI/2026', $surat->nomor_surat);
        $this->assertEquals('Jalan Rapi No. 12', $surat->form_data['alamat_domisili']);
        $this->assertEquals('Membuka Rekening Bank', $surat->form_data['keperluan']);
    }

    /**
     * Test that finished or rejected requests cannot be edited
     */
    public function test_finished_letter_request_cannot_be_edited()
    {
        $user = User::factory()->create([
            'role' => 'operator_pelayanan'
        ]);

        // Create a completed request
        $surat = SuratRequest::create([
            'nomor_pengajuan' => 'DSA-2026-TESTFINISHED',
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Warga Sukses',
            'jenis_surat' => 'domisili',
            'form_data' => [
                'alamat_domisili' => 'Jalan Jaya No. 5',
                'keperluan' => 'Beasiswa'
            ],
            'status' => 'selesai'
        ]);

        // Try getting edit page
        $response = $this->actingAs($user)->get("/admin/surat/edit/{$surat->id}");
        $response->assertRedirect("/admin/surat/detail/{$surat->id}");
        $response->assertSessionHas('error');

        // Try posting updates
        $postData = [
            'nama_lengkap' => 'Warga Typos Hack',
            'nik' => '1234567890123456',
            'form_data' => [
                'alamat_domisili' => 'Jalan Hack',
                'keperluan' => 'Hack'
            ]
        ];

        $response2 = $this->actingAs($user)->post("/admin/surat/edit/{$surat->id}", $postData);
        $response2->assertRedirect("/admin/surat/detail/{$surat->id}");
        $response2->assertSessionHas('error');

        // Ensure database did not change
        $surat->refresh();
        $this->assertEquals('Warga Sukses', $surat->nama_lengkap);
    }
}
