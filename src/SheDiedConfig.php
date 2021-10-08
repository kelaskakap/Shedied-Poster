<?php

namespace SheDied;

use SheDied\helpers\Dua;
use SheDied\helpers\Satu;
use SheDied\helpers\Tiga;
use SheDied\helpers\Empat;
use SheDied\helpers\Lima;
use SheDied\helpers\Enam;
use SheDied\helpers\Tujuh;
use SheDied\helpers\Wolu;
use SheDied\helpers\Songo;

class SheDieDConfig
{

    static $sources = [];

    /**
     * SITE DOMAIN adalah project yang aktif
     */
    const SITE_DOMAIN = 'appszone.us';
    const AUTHOR_ID = 1;
    const BOT_POST_INVTERVAL = 10; //minutes

    private static function _sources()
    {
        if (empty(self::$sources))
        {
            if (self::SITE_DOMAIN == Satu::LOKERKREASI_COM)
                self::$sources = Satu::sources();
            elseif (self::SITE_DOMAIN == Dua::AWESOMEDECORS_US)
                self::$sources = Dua::sources();
            elseif (self::SITE_DOMAIN == Tiga::POJOKJOGJA_COM)
                self::$sources = Tiga::sources();
            elseif (self::SITE_DOMAIN == Empat::TECHNOREVIEW_US)
                self::$sources = Empat::sources();
            elseif (self::SITE_DOMAIN == Lima::JOGJA_TRADE)
                self::$sources = Lima::sources();
            elseif (self::SITE_DOMAIN == Enam::NGEMIE_COM)
                self::$sources = Enam::sources();
            elseif (self::SITE_DOMAIN == Tujuh::FURNITUREIDEAS_US)
                self::$sources = Tujuh::sources();
            elseif (self::SITE_DOMAIN == Wolu::SWOODEN)
                self::$sources = Wolu::sources();
            elseif (self::SITE_DOMAIN == Songo::APPSZONE)
                self::$sources = Songo::sources();
        }
    }

    public static function getSourcesList()
    {
        self::_sources();
        return self::$sources;
    }

    public static function getSource($id)
    {
        self::_sources();
        if (array_key_exists($id, self::$sources))
        {
            return self::$sources[$id];
        } else
        {
            return FALSE;
        }
    }

    /**
     * Source Category And Post Category
     * @param array $indexes sources [1, 2, 3]
     * @param array $cats [10, 12, 13]
     * Jumlah harus sama
     * $indexes[0] pairs $cats[0]
     * @return array
     */
    static public function pick_Sources(Array $indexes, Array $cats)
    {
        if (count($indexes) != count($cats))
            return [];

        $x = [];

        foreach ($indexes as $key => $val)
        {
            $t = self::getSource($val);

            if ($t)
            {

                $t['cat'] = $cats[$key];
                $x[$val] = $t;
            }
        }

        return $x;
    }

}
