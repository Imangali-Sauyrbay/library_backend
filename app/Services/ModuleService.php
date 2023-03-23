<?php

namespace App\Services;

class ModuleService
{
    /**
     * Get list of paths to module subdirs
     */
    public static function getSubDirsOfModules(string $subDirName): array
    {
        $modulesPath = config('modules.paths.modules');
        $modulesDirs = glob($modulesPath . '/*', GLOB_ONLYDIR);
        $basePath = base_path() . '/';

        return array_map(
            fn ($path) => str_replace($basePath, '', $path . '/' . $subDirName),
            $modulesDirs
        );
    }
}
