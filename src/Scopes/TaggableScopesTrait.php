<?php 

namespace Shamil\Tags\Scopes;

trait TaggableScopesTrait
{
    public function scopeWithAnyTag($query, array $tags)
    {
        return $query->hasTags($tags);
    }

    public function scopeWithAllTags($query, array $tags)
    {
        foreach($tags as $tag) {
            $query->hasTags([$tag]);
        }
        return $query;
    }

    public function scopeHasTags($query, array $tags)
    {
        return $query->whereHas('tags', function ($query) use ($tags) {
            return $query->whereIn('slug', $tags);
        });
    }

    public function scopeWithoutTags($query, array $tags)
    {
        return $query->whereHas('tags', function ($query) use ($tags) {
            return $query->whereNotIn('slug', $tags);
        });
    }
}
