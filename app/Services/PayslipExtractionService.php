<?php

namespace App\Services;

use Aws\Textract\TextractClient;
use Aws\Exception\AwsException;
use App\Models\PayslipExtraction;
use Exception;
use Illuminate\Support\Facades\Storage;

class PayslipExtractionService
{
    protected TextractClient $textract;

    public function __construct()
    {
        $this->textract = new TextractClient([
            'version' => 'latest',
            'region'  => env('AWS_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    /**
     * @throws Exception
     */
    public function extractTextFromFile(string $filePath): string
    {
        $fileContent = Storage::disk('public')->get($filePath);

        try {
            $result = $this->textract->analyzeDocument([
                'Document' => [
                    'Bytes' => $fileContent
                ],
                'FeatureTypes' => ['TABLES', 'FORMS']
            ]);

            $extractedText = '';
            foreach ($result['Blocks'] as $block) {
                if ($block['BlockType'] === 'LINE') {
                    $extractedText .= $block['Text'] . "\n";
                }
            }

            return $extractedText;
        } catch (AwsException $e) {
            throw new Exception('AWS Textract error: ' . $e->getMessage());
        }
    }

    public function saveExtraction(string $extractedText): void
    {
        PayslipExtraction::create([
            'extracted_text' => $extractedText,
            'extracted_at' => now(),
        ]);
    }
}
