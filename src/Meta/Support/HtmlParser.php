<?php

namespace Gsdk\Meta\Support;

use Gsdk\Meta\MetaTags;

class HtmlParser
{
    public function buildHead(MetaTags $meta, string $html): void
    {
        if (empty($html)) {
            return;
        }
        //if (preg_match('/<title>(.*)<\/title>/iU', $html, $m))
        //	$this->setTitle($m[1]);
        //preg_match_all('/<(\w+)(?:\s.*)>/imU', $html, $mTag, PREG_SET_ORDER);
        preg_match_all('/<(\w+\b)(?:([^>]*)\/?>)(?:([^<]*)(?:<\/\w+>))?/im', $html, $mTag, PREG_SET_ORDER);
        if (!$mTag) {
            return;
        }

        foreach ($mTag as $tag) {
            $attr = [];
            preg_match_all('/(\b(?:\w|-)+\b)\s*=\s*(?:"([^"]*)")/imU', $tag[0], $mAttr, PREG_SET_ORDER);
            if ($mAttr) {
                foreach ($mAttr as $m) {
                    $attr[$m[1]] = $m[2];
                }
            }

            if ($tag[1] === 'title' && isset($tag[3])) {
                $meta->title($tag[3]);
            } elseif ($tag[1] === 'meta') {
                $meta->addMeta($attr);
            } elseif ($tag[1] === 'base') {
                $meta->baseHref($attr['href']);
            } elseif ($tag[1] === 'link') {
                $meta->link($attr);
            } else {
                $meta->addContent($tag[0]);
            }
            //else var_dump($tag[1], $attr);
        }
    }
}
