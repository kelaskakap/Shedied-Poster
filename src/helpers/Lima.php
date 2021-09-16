<?php

namespace SheDied\helpers;

use SheDied\helpers\Numbers;
use SheDied\PojokJogjaController;
use SheDied\parser\jogja\OLXParser;

class Lima extends Numbers
{

    const JOGJA_TRADE = 'jogja.trade';

    public function fetchCustomUrls(PojokJogjaController $controller)
    {
        ;
    }

    public function switchParsers(PojokJogjaController $controller)
    {

        if ($this->source_OLX($controller))
        {
            $this->parser = 'SheDied\parser\jogja\OLXParser';
        } elseif ($this->source_LOKER15($controller))
        {
            $this->parser = 'SheDied\parser\jogja\Loker15Parser';
        }
    }

    public function fetchPostLinks(PojokJogjaController $controller)
    {

        $doc = $this->fetchLinks($controller->getUrl());
        $postlinks = $controller->getPostLinks();

        $node = \phpQuery::newDocument($doc);

        if ($this->source_OLX($controller))
        {
            foreach ($node->find('li.EIR5N a') as $a)
            {

                $link = pq($a)->attr('href');
                $title = pq($a)->find('span[data-aut-id="itemTitle"]')->text();
                $loc = pq($a)->find('span[data-aut-id="item-location"]')->text();

                $title = $this->clean_whitespaces($title);
                $loc = $this->clean_whitespaces($loc);
                $linktitle = "{$title} - {$loc}";

                $postlinks[] = array("title" => $linktitle, "link" => OLXParser::make_URL(trim($link)), 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory());

                if ($this->enough($postlinks, $controller) && !$controller->auto())
                {
                    break;
                }
            }
        } elseif ($this->source_LOKER15($controller))
        {
            $outer = $node->find('div.blog-posts.hfeed div.post-outer');

            foreach ($outer->find('article h2.post-title a') as $a)
            {
                $link = pq($a)->attr('href');
                $title = pq($a)->text();

                $postlinks[] = array("title" => trim($title), "link" => trim($link), 'src' => $controller->getNewsSrc(), 'cat' => $controller->getCategory());

                if ($this->enough($postlinks, $controller) && !$controller->auto())
                {
                    break;
                }
            }
        }

        $controller->setPostLinks($postlinks);
    }

    static public function sources()
    {

        $sources = self::sources_olx();
        $sources += self::sources_loker15();
        return $sources;
    }

