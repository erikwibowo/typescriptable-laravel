<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\Typed\EloquentType;
use Kiwilan\Typescriptable\Typed\Typescript\TypescriptToPhp;
use Kiwilan\Typescriptable\Typed\Utils\ModelList;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    $models = outputDir('types-models.d.ts');
    deleteFile($models);
});

it('can be run with show method', function () {
    foreach (getDatabaseTypes() as $type) {
        ray('Database type: '.$type);
        TestCase::setupDatabase($type);

        config(['typescriptable.models.skip' => [
            'Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest',
        ]]);

        Artisan::call('typescriptable:models', [
            '--models-path' => models(),
            '--output-path' => outputDir(),
            '--php-path' => outputDir().'/php',
            '--legacy' => false,
        ]);

        // $models = outputDir(TypescriptableConfig::modelsFilename());
        // expect($models)->toBeFile();
    }
});

it('can be run with legacy method', function () {
    foreach (getDatabaseTypes() as $type) {
        ray('Database type: '.$type);
        TestCase::setupDatabase($type);

        config(['typescriptable.models.skip' => [
            'Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest',
        ]]);

        Artisan::call('typescriptable:models', [
            '--models-path' => models(),
            '--output-path' => outputDir(),
            '--php-path' => outputDir().'/php',
            '--legacy' => true,
        ]);

        // $models = outputDir(TypescriptableConfig::modelsFilename());
        // expect($models)->toBeFile();
    }
});

// it('can be run', function () {
//     foreach (getDatabaseTypes() as $type) {
//         ray('Database type: '.$type);
//         TestCase::setupDatabase($type);

//         config(['typescriptable.models.skip' => [
//             'Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest',
//         ]]);

//         Artisan::call('typescriptable:models', [
//             '--models-path' => models(),
//             '--output-path' => outputDir(),
//             '--php-path' => outputDir().'/php',
//             '--legacy' => true,
//         ]);

//         $models = outputDir(TypescriptableConfig::modelsFilename());
//         expect($models)->toBeFile();
//     }
// });

// it('is correct from models', function () {
//     config(['typescriptable.models.skip' => [
//         'App\\Models\\SushiTest',
//     ]]);

//     TestCase::setupDatabase('mysql');

//     Artisan::call('typescriptable:models', [
//         '--models-path' => models(),
//         '--output-path' => outputDir(),
//         '--php-path' => outputDir().'/php',
//     ]);

//     $models = outputDir(TypescriptableConfig::modelsFilename());
//     $ts = TypescriptToPhp::make($models);
//     $data = $ts->raw();
//     $classes = $ts->classes();

//     foreach ($classes as $key => $value) {
//         if (str_contains($key, 'Paginate')) {
//             unset($classes[$key]);
//         }
//     }

//     $type = EloquentType::make(models(), outputDir());

//     // expect(count($type->eloquents()))->toBe(count($classes));

//     // foreach ($type->eloquents() as $field => $properties) {
//     //     expect(array_key_exists($field, $classes))->toBeTrue();

//     //     $tsProperties = $classes[$field]->properties();
//     //     if (! array_key_exists('pivot', $properties)) {
//     //         expect(count($tsProperties))->toBe(count($properties));
//     //     }

//     //     expect(array_key_exists($field, $data))->toBeTrue();
//     //     foreach ($properties as $key => $property) {
//     //         $tsProperty = $tsProperties[$key];

//     //         expect(array_key_exists($key, $tsProperties))->toBeTrue();
//     //         if (! is_array($property)) {
//     //             expect($property->name())->toBe($tsProperty->name());
//     //         }
//     //         // expect($property->typeTs())->toBe($tsProperty->type());
//     //         // expect($property->isNullable())->toBe($tsProperty->isNullable());
//     //     }
//     // }
// });

// it('can list models', function () {
//     $list = ModelList::make(models());

//     expect($list->models())->toBeArray();
//     expect($list->path())->toBe(models());
//     expect(count($list->models()))->toBe(10);

//     Artisan::call('model:list', [
//         'modelPath' => models(),
//     ]);

//     $output = Artisan::output();
//     expect($output)->toContain('Name');
//     expect($output)->toContain('Namespace');
//     expect($output)->toContain('Path');
// });
