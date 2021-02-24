<?php

namespace App\Console\Commands;

use App\Exceptions\ConsumeCommandException;
use App\Models\File;
use App\Pdf;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConsumeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paperless:consume
                            { source* : The file(s) to consume }
                            { --remove-source-file : Attempt to remove source file after being consumed }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume documents into the application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->argument('source') as $path) {
            try {
                $this->consume($path);
            } catch (ConsumeCommandException $e) {
                $this->error($e->getMessage());

                // Continue handling the command
                continue;
            }
        }
    }

    /**
     * Attempt to consume a single file.
     *
     * @param string $path
     * @throws ConsumeCommandException
     */
    protected function consume(string $path): void
    {
        $this->info("Consuming: $path");

        $this->validate($path, [
            'is_file',
            'mimetypes:application/pdf',
        ]);

        $pdf = app(Pdf::class);
        $pdf->setPdf($path);
        $prefix = Carbon::parse($pdf->info('CreationDate', 'now'))->year;

        $file = new File([
            'name' => basename($path),
            'path' => Storage::putFile($prefix, $path),
            'bytes' => filesize($path),
            'pages' => $pdf->info('Pages'),
            'meta' => $pdf->info(),
            'generated_at' => Carbon::make($pdf->info('CreationDate')),
        ]);

        if ($file->path === false) {
            throw new ConsumeCommandException("The file \"$path\" could not be stored");
        }

        $file->save();

        if ($this->option('remove-source-file')) {
            unlink($path);
        }

        $this->info("Consumed: $path");
    }

    /**
     * Run validation rules on the file.
     *
     * @param string $path
     * @param array $rules
     */
    protected function validate(string $path, array $rules)
    {
        foreach ($rules as $rule) {
            [$function, $args] = array_pad(explode(':', $rule, 2), 2, null);

            $function = Str::camel("validate_$function");
            call_user_func([&$this, $function], $path, $args);
        }
    }

    /**
     * The path under validation must be a simple file.
     *
     * @param string $path
     * @param string|null $args
     * @throws ConsumeCommandException
     */
    protected function validateIsFile(string $path, string $args = null)
    {
        if (is_file($path)) {
            return;
        }

        throw new ConsumeCommandException("The file \"$path\" does not exist");
    }

    /**
     * The path under validation must match one of the given MIME types.
     *
     * @param string $path
     * @param string|null $args
     * @throws ConsumeCommandException
     */
    protected function validateMimetypes(string $path, string $args = null)
    {
        $mimetypes = explode(',', $args);

        if (in_array(mime_content_type($path), $mimetypes)) {
            return;
        }

        throw new ConsumeCommandException("The file \"$path\" is not a PDF");
    }
}
