<?php

namespace App\Http\Controllers;

use App\Services\PayslipExtractionService;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PayslipExtractionController extends Controller
{
    protected PayslipExtractionService $payslipExtractionService;

    public function __construct(PayslipExtractionService $payslipExtractionService)
    {
        $this->payslipExtractionService = $payslipExtractionService;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:5120',
        ]);

        $file = $request->file('file');
        $filePath = FileHelper::storeFile($file);

        try {
            $extractedText = $this->payslipExtractionService->extractTextFromFile(filePath: $filePath);

            // Save the extracted text into the database
            $this->payslipExtractionService->saveExtraction(extractedText: $extractedText);

            // Remove the file after processing
            FileHelper::removeFile(filePath: $filePath);

            return response()->json([
                'message' => 'Text extracted and saved successfully',
                'text' => $extractedText
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
