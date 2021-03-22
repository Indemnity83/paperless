<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Obj;
use App\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Pdf $pdf
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Spatie\PdfToText\Exceptions\PdfNotFound
     * @throws \Throwable
     */
    public function store(Request $request, Pdf $pdf)
    {
        $request->validate([
            'parent_id' => ['required', 'exists:objects,id'],
            'document' => ['file', 'mimetypes:application/pdf'],
        ]);

        $document = $request->file('document');

        $pdf->setPdf($document->path());
        $year = Carbon::parse($pdf->info('CreationDate', 'now'))->year;

        $file = new File([
            'name' => $document->getClientOriginalName(),
            'path' => $document->store($year),
            'bytes' => $document->getSize(),
            'pages' => $pdf->info('Pages'),
            'meta' => $pdf->info(),
            'generated_at' => Carbon::make($pdf->info('CreationDate')),
        ]);

        throw_if($file->path === false,
            ValidationException::withMessages(['document' => 'The document could not be stored'])
        );

        $file->save();

        $fileTree = Obj::make(['parent_id' => $request->get('parent_id')]);
        $fileTree->item()->associate($file);
        $fileTree->save();

        return redirect()->back();
    }

    /**
     * Download the specified resource.
     *
     * @param File $file
     * @return StreamedResponse
     */
    public function download(File $file)
    {
        abort_unless(Storage::exists($file->path), 404);

        return response()->stream(function () use ($file) {
            echo Storage::get($file->path);
        }, 200, ['Content-Type' => Storage::mimeType($file->path)]);
    }

    /**
     * Download the specified resource.
     *
     * @param File $file
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function thumbnail(File $file)
    {
        abort_unless(Storage::exists($file->thumbnail), 404);

        return response()->stream(function () use ($file) {
            echo Storage::get($file->thumbnail);
        }, 200, ['Content-Type' => Storage::mimeType($file->thumbnail)]);
    }
}
