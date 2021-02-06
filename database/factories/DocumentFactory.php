<?php

namespace Database\Factories;

use App\Models\Document;
use Fpdf\Fpdf;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Document::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $paragraphCount = ceil((1.0 * -log((float) mt_rand() / (float) mt_getrandmax())) * 5);
        $content = $this->faker->paragraphs($paragraphCount, true);
        $fileName = $this->faker->randomNumber(8) . '.pdf';
        $tempFile = tempnam(storage_path('tmp'), 'x');

        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Write(10, $content);
        $pdf->Output('F', $tempFile);

        $file = new UploadedFile($tempFile, $fileName, 'application/pdf', null, false);
        $attachment = $file->store('documents', 'public');

        return [
            'attachment' => $attachment,
            'attachment_name' => $file->getClientOriginalName(),
            'attachment_size' => $file->getSize(),
            'content' => $content,
        ];
    }
}
