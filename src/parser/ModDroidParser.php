<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParserWithGallery;

class ModDroidParser extends AbstractParserWithGallery
{

    const idx = "index-";

    protected $spec_table;
    protected $dl_link = [];

    public function __construct()
    {
        $this->attach = TRUE;
    }

    protected function getPostDetail()
    {
        $doc = $this->curlGrabContent();
        $html = $this->make_DOM($doc);

        $table = pq('main#primary table:first');
        $table->removeAttr('*');
        $table->find('a[rel=tag]')->contentsUnwrap();
        $table->find('img')->removeAttr('style')->attr('src', 'https://www.zoneapps.us/wp-content/uploads/2021/08/google-play.png');
        $this->spec_table = trim($table->htmlOuter());

        $dl = pq('main#primary > a.btn.btn-secondary.btn-block.mb-3');
        $this->dl_link['href'] = trim($dl->attr('href'));

        $dltext = pq('h2#download');
        $this->dl_link['intro'] = trim($dltext->text());
        //file_put_contents('/tmp/oni.txt', var_export($dl->attr('href'), true));

        $node = pq('div.entry-content');
        $counter_p = 0;

        foreach (pq($node)->find('*') as $i => $el)
        {
            $index = self::idx . $i;

            if (pq($el)->is('p'))
            {
                $pel = pq($el);
                if ($pel->find('img')->length > 0)
                {
                    $s = $pel->find('img');
                    $img = trim(pq($s)->attr('src'));
                    $excerpt = $this->title;
                    $caption = $this->title;

                    $image = $this->setPhotoSource($img, $excerpt, $caption);
                    $this->gallery[$index] = $image;
                    $this->p[$index] = $image;
                } else
                {
                    $p = trim($pel->text());
                    if ($p)
                    {
                        $this->p[$index] = $p;
                        $counter_p++;
                    }
                }
            } elseif (pq($el)->is('h2'))
            {
                //h2 diganti h3 saja
                $hel = pq($el);
                $h = trim($hel->text());
                if ($h)
                {
                    $this->p[$index] = "<h3>{$h}</h3>";
                    $counter_p++;
                }
            } elseif (pq($el)->is('h3'))
            {
                //h3 diganti h4 saja
                $hel = pq($el);
                $h = trim($hel->text());
                if ($h)
                {
                    $this->p[$index] = "<h4>{$h}</h4>";
                    $counter_p++;
                }
            } elseif (pq($el)->is('h4'))
            {
                //h4 diganti h5 saja
                $hel = pq($el);
                $h = trim($hel->text());
                if ($h)
                {
                    $this->p[$index] = "<h5>{$h}</h5>";
                    $counter_p++;
                }
            }
        }
        //temp content
        $this->content = "Lorem ipsum";
    }

    public function grab()
    {
        $this->getPostDetail();
        $this->_getFeaturedImage();
        $this->_getHost();
        $this->generateSeoMetaDescription();
    }

    protected function _getFeaturedImage()
    {
        if ($this->gallery)
        {
            //ternyata tidak selalu 0 ferguso
            //$idx = self::idx . "0";
            //pake reset
            $this->featured_image = reset($this->gallery)['image'];
        }
    }

    protected function generateSeoMetaDescription()
    {
        $meta_description = pq('meta[name="description"]')->attr('content');
        $this->meta_description = trim($meta_description);
    }

    public function buildPostWithGallery()
    {
        $content = '';

        if ($this->spec_table)
        {
            $content .= '<div class="spec-table">';
            $content .= $this->spec_table;
            $content .= '</div>';
        }

        $content .= '<div class="app-detail">';
        foreach ($this->p as $idx => $p)
        {
            if (isset($this->gallery[$idx]))
            {

                $img = $this->gallery[$idx];
                $content .= '<p>';
                $content .= $img['html'];
                $content .= '</p>';
            } else
            {
                if (preg_match('|<\s*h[1-6](?:.*)>(.*)</\s*h|Ui', $p))
                {
                    $content .= $p;
                } else
                {
                    $content .= '<p>';
                    $content .= $p;
                    $content .= '</p>';
                }
            }
        }
        $content .= '</div>';

        if ($this->dl_link)
        {
            $content .= '<div class="download-link">';
            $content .= '<h3>' . $this->dl_link['intro'] . '</h3>';
            $content .= '<a href="' . $this->dl_link['href'] . '" rel="nofollow" target="_blank">Download</a>';
            $content .= '</div>';
        }

        return $content;
    }

    protected function curlGrabContent()
    {
        $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
        );
        $doc = @file_get_contents($this->url, false, $context);

        return $doc;
    }

}
