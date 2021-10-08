<?php

namespace SheDied\helpers;

use SheDied\helpers\Numbers;
use SheDied\PojokJogjaController;

/**
 * appszone.us
 */
class Songo extends Numbers
{

    const APPSZONE = 'appszone.us';

    public function __construct()
    {
        $this->set_Need_Gallery(true);
    }

    public function fetchPostLinks(PojokJogjaController $controller)
    {
        $doc = $this->fetchLinks($controller->getUrl());
        \phpQuery::newDocument($doc);

        $postlinks = [];

        if ($this->source_MODDROID($controller))
        {
            foreach (pq('main#primary a.archive-post') as $a)
            {
                $link = pq($a)->attr('href');
                $title = pq($a)->text();
                $title = preg_replace('/\s+/', ' ', $title);
                $postlinks[] = array("title" => trim($title), "link" => trim($link), 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory());

                if ($this->enough($postlinks, $controller))
                {
                    break;
                }
            }
        }

        $controller->setPostLinks($postlinks);
    }

    public function switchParsers(PojokJogjaController $controller)
    {
        if ($this->source_MODDROID($controller))
            $this->parser = 'SheDied\parser\ModDroidParser';
    }

    static public function sources()
    {
        $sources = self::sources_moddroid();
        return $sources;
    }

    public function firstRunURL($url, $sourceId, PojokJogjaController $controller)
    {
        if (empty($this->fr) && !$this->isfr)
            return $url;

        $default = 20;

        $t = isset($this->fr[$sourceId]) ? (int) $this->fr[$sourceId] : $default;
        $Page = $t > 1 ? 'page/' . $t : '';

        $t--;
        $this->fr[$sourceId] = $t;

        return $url . $Page;
    }

    protected function source_MODDROID(PojokJogjaController $controller)
    {
        return $controller->getNewsSrc() > 1 && $controller->getNewsSrc() < 43;
    }

    public function fetchCustomUrls(PojokJogjaController $controller)
    {
        ;
    }

    public function scanURL(PojokJogjaController $controller, $params = array())
    {
        ;
    }

