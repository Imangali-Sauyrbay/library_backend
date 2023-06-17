<?php

namespace App\Services;

use Modules\Library\Entities\Book;
use Modules\Library\Entities\Library;
use Modules\Library\Entities\Page;
use Modules\UserAuth\Entities\Profiles\AdminProfile;
use Modules\UserAuth\Entities\Profiles\CoworkerProfile;
use Modules\UserAuth\Entities\Profiles\StudentProfile;
use Modules\UserAuth\Entities\User;

class ProvideModelsService
{
    public static function getUserClass()
    {
        return User::class;
    }

    public static function getStudentProfileClass()
    {
        return StudentProfile::class;
    }

    public static function getCoworkerProfileClass()
    {
        return CoworkerProfile::class;
    }

    public static function getAdminProfileClass()
    {
        return AdminProfile::class;
    }

    public static function getBookClass()
    {
        return Book::class;
    }

    public static function getPageClass()
    {
        return Page::class;
    }

    public static function getLibraryClass()
    {
        return Library::class;
    }
}
