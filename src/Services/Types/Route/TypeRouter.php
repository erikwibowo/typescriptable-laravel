<?php

namespace Kiwilan\Typescriptable\Services\Types\Route;

use Closure;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Kiwilan\Typescriptable\TypescriptableConfig;

class TypeRouter
{
    protected function __construct(
        /** @var TypeRoute[] */
        protected array $routes = [],
        protected ?string $tsNames = null,
        protected ?string $tsPaths = null,
        protected ?string $tsParams = null,

        protected ?string $tsTypes = null,
        protected ?string $tsGlobalTypes = null,

        protected ?string $tsGlobalTypesGet = null,
        protected ?string $tsGlobalTypesPost = null,
        protected ?string $tsGlobalTypesPut = null,
        protected ?string $tsGlobalTypesPatch = null,
        protected ?string $tsGlobalTypesDelete = null,

        protected ?string $tsRoutes = null,

        protected ?string $typescript = null,
        protected ?string $typescriptRoutes = null,
    ) {
    }

    public static function make(): self
    {
        $type = new self();
        $type->routes = $type->setRoutes();

        $type->tsNames = $type->setTsNames();
        $type->tsPaths = $type->setTsPaths();
        $type->tsParams = $type->setTsParams();

        $type->tsTypes = $type->setTsTypes();
        $type->tsGlobalTypes = $type->setTsGlobalTypes();

        $type->tsGlobalTypesGet = $type->setTsGlobalTypesMethod('GET');
        $type->tsGlobalTypesPost = $type->setTsGlobalTypesMethod('POST');
        $type->tsGlobalTypesPut = $type->setTsGlobalTypesMethod('PUT');
        $type->tsGlobalTypesPatch = $type->setTsGlobalTypesMethod('PATCH');
        $type->tsGlobalTypesDelete = $type->setTsGlobalTypesMethod('DELETE');

        $type->tsRoutes = $type->setTsRoutes();

        $type->typescript = $type->setTypescript();
        $type->typescriptRoutes = $type->setTypescriptRoutes();

        return $type;
    }

    public function typescript(): string
    {
        return $this->typescript;
    }

    public function setTypescript(): string
    {
        return <<<typescript
        // This file is auto generated by TypescriptableLaravel.
        declare namespace Route {
          export type Name = {$this->tsNames}
          export type Path = {$this->tsPaths};
          export type Params = {
        {$this->tsParams}
          };

          export type Method = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';
          export interface Entity { name: Route.Name; path: Route.Path; params?: Route.Params[Route.Name],  method: Route.Method; }

          declare namespace Typed {
        {$this->tsTypes}
          }
          export type Type = {$this->tsGlobalTypes}
          export type TypeGet = {$this->tsGlobalTypesGet}
          export type TypePost = {$this->tsGlobalTypesPost}
          export type TypePut = {$this->tsGlobalTypesPut}
          export type TypePatch = {$this->tsGlobalTypesPatch}
          export type TypeDelete = {$this->tsGlobalTypesDelete}
        }
        typescript;
    }

    public function typescriptRoutes(): string
    {
        return $this->typescriptRoutes;
    }

    public function setTypescriptRoutes(): string
    {
        return <<<typescript
        // This file is auto generated by TypescriptableLaravel.
        const Routes: Record<Route.Name, Route.Entity> = {
        {$this->tsRoutes},
        }

        declare global {
          interface Window {
            Routes: Record<Route.Name, Route.Entity>
          }
        }

        if (typeof window !== undefined && typeof window.Routes !== undefined)
          window.Routes = Routes

        export { Routes }

        typescript;
    }

    private function setTsNames(): string
    {
        return $this->collectRoutes(function (TypeRoute $route) {
            return "'{$route->name()}'";
        }, ' | ');
    }

    private function setTsPaths(): string
    {
        return $this->collectRoutes(function (TypeRoute $route) {
            return "'{$route->fullUri()}'";
        }, ' | ');
    }

