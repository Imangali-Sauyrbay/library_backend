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
        /**
         * The enforceMorphMap() method is a Laravel feature that allows you
         * to define a mapping between morphable types and their corresponding model classes.
         */
        Relation::enforceMorphMap([
            Fileable::getMorphName() => Fileable::class,
            Imageable::getMorphName() => Imageable::class,
        ]);

        // Fluent is a helper class used in Laravel to create fluent interfaces.
        Grammar::macro('typeRaw', function (Fluent $column) {
            return $column->get('raw_type');
        });

        Blueprint::macro('customTypedColumn', function (string $type, string $columnName): \Illuminate\Database\Schema\ColumnDefinition {
            /** @var Blueprint $this */
            return $this->addColumn('raw', $columnName, ['raw_type' => $type]);
        });

        $this->addDBTypeMacros();
        $this->addToDBAllTypes();
        $this->addDropTypesMacro();
    }

    private function addToDBAllTypes()
    {
        // if (App::runningInConsole()) {
        //     return;
        // }

        try {
            // Establishes a connection to the database
            $conn = DB::connection();

            // Retrieves the underlying Doctrine connection associated with the Laravel database connection.
            $doctrineConn = $conn->getDoctrineConnection();

            // Obtains the database platform from the Doctrine connection,
            // which represents the specific database vendor being used.
            $dbPlatform = $doctrineConn->getDatabasePlatform();

            // Retrieves a mapping of custom database types from the Laravel configuration.
            $types_mapping = config('database.types_mapping', []);


            /**
             * For each custom type mapping,
             * registers the mapping between
             * the custom database type and the corresponding Doctrine type
             * in the database platform.
             */
            foreach ($types_mapping as $dbType => $doctrineType) {
                $dbPlatform->registerDoctrineTypeMapping($dbType, $doctrineType);

                // Registers mappings for variations of the custom types by appending an underscore to the type name.
                $dbPlatform->registerDoctrineTypeMapping('_' . $dbType, $doctrineType);
            }
        } catch (Exception $e) {
            \Log::info($e);
        }
    }

    /**
     * These macros allow for convenient creation of enum types
     * in the database by using the DB facade in Laravel.
     * The addEnumType macro creates a single enum type
     * with provided allowed values, while the addMorphType macro
     * simplifies the creation of enum types specifically for
     * morph relationships by automatically retrieving
     * the morph names from the provided classes.
     */
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

    /**
     * These macros provide convenient methods for dropping database types in Laravel.
     * The dropType macro drops a single specified type using the DROP TYPE SQL command,
     * while the dropAllTypes macro drops all types in the database using
     * a PostgreSQL-specific loop and dynamic SQL execution
     */
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
