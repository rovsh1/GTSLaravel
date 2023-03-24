<?php

namespace App\Admin\Helpers;

use Gsdk\Meta\Meta;

class ContentTitle
{
    public static function withAddButton(?string $addUrl, string $addText = 'Добавить'): string
    {
        $html = '<div class="content-header">';
        $html .= '<div class="title">' . self::title() . '</div>';
        if ($addUrl) {
            $html .= '<a href="' . $addUrl . '">' . $addText . '</a>';
        }
        $html .= '</div>';
        return $html;
    }

    public static function default(): string
    {
        $html = '<div class="content-header">';
        $html .= '<div class="title">' . self::title() . '</div>';
        $html .= self::actions();
        $html .= '</div>';
        return $html;
    }


    public static function actions(): string
    {
        if (app()->resolved('menu.actions')) {
            return app('menu.actions')->render();
        } else {
            return '';
        }
    }

    public static function title(): string
    {
        return Meta::getTitle();
    }
}
