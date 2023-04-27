<?php

namespace App\Providers;

use App\Models\Fileable;
use App\Models\Imageable;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\Grammar;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Fluent;
use Illuminate\Support\ServiceProvider;

class MigrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            Fileable::getMorphName() => Fileable::class,
            Imageable::getMorphName() => Imageable::class,
        ]);

        Grammar::macro('typeRaw', function (Fluent $column) {
            return $column->get('raw_type');
        });

        Blueprint::macro('customTypedColumn', function (string $type, string $columnName) {
            /** @var Blueprint $this */
            return $this->addColumn('raw', $columnName, ['raw_type' => $type]);
        });

        $this->addDBTypeMacros();
        $this->addToDBAllTypes();
        $this->addDropTypesMacro();
    }

    private function addToDBAllTypes()
    {
        if (! App::runningInConsole()) {
            return;
        }

        try {
            $conn = DB::connection();

            $doctrineConn = $conn->getDoctrineConnection();
            $dbPlatform = $doctrineConn->getDatabasePlatform();
            $types_mapping = config('database.types_mapping', []);

            foreach ($types_mapping as $dbType => $doctrineType) {
                $dbPlatform->registerDoctrineTypeMapping($dbType, $doctrineType);
                $dbPlatform->registerDoctrineTypeMapping('_' . $dbType, $doctrineType);
            }
        } catch (Exception $e) {
            \Log::info($e);
        }
    }

    private function addDBTypeMacros()
    {
        DB::macro('addEnumType', function (string $type, array $allowed): bool {
            $enums = implode(', ', array_map(fn ($el) => "'{$el}'", $allowed));
            return DB::unprepared("CREATE TYPE {$type} AS ENUM ({$enums})");
        });

        DB::macro('addMorphType', function (string $type, array $classesForMorph): bool {
            return DB::addEnumType(
                $type,
                array_map(fn ($class) => $class::getMorphName(), $classesForMorph)
            );
        });
    }

    private function addDropTypesMacro()
    {
        DB::macro('dropType', function (string $type): bool {
            return DB::unprepared("DROP TYPE {$type}");
        });

        DB::macro('dropAllTypes', function (): bool {
            return DB::unprepared(
                "
                DO $$ DECLARE
                    r RECORD;
                BEGIN
                FOR r IN (SELECT typname FROM pg_type WHERE typtype = 'e' or typtype = 'u') LOOP
                    EXECUTE 'DROP TYPE IF EXISTS ' || quote_ident(r.typname) || ' CASCADE;';
                END LOOP;
                END $$;
                "
            );
        });
    }
}
