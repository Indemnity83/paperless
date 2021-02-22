<?php

namespace App;

use Illuminate\Support\Arr;
use Spatie\PdfToText\Exceptions\CouldNotExtractText;
use Spatie\PdfToText\Exceptions\PdfNotFound;
use Symfony\Component\Process\Process;

class Pdf
{
    protected $pdf;

    protected $binPath;

    protected $options = [];

    public function __construct(string $binPath = null)
    {
        $this->binPath = $binPath ?? '/usr/bin/pdfinfo';
    }

    public function setPdf(string $pdf): self
    {
        if (! is_readable($pdf)) {
            throw new PdfNotFound("Could not read `{$pdf}`");
        }

        $this->pdf = $pdf;

        return $this;
    }

    public function setOptions(array $options): self
    {
        $this->options = $this->parseOptions($options);

        return $this;
    }

    public function addOptions(array $options): self
    {
        $this->options = array_merge(
            $this->options,
            $this->parseOptions($options)
        );

        return $this;
    }

    protected function parseOptions(array $options): array
    {
        $mapper = function (string $content): array {
            $content = trim($content);
            if ('-' !== ($content[0] ?? '')) {
                $content = '-'.$content;
            }

            return explode(' ', $content, 2);
        };

        $reducer = function (array $carry, array $option): array {
            return array_merge($carry, $option);
        };

        return array_reduce(array_map($mapper, $options), $reducer, []);
    }

    public function info($attribute = null, $default = null)
    {
        $process = new Process(array_merge([$this->binPath], $this->options, [$this->pdf]));
        $process->run();
        if (! $process->isSuccessful()) {
            throw new CouldNotExtractText($process);
        }

        $info = [];
        $lines = explode(PHP_EOL, trim($process->getOutput()));

        foreach ($lines as $line) {
            [$key, $value] = explode(':', $line, 2);
            $info[$key] = trim($value);
        }

        if (isset($attribute)) {
            return Arr::get($info, $attribute, $default);
        }

        return $info;
    }

    public static function getInfo(string $pdf, string $binPath = null, array $options = []): array
    {
        return (new static($binPath))
            ->setOptions($options)
            ->setPdf($pdf)
            ->info();
    }
}
