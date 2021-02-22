<?php

namespace Database\Factories;

use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File as SystemFile;
use Illuminate\Support\Facades\Storage;

class FileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = File::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'sample.pdf',
            'bytes' => 10 * 1024, // 10 KiB,
            'path' => Storage::putFile(null, new SystemFile(base_path('tests/Fixtures/sample.pdf'))),
        ];
    }
}
