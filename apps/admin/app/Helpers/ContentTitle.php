<?php

namespace App\Admin\Helpers;

use Gsdk\Meta\Meta;

class ContentTitle
{
    public static function withAddButton(?string $addUrl, string $addText = 'Добавить', ?string $id = null): string
    {
        $html = '<div class="content-header">';
        $html .= '<div class="title">' . self::title() . '</div>';
        if ($addUrl) {
            $html .= '<button type="button" class="btn btn-add" data-url="' . $addUrl . '"><i class="icon">add</i>' . $addText . '</button>';
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
