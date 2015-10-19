<?php

/**
* 图片操作
*/
class GetImage
{
    
    /**
     * [获取文章内容之第一张图片地址 getFirstImage description]
     * @param  [type] $content [文章内容]
     * @return [type]          [图片地址]
     */
    public static function getFirstImage($content = null)
    {
        preg_match ("<img.*src=[\"](.*?)[\"].*?>", $content, $match);

        if ($match) {
            $imgSrc = $match[1];
        } else {
            $imgSrc = 'images/rand/'.rand(0,9).'.jpg';
        }
        return $imgSrc;
    }
}