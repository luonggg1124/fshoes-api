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
}
