<?php

namespace Vnetby\Helpers;

class HelperUrl
{
    static function urlPhone(string $phone): string
    {
        return 'tel:' . static::normalizePhone($phone);
    }

    static function ulrEmail(string $email): string
    {
        return 'mailto:' . trim($email);
    }

    static function urlWhatsapp(string $phone): string
    {
        $phone = static::normalizePhone($phone);
        $phone = preg_replace("/^\+/", '', $phone);
        return 'https://api.whatsapp.com/send?phone=' . $phone;
    }

    static function urlTelegram(string $nickName): string
    {
        // если передана ссылка
        $nickName = preg_replace("%^https?://t\.me/([^/]+)%", '$1', $nickName);
        // если передан никнейм с собачкой
        $nickName = preg_replace("/^@/", '', $nickName);
        return 'https://t.me/' . $nickName;
    }

    static function urlViber(string $publicAccountUri, string $text = ''): string
    {
        return 'viber://pa?chatURI=' . $publicAccountUri . '&text=' . urlencode($text);
    }

    private static function normalizePhone(string $phone): string
    {
        $phone = trim($phone);
        return (preg_match("/^\+/", $phone) ? '+' : '') . preg_replace("/[^\d]+/", '', $phone);
    }
}