    private function setTsParams(): string
    {
        return $this->collectRoutes(function (TypeRoute $route) {
            $hasParams = count($route->parameters()) > 0;

            if ($hasParams) {
                $params = collect($route->parameters())
                    ->map(function (string $param) {
                        return "'{$param}'?: string | number | boolean";
                    })
                    ->join(",\n");

                return "    '{$route->name()}': {\n      {$params}\n    }";
            } else {
                return "    '{$route->name()}': never";
            }
        }, ",\n");
    }

    private function setTsTypes(): string
    {
        return $this->collectRoutes(function (TypeRoute $route) {
            $params = '';

            if (empty($route->parameters())) {
                $params = 'params?: undefined';
            } else {
                $params = collect($route->parameters())->map(function (string $param) {
                    return "{$param}: string | number | boolean";
                })->join(",\n");
                $params = <<<typescript
                params: {
                        {$params}
                      }
                typescript;
            }

            return <<<typescript
                type {$route->nameType()} = {
                  name: '{$route->pathType()}',
                  {$params},
                  query?: Record<string, string | number | boolean>,
                  hash?: string,
                }
            typescript;
        }, ";\n");
    }

    private function setTsGlobalTypes(): string
    {
        return $this->collectRoutes(function (TypeRoute $route) {
            return <<<typescript
            Route.Typed.{$route->nameType()}
            typescript;
        }, ' | ');
    }

    private function setTsGlobalTypesMethod(string $method): string
    {
        $routes = $this->collectRoutesMethod($method);

        return collect($routes)
            ->map(function (TypeRoute $route) {
                return <<<typescript
                Route.Typed.{$route->nameType()}
                typescript;
            })->join(' | ');
    }

    private function setTsRoutes(): string
    {
        return $this->collectRoutes(function (TypeRoute $route) {
            $params = collect($route->parameters())
                ->map(function (string $param) {
                    return "{$param}: 'string',";
                })
                ->join(",\n");

            if (empty($params)) {
                $params = 'params: undefined';
            } else {
                $params = <<<typescript
                params: {
                      {$params}
                    }
                typescript;
            }

            return <<<typescript
              '{$route->name()}': {
                name: '{$route->name()}',
                path: '{$route->fullUri()}',
                {$params},
                method: '{$route->methods()[0]}',
              }
            typescript;
        }, ",\n");
    }

    private function collectRoutesMethod(string $method): Collection
    {
        return collect($this->routes)
            ->filter(function (TypeRoute $route) use ($method) {
                return $route->method() === $method;
            });
    }

    private function collectRoutes(Closure $closure, ?string $join = null): string|Collection
    {
        $routes = collect($this->routes)
            ->map(function (TypeRoute $route, string $key) use ($closure) {
                return $closure($route, $key);
            });

        if ($join) {
            return $routes->join($join);
        }

        return $routes;
    }

    private function setRoutes(): array
    {
        /** @var TypeRoute[] $routes */
        $routes = collect(app('router')->getRoutes())
            ->mapWithKeys(function ($route) {
                return [$route->getName() => $route];
            })
            ->filter()
            ->map(function (Route $route) {
                return TypeRoute::make($route);
            })
            ->toArray();

        $list = [];

        foreach ($routes as $route) {
            if (! $this->skipRouteName($route)) {
                $list[$route->name()] = $route;
            }
        }

        foreach ($list as $route) {
            if ($this->skipRoutePath($route)) {
                unset($list[$route->name()]);
            }
        }

        return $list;
    }

    private function skipRouteName(TypeRoute $route): bool
    {
        $skip_name = [];
        $skippable_name = TypescriptableConfig::routesSkipName();

        foreach ($skippable_name as $item) {
            $item = str_replace('.*', '', $item);
            array_push($skip_name, $item);
        }

        foreach ($skip_name as $type => $item) {
            if (str_starts_with($route->name(), $item)) {
                return true;
            }
        }

        return false;
    }

    private function skipRoutePath(TypeRoute $route): bool
    {
        $skip_path = [];
        $skippable_path = TypescriptableConfig::routesSkipPath();

        foreach ($skippable_path as $item) {
            $item = str_replace('/*', '', $item);
            array_push($skip_path, $item);
        }

        foreach ($skip_path as $type => $item) {
            if (str_starts_with($route->uri(), $item)) {
                return true;
            }
        }

        return false;
    }
}
