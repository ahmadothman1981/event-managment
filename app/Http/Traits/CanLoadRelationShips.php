<?php
namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;

trait CanLoadRelationShips
{
    public function LoadRelationShips(
        Model | QueryBuilder | EloquentBuilder $for, ?array $relations = null
    ): Model | QueryBuilder | EloquentBuilder
    {
        $relations = $relations ?? $this->$relations ?? [];
        
        foreach ($relations as $relation) {
            $for->when(
                $this->shouldIncludeRelation($relation),
                fn($q)=> $for instanceof Model ? $for->load($relation) : $q->with($relation)
            );
        }

        return $for;
    }

    protected function shouldIncludeRelation(string $relation): bool
    {
        //get current request using request function, it has query method to get query paraameters
        $include = request()->query('include');
        if(!$include) {
            return false;
        }
        $relations = array_map('trim', explode(',', $include)); 
        return in_array($relation, $relations);
        
    }
}