    protected static function sources_moddroid()
    {
        $sources[2] = ['name' => 'MOD DROID: GAME: Action', 'url' => 'https://moddroid.co/games/action'];
        $sources[3] = ['name' => 'MOD DROID: GAME: Adventure', 'url' => 'https://moddroid.co/games/adventure'];
        $sources[4] = ['name' => 'MOD DROID: GAME: Arcade', 'url' => 'https://moddroid.co/games/arcade'];
        $sources[5] = ['name' => 'MOD DROID: GAME: Board', 'url' => 'https://moddroid.co/games/board'];
        $sources[6] = ['name' => 'MOD DROID: GAME: Card', 'url' => 'https://moddroid.co/games/card'];
        $sources[7] = ['name' => 'MOD DROID: GAME: Casual', 'url' => 'https://moddroid.co/games/casual'];
        $sources[8] = ['name' => 'MOD DROID: GAME: Fighting', 'url' => 'https://moddroid.co/games/fighting'];
        $sources[9] = ['name' => 'MOD DROID: GAME: Gambling', 'url' => 'https://moddroid.co/games/gambling'];
        $sources[10] = ['name' => 'MOD DROID: GAME: Logic', 'url' => 'https://moddroid.co/games/logic'];
        $sources[11] = ['name' => 'MOD DROID: GAME: MOBA', 'url' => 'https://moddroid.co/games/moba'];
        $sources[12] = ['name' => 'MOD DROID: GAME: Music', 'url' => 'https://moddroid.co/games/music'];
        $sources[13] = ['name' => 'MOD DROID: GAME: Puzzle', 'url' => 'https://moddroid.co/games/puzzle'];
        $sources[14] = ['name' => 'MOD DROID: GAME: Racing', 'url' => 'https://moddroid.co/games/racing'];
        $sources[15] = ['name' => 'MOD DROID: GAME: Role Playing', 'url' => 'https://moddroid.co/games/role-playing'];
        $sources[16] = ['name' => 'MOD DROID: GAME: RPG', 'url' => 'https://moddroid.co/games/rpg'];
        $sources[17] = ['name' => 'MOD DROID: GAME: Simulation', 'url' => 'https://moddroid.co/games/simulation'];
        $sources[18] = ['name' => 'MOD DROID: GAME: Sports', 'url' => 'https://moddroid.co/games/sports'];
        $sources[19] = ['name' => 'MOD DROID: GAME: Strategy', 'url' => 'https://moddroid.co/games/strategy'];
        $sources[20] = ['name' => 'MOD DROID: GAME: Survival', 'url' => 'https://moddroid.co/games/survival'];
        $sources[21] = ['name' => 'MOD DROID: APPS: Art & Design', 'url' => 'https://moddroid.co/apps/art-design'];
        $sources[22] = ['name' => 'MOD DROID: APPS: Auto & Vehicles', 'url' => 'https://moddroid.co/apps/auto-vehicles'];
        $sources[23] = ['name' => 'MOD DROID: APPS: Books & Reference', 'url' => 'https://moddroid.co/apps/books-reference'];
        $sources[24] = ['name' => 'MOD DROID: APPS: Business', 'url' => 'https://moddroid.co/apps/business'];
        $sources[25] = ['name' => 'MOD DROID: APPS: Communication', 'url' => 'https://moddroid.co/apps/communication'];
        $sources[26] = ['name' => 'MOD DROID: APPS: Education', 'url' => 'https://moddroid.co/apps/education'];
        $sources[27] = ['name' => 'MOD DROID: APPS: Emulator', 'url' => 'https://moddroid.co/apps/emulator'];
        $sources[28] = ['name' => 'MOD DROID: APPS: Entertainment', 'url' => 'https://moddroid.co/apps/entertainment'];
        $sources[29] = ['name' => 'MOD DROID: APPS: Health', 'url' => 'https://moddroid.co/apps/health'];
        $sources[30] = ['name' => 'MOD DROID: APPS: Lifestyle', 'url' => 'https://moddroid.co/apps/lifestyle'];
        $sources[31] = ['name' => 'MOD DROID: APPS: Maps & Navigation', 'url' => 'https://moddroid.co/apps/maps-navigation'];
        $sources[32] = ['name' => 'MOD DROID: APPS: Music & Audio', 'url' => 'https://moddroid.co/apps/music-audio'];
        $sources[33] = ['name' => 'MOD DROID: APPS: News & Magazine', 'url' => 'https://moddroid.co/apps/news-magazines'];
        $sources[34] = ['name' => 'MOD DROID: APPS: Personalization', 'url' => 'https://moddroid.co/apps/personalization'];
        $sources[35] = ['name' => 'MOD DROID: APPS: Photography', 'url' => 'https://moddroid.co/apps/photography'];
        $sources[36] = ['name' => 'MOD DROID: APPS: Productivity', 'url' => 'https://moddroid.co/apps/productivity'];
        $sources[37] = ['name' => 'MOD DROID: APPS: Social', 'url' => 'https://moddroid.co/apps/social'];
        $sources[38] = ['name' => 'MOD DROID: APPS: System', 'url' => 'https://moddroid.co/apps/system'];
        $sources[39] = ['name' => 'MOD DROID: APPS: Tools', 'url' => 'https://moddroid.co/apps/tools'];
        $sources[40] = ['name' => 'MOD DROID: APPS: Travel & Local', 'url' => 'https://moddroid.co/apps/travel-local'];
        $sources[41] = ['name' => 'MOD DROID: APPS: Video Players', 'url' => 'https://moddroid.co/apps/video-players-editors'];
        $sources[42] = ['name' => 'MOD DROID: APPS: Weather', 'url' => 'https://moddroid.co/apps/weather'];

        return $sources;
    }

    public function getIdentity()
    {
        return static::APPSZONE;
    }

    protected function fetchLinks($url)
    {

        $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
        );
        $doc = @file_get_contents($url, false, $context);
        if (function_exists('mb_convert_encoding'))
        {
            $doc = mb_convert_encoding($doc, "HTML-ENTITIES", "UTF-8");
        }

        return $doc;
    }

}
