<?php

namespace SheDied\parser;

use SheDied\parser\AbstractParser;

abstract class AbstractParserWithGallery extends AbstractParser
{

    protected $gallery = [];
    protected $p = [];
    protected $attach = FALSE;

    public function getGallery()
    {
        return $this->gallery;
    }

    public function attach()
    {
        return $this->attach;
    }

    public function setPhotoSource($img, $excerpt, $caption)
    {
        return [
            'image' => $img,
            'excerpt' => $excerpt,
            'caption' => $caption
        ];
    }

    public function updatePhotoSource($idx, $photosource)
    {
        if (isset($this->gallery[$idx]))
            $this->gallery[$idx] = $photosource;
    }

    public function buildPostWithGallery()
    {
        $content = '';
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
                $content .= '<p>';
                $content .= $p;
                $content .= '</p>';
            }
        }

        return $content;
    }

    public function getParagraph()
    {
        return $this->p;
    }

}
