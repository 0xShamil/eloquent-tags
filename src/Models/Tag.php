<?php

namespace Shamil\Tags\Models;

use Illuminate\Database\Eloquent\Model;

use Shamil\Tags\Scopes\TagUsedScopesTrait;

class Tag extends Model
{
    use TagUsedScopesTrait;
}
