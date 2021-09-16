<?php

namespace SheDied\parser\jogja;

use SheDied\parser\AbstractParser;

class Loker15Parser extends AbstractParser
{

    protected function getPostDetail()
    {
        $doc = $this->curlGrabContent();
        $html = $this->make_DOM($doc);

        $node = pq('article div.post-body.entry-content');
        $node->find('script')->remove();
        $node->find('noscript')->remove();
        $node->find('meta')->remove();
        $node->find('ins')->remove();
        $node->find('div.googlepublisherads')->remove();
        $node->find('*')->not('a')->removeAttr('*');
        $node->find('table')->remove();
        $node->find('div span')->wrap('<p>')->contentsUnwrap();
        $node->find('div')->contentsUnwrap();
        $node->find('br')->remove();
        $node->find('h1')->remove();
        $node->find('img')->remove();
        $node->find('iframe')->remove();

        foreach ($node->find('b') as $b)
        {
            if (pq($b)->text() == 'LOWONGANKERJA15.COM')
            {
                pq($b)->text('JOGJA TRADING');
            }
        }

        foreach ($node->find('a') as $a)
        {
            $text = trim(pq($a)->text());
            syslog(LOG_DEBUG, "-apa? $text");
            if (preg_match('/\bdaftar\b/', $text))
            {
                pq($a)->removeAttr('rel')->attr('rel', 'nofollow noopener');
                pq($a)->removeAttr('target')->attr('target', '_blank');
            } else
            {
                pq($a)->contentsUnwrap();
            }
        }

        $this->content = $node->html();
        
        $this->content = preg_replace('/\bLOWONGANKerja15.com\b/i', 'JOGJA TRADING', $this->content);

        $this->removeEmptyHTMLTags();
        $this->removeHTMLComments();
        $this->aggregateContent();
    }

    protected function _getTags()
    {

        foreach (pq('div.post-footer div.tags a') as $t)
        {
            $this->tags[] = trim(pq($t)->text());
        }
    }

    public function grab()
    {
        $this->getPostDetail();
        $this->_getTags();
        $this->_getHost();
        $this->generateSeoMetaDescription();

        file_put_contents('/tmp/oni.txt', var_export($this->content, true));
    }

    protected function generateSeoMetaKeywords()
    {
        $desc = "";
        $desc .= ucfirst($this->title);
        $desc .= " " . implode(' ', $this->tags);
        $desc .= " {$this->source_category} {$this->category_name}.";

        $this->meta_description = $desc;
    }

}
