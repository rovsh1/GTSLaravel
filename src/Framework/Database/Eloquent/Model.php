<?php

namespace Custom\Framework\Database\Eloquent;

class Model extends \Illuminate\Database\Eloquent\Model
{
    public function setAttribute($key, $value)
    {
        //dd($this->isClassCastable($key));
//        $this->validateAttribute($key, $value);
        if (method_exists($this, 'setTranslatableAttribute') && $this->isTranslatable($key)) {
            $this->setTranslatableAttribute($key, $value);
        } else {
            parent::setAttribute($key, $value);
        }

        return $this;
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        return $query->where($this->getTable() . '.' . ($field ?? $this->getRouteKeyName()), $value);
    }

//    public function getCasts() {
//        //dd(array_merge($this->getValidationCastTypes(), parent::getCasts()));
//        return array_merge($this->getValidationCastTypes(), parent::getCasts());
//    }
}
