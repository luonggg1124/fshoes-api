<?php

namespace App\Http\Traits;

trait ResourceSummary
{
    public function shouldSummaryRelation(string $relation):bool
    {
        $summary = request()->query('summary');
        if(!$summary) return false;
        $relations = array_map('trim', explode(',', $summary));
        return in_array($relation, $relations);
    }
    public function includeTimes(string $relation):bool
    {
        $times = request()->query('times');
        if(!$times) return false;
        $relations = array_map('trim', explode(',', $times));
        return in_array($relation, $relations);
    }
    public function exceptFields(array $resource):array
    {
        $exceptFields = request()->query('except');
        if(!$exceptFields) return $resource;
        $fields = array_map('trim', explode(',', $exceptFields));
        foreach ($fields as $f){
            if(isset($resource[$f])) unset($resource[$f]);
        }
        return $resource;
    }
}
