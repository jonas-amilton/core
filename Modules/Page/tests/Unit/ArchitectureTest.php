<?php

declare(strict_types=1);

namespace Modules\Page\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Page\Contracts\PageRepository;
use Modules\Page\Repositories\EloquentPageRepository;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Tests\TestCase;

class ArchitectureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Controller class name => source code.
     *
     * @return array<string, string>
     */
    private function controllerSources(): array
    {
        $directory = module_path('Page', 'app/Http/Controllers');
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

        $sources = [];

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $relative = str_replace(
                [module_path('Page').'/', 'app/', '.php'],
                ['', '', ''],
                $file->getPathname(),
            );

            $class = 'Modules\\Page\\'.str_replace('/', '\\', $relative);
            $sources[$class] = (string) file_get_contents($file->getPathname());
        }

        ksort($sources);

        return $sources;
    }

    public function test_every_controller_is_invokable(): void
    {
        $controllers = $this->controllerSources();

        $this->assertNotEmpty($controllers);

        foreach ($controllers as $class => $source) {
            preg_match_all('/public function (\w+)\s*\(/', $source, $matches);
            $publicMethods = array_values(array_diff($matches[1], ['__construct']));

            $this->assertContains('__invoke', $publicMethods, "{$class} não é invokable.");
            $this->assertSame(['__invoke'], $publicMethods, "{$class} expõe métodos públicos além de __invoke.");
        }
    }

    public function test_the_repository_is_bound_to_its_eloquent_implementation(): void
    {
        $this->assertInstanceOf(
            EloquentPageRepository::class,
            $this->app->make(PageRepository::class),
        );
    }

    public function test_controllers_do_not_query_the_database_directly(): void
    {
        foreach ($this->controllerSources() as $class => $source) {
            $this->assertStringNotContainsString('DB::', $source, "{$class} usa DB:: diretamente.");
            $this->assertStringNotContainsString('::query()', $source, "{$class} consulta o Eloquent diretamente.");
        }
    }
}