    protected static function sources_olx()
    {
        $sources[1] = ['name' => 'OLX - Mobil > Mobil Bekas', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/mobil-bekas_c198?sorting=desc-creation'];
        $sources[2] = ['name' => 'OLX - Mobil > Aksesori', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/aksesori_c4760?sorting=desc-creation'];
        $sources[3] = ['name' => 'OLX - Mobil > Audio Mobil', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/audio-mobil_c4762?sorting=desc-creation'];
        $sources[4] = ['name' => 'OLX - Mobil > Spare Part', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/spare-part_c4759?sorting=desc-creation'];
        $sources[5] = ['name' => 'OLX - Mobil > Velg & Ban', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/velg-dan-ban_c4761?sorting=desc-creation'];
        $sources[6] = ['name' => 'OLX - Mobil > Truk & Kendaraan Komersial', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/truk-kendaraan-komersial_c4662?sorting=desc-creation'];
        $sources[7] = ['name' => 'OLX - Properti > Dijual: Rumah & Apartemen', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/dijual-rumah-apartemen_c5158?sorting=desc-creation'];
        $sources[8] = ['name' => 'OLX - Properti > Disewakan: Rumah & Apartemen', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/disewakan-rumah-apartemen_c5160?sorting=desc-creation'];
        $sources[9] = ['name' => 'OLX - Properti > Tanah', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/tanah_c4827?sorting=desc-creation'];
        $sources[10] = ['name' => 'OLX - Properti > Indekos', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/indekos_c4833?sorting=desc-creation'];
        $sources[11] = ['name' => 'OLX - Properti > Dijual: Bangunan Komersil', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/dijual-bangunan-komersil_c5154?sorting=desc-creation'];
        $sources[12] = ['name' => 'OLX - Properti > Disewakan: Bangunan Komersil', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/disewakan-bangunan-komersil_c5156?sorting=desc-creation'];
        $sources[13] = ['name' => 'OLX - Jasa & Lowongan Kerja > Lowongan', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/lowongan_c226?sorting=desc-creation'];
        $sources[14] = ['name' => 'OLX - Jasa & Lowongan Kerja > Jasa', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/jasa_c228?sorting=desc-creation'];
        $sources[15] = ['name' => 'OLX - Kantor & Industri > Peralatan Kantor', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/peralatan-kantor_c203?sorting=desc-creation'];
        $sources[16] = ['name' => 'OLX - Kantor & Industri > Perlengkapan Usaha', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/perlengkapan-usaha_c5090?sorting=desc-creation'];
        $sources[17] = ['name' => 'OLX - Kantor & Industri > Mesin & Keperluan Industri', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/mesin-keperluan-industri_c4846?sorting=desc-creation'];
        $sources[18] = ['name' => 'OLX - Elektronik & Gadget > Handphone', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/handphone_c208?filter=condition_eq_bekas&sorting=desc-creation'];
        $sources[19] = ['name' => 'OLX - Elektronik & Gadget > Laptop', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/laptop-komputer_c213?filter=condition_eq_bekas%2Ctype_eq_elektronik-gadget-komputer-laptop&sorting=desc-creation'];
        $sources[20] = ['name' => 'OLX - Rumah Tangga > Perlengkapan Rumah', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/perlengkapan-rumah_c202?sorting=desc-creation'];
        $sources[21] = ['name' => 'OLX - Rumah Tangga > Mebel', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/mebel_c4835?sorting=desc-creation'];
        $sources[22] = ['name' => 'OLX - Rumah Tangga > Dekorasi Rumah', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/dekorasi-rumah_c4836?sorting=desc-creation'];
        $sources[23] = ['name' => 'OLX - Hobi & Olahraga > Hewan Peliharaan', 'url' => 'https://www.olx.co.id/yogyakarta-di_g2000032/hewan-peliharaan_c235?sorting=desc-creation'];

        return $sources;
    }

    protected static function sources_loker15()
    {
        $sources[25] = ['name' => 'LOWONGANKERJA15 - CPNS KESEHATAN', 'url' => 'https://www.lowongankerja15.com/search/label/Kemenkes'];
        $sources[26] = ['name' => 'LOWONGANKERJA15 - CPNS KEMENKEU', 'url' => 'https://www.lowongankerja15.com/search/label/CPNS%20Kementerian%20Keuangan'];
        $sources[27] = ['name' => 'LOWONGANKERJA15 - CPNS KEMDIKBUD', 'url' => 'https://www.lowongankerja15.com/search/label/CPNS%20Kemendikbud'];
        $sources[28] = ['name' => 'LOWONGANKERJA15 - BANK BTN', 'url' => 'https://www.lowongankerja15.com/search/label/Bank%20BTN'];
        $sources[29] = ['name' => 'LOWONGANKERJA15 - BANK BNI', 'url' => 'https://www.lowongankerja15.com/search/label/Bank%20BNI'];
        $sources[30] = ['name' => 'LOWONGANKERJA15 - BANK BANK MANDIRI', 'url' => 'https://www.lowongankerja15.com/search/label/Bank%20Mandiri'];
        $sources[31] = ['name' => 'LOWONGANKERJA15 - BANK BRI', 'url' => 'https://www.lowongankerja15.com/search/label/Bank%20BRI'];
        $sources[32] = ['name' => 'LOWONGANKERJA15 - BANK BI', 'url' => 'https://www.lowongankerja15.com/search/label/Bank%20Indonesia'];
        $sources[33] = ['name' => 'LOWONGANKERJA15 - BUMN', 'url' => 'https://www.lowongankerja15.com/search/label/BUMN'];

        return $sources;
    }

    public function source_OLX(PojokJogjaController $controller)
    {
        return $controller->getNewsSrc() > 0 && $controller->getNewsSrc() < 24;
    }

    public function source_LOKER15(PojokJogjaController $controller)
    {
        return $controller->getNewsSrc() > 24 && $controller->getNewsSrc() < 34;
    }

    public function scanURL(PojokJogjaController $controller, $params = array())
    {
        ;
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

    protected function clean_whitespaces($text)
    {

        return preg_replace('/\s+/', ' ', trim($text));
    }

    public function getIdentity()
    {
        return static::JOGJA_TRADE;
    }

}
