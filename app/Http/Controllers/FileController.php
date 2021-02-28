<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
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
        $files = QueryBuilder::for(File::class)
            ->defaultSort('name')
            ->allowedSorts([
                'name',
                AllowedSort::field('age', 'generated_at'),
                AllowedSort::field('size', 'bytes'),
                'pages',
            ])
            ->allowedFilters([
                AllowedFilter::trashed(),
            ]);

        if ($request->has('q')) {
            $ids = File::search($request->get('q'))->get()->pluck('id');
            $files->whereIn('id', $ids);
        }

        return response()->view('files.index', [
            'files' => $files->paginate()->appends($request->query()),
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

        return redirect()->route('files.index')->with('status', 'Document uploaded');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        return response()->view('files.show', compact('file'));
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
