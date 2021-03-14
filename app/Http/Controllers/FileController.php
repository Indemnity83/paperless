<?php

namespace App\Http\Controllers;

use App\Models\DirectoryTree;
use App\Models\File;
use App\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
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
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|JsonResponse
     */
    public function index(Request $request)
    {
        $objectId = $request->get('o', DirectoryTree::isRoot()->firstOrFail()->id);

        return response()->view('files', [
            'objectId' => $objectId,
        ]);
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

        $fileTree = DirectoryTree::make(['parent_id' => $request->get('parent_id')]);
        $fileTree->object()->associate($file);
        $fileTree->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        return response()->view('files.show', [
            'file' => $file,
            'ancestors' => $file->directoryTree->ancestorsAndSelf()->breadthFirst()->get(),
        ]);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(File $file)
    {
        $file->delete();

        return redirect()->route('files.index')->with('status', 'Document moved to trash');
    }
}
