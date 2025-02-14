<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\PayslipExtractionService;
use App\Helpers\FileHelper;

class PayslipExtractionTest extends TestCase
{
    use RefreshDatabase;

    public function test_extract_text_from_file(): void
    {
        $service = new PayslipExtractionService();

        Storage::fake('public');

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, 'Salary: $1000');
        $pdf->Ln();
        $pdf->Cell(40, 10, 'Deductions: $200');

        $pdfOutput = $pdf->Output('S');
        $filePath = 'fake-path/test-payslip.pdf';
        Storage::disk('public')->put($filePath, $pdfOutput);

        $extractedText = $service->extractTextFromFile($filePath);

        $this->assertStringContainsString('Salary: $1000', $extractedText);
        $this->assertStringContainsString('Deductions: $200', $extractedText);
    }

    public function test_save_extracted_text(): void
    {
        $service = new PayslipExtractionService();
        $extractedText = "Sample extracted text";

        $service->saveExtraction($extractedText);
        $this->assertDatabaseHas('payslip_extractions', [
            'extracted_text' => $extractedText,
        ]);
    }

    public function test_store_and_remove_file(): void
    {
        $file = UploadedFile::fake()->create('test-file.pdf', 1000);
        $filePath = FileHelper::storeFile($file);

        $this->assertTrue(Storage::disk('public')->exists($filePath));

        FileHelper::removeFile($filePath);
        $this->assertFalse(Storage::disk('public')->exists($filePath));
    }
}
