<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent;

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Typed\Eloquent\Schema\Model\SchemaModel;
use Kiwilan\Typescriptable\Typed\Eloquent\Schema\SchemaApp;
use Kiwilan\Typescriptable\Typed\EloquentType;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaCollection;
use Kiwilan\Typescriptable\TypescriptableConfig;

class EloquentTypeShow extends EloquentType implements IEloquentType
{
    public function run(): self
    {
        $this->app = SchemaApp::make($this->config->modelsPath);

        $collect = SchemaCollection::make($this->config->modelsPath, TypescriptableConfig::modelsSkip());
        $schemas = $collect->onlyModels();
        $this->app->parseBaseNamespace($schemas);

        $models = $this->parseModels($schemas);
        $this->app->setModels($models);

        ray($this->app);
        ray($this->app()->models());
        ray($this->app()->models()['Kiwilan\Typescriptable\Tests\Data\Models\Movie']->relations());

        return $this;
    }

    /**
     * @param  SchemaClass[]  $schemas
     * @return SchemaModel[]
     */
    private function parseModels(array $schemas): array
    {
        $models = [];
        foreach ($schemas as $schema) {
            Artisan::call('model:show', [
                'model' => $schema->namespace(),
                '--json' => true,
            ]);
            $models[$schema->namespace()] = SchemaModel::make(json_decode(Artisan::output(), true), $schema);
        }

        return $models;
    }
}
