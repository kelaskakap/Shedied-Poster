<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParserWithGallery;

class DetikParser extends AbstractParserWithGallery
{

    const idx = "index-";

    public function __construct()
    {
        $this->attach = TRUE;
    }

    protected function getPostDetail()
    {
        $url = $this->getUrl();
        $url = $url . "?single=1";
        $this->setUrl($url);

        $doc = $this->curlGrabContent();
        $html = $this->make_DOM($doc);

        $node = pq('div.detail__body-text');
        $node->find('div.clearfix')->remove();
        $node->find('table.linksisip')->remove();
        $node->find('div#parallax1')->remove();
        $node->find('div[id*=div-gpt]')->remove();
        $node->find('p em')->remove();


        $counter_p = 0;
        foreach (pq($node)->find('*') as $i => $el)
        {
            $index = self::idx . $i;

            if (pq($el)->is('p'))
            {
                $pel = pq($el);
                $p = trim($pel->text());
                if ($p)
                {
                    $this->p[$index] = $p;
                    $counter_p++;
                }
            } elseif (pq($el)->is('table.pic_artikel_sisip_table'))
            {
                $table = pq($el);
                foreach ($table->find('img') as $imgx)
                {
                    $img = pq($imgx)->attr('src');
                    $excerpt = $this->title;
                    $caption = $this->title;

                    $image = $this->setPhotoSource($img, $excerpt, $caption);
                    $this->gallery[$index] = $image;
                    $this->p[$index] = $image;
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
        $this->_getTags();
        $this->generateSeoMetaDescription();
    }

    protected function _getFeaturedImage()
    {
        $img = pq('article figure.detail__media-image img');
        $this->featured_image = trim($img->attr('src'));
    }

    protected function generateSeoMetaDescription()
    {
        $meta_description = pq('meta[name="description"]')->attr('content');
        $this->meta_description = trim($meta_description);
    }

    protected function _getTags()
    {
        foreach (pq('div.detail__body-tag a.nav__item') as $a)
        {
            $tag = trim(pq($a)->text());
            $this->tags[] = ucwords($tag);
        }
    }

}
