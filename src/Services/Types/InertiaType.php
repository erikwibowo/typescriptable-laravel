<?php

namespace Kiwilan\Typescriptable\Services\Types;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Services\Types\Inertia\InertiaEmbed;
use Kiwilan\Typescriptable\Services\Types\Inertia\InertiaPage;
use Kiwilan\Typescriptable\TypescriptableConfig;

class InertiaType
{
    protected function __construct(
      public string $filePath,
      public string $fileGlobal,
    ) {
    }

    public static function make(): self
    {
        $path = TypescriptableConfig::outputPath();
        $filename = TypescriptableConfig::inertiaFilename();
        $filenameGlobal = TypescriptableConfig::inertiaFilenameGlobal();

        $file = "{$path}/{$filename}";
        $fileGlobal = "{$path}/{$filenameGlobal}";

        $service = new self(
            filePath: $file,
            fileGlobal: $fileGlobal,
        );
        $types = $service->types();
        $global = $service->global();

        if (! File::isDirectory($path)) {
            File::makeDirectory($filename);
        }
        File::put($file, $types);
        File::put($fileGlobal, $global);

        return $service;
    }

    private function types(): string
    {
        $page = TypescriptableConfig::inertiaPage() ? InertiaPage::make() : '';

        return <<<typescript
// This file is auto generated by TypescriptableLaravel.
declare namespace Inertia {
  {$page}
}
typescript;
    }

    private function global(): string
    {
        $useEmbed = TypescriptableConfig::inertiaUseEmbed() ? InertiaEmbed::make() : InertiaEmbed::native();

        return <<<typescript
{$useEmbed}
typescript;
    }
}
