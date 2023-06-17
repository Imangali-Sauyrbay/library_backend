<?php

namespace App\Services;

use App\Services\ProvideModelsService;
use Illuminate\Http\Response;

class CheckUnfilledFields
{
    public static function getUnfilledFields($user) {
        $unfilledFields = [];

        $profiles = array_filter([
            [$user, ProvideModelsService::getUserClass()],

            $user->isStudent()
            ? [$user->studentProfile, ProvideModelsService::getStudentProfileClass()]
            : null,

            $user->isCoworker()
            ? [$user->coworkerProfile, ProvideModelsService::getCoworkerProfileClass()]
            : null,

            $user->isAdmin()
            ? [$user->adminProfile, ProvideModelsService::getAdminProfileClass()]
            : null,
        ], fn($item) => !is_null($item));


        foreach ($profiles as [$profile, $profileClass]) {
            $profileTable = (new $profileClass)->getTable();

            if(!$profileTable) continue;

            $tableColumns = \Schema::getColumnListing($profileTable);
            foreach ($tableColumns as $column) {
                if (empty($profile->{$column})) {
                    $columnType = \Schema::getColumnType($profileTable, $column);
                    $unfilledFields[$column] = $columnType;
                }
            }
        }

        return $unfilledFields;
    }

    public static function getResponse($unfilledFields) {
        return response()->json(['unfilled_fields' => $unfilledFields], Response::HTTP_UNAUTHORIZED);
    }
}
