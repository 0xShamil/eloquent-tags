<?php

use Illuminate\Database\Eloquent\Model;

use Shamil\Tags\Scopes\TagUsedScopesTrait;

class TagStub extends Model
{
	protected $connection = 'testbench';
    use TagUsedScopesTrait;

    public $table = 'tags';
}
