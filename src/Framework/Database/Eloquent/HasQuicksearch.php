<?php

namespace Custom\Framework\Database\Eloquent;

trait HasQuicksearch
{

//    protected $quicksearch = ['id'];

    public function fieldIdentifier($name)
    {
//        static $seoColumns = ['title', 'text', 'head'];

        if ($name == 'id')
            return $this->table . '.' . $name;
        elseif (isset($this->translatable) && in_array($name, $this->translatable))
            return $this->getTranslationTable() . '.' . $name;
//        elseif (in_array($name, $seoColumns) && method_exists($this, 'seo'))
//            return 'seo_content.' . $name;
        elseif (in_array($name, $this->fillable))
            return $this->table . '.' . $name;
        else
            return $name;
    }

    public static function scopeQuicksearch($query, $term)
    {
        if (empty($term))
            return;

        $model = $query->getModel();

        $query->where(function ($query) use ($term, $model) {
            foreach ($model->quicksearch as $arg) {
                $a = explode('.', $arg);
                $column = $a[1] ?? $a[0];
                $t = $term;
                if (str_starts_with($column, '%'))
                    $t = '%' . $t;
                if (str_ends_with($column, '%'))
                    $t = $t . '%';

                if (isset($a[1]))
                    $identifier = $a[0] . '.' . trim($column, '%');
                else
                    $identifier = $model->fieldIdentifier(trim($column, '%'));

                $query->orWhere($identifier, 'like', $t);
            }
        });
    }

}
