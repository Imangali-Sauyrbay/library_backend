<?php

namespace App\Services;

use Browser;

class DeviceService
{
    private const DEVICE_MOBILE = 'mobile';
    private const DEVICE_TABLET = 'tablet';
    private const DEVICE_DESKTOP = 'desktop';
    private const DEVICE_OTHER = 'other';

    public static function getShortDeviceInfo(): array
    {
        return [
            'ip' => request()->ip(),
            'user_agent' => Browser::userAgent(),
            'platform_name' => Browser::platformName(),
            'browser_family' => Browser::browserFamily(),
            'device_name' => Browser::deviceFamily(),
            'device_type' => static::getDeviceType(),
        ];
    }

    private static function getDeviceType(): string
    {
        $type = self::DEVICE_OTHER;

        if (Browser::isDesktop()) {
            $type = self::DEVICE_DESKTOP;
        }

        if (Browser::isTablet()) {
            $type = self::DEVICE_TABLET;
        }

        if (Browser::isMobile()) {
            $type = self::DEVICE_MOBILE;
        }

        return $type;
    }
}
