<?php

namespace Modules\UserAuth\Observers;

use App\Services\DeviceService;
use Modules\UserAuth\Entities\PersonalAccessToken;

class PersonalAccessTokenObserver
{
    /**
     * Handle the PersonalAccessToken "created" event.
     */
    public function created(PersonalAccessToken $personalAccessToken): void
    {
        $deviceData = DeviceService::getShortDeviceInfo();

        $personalAccessToken->storeMeta($deviceData);
    }
}
