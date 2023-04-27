<?php

namespace App\Services;

use Modules\Library\Entities\Book;
use Modules\Library\Entities\Library;
use Modules\Library\Entities\Page;
use Modules\UserAuth\Entities\User;

class ProvideModelsService
{
    public static function getUserClass()
    {
        return User::class;
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
