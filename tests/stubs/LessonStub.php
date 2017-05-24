<?php

use Illuminate\Database\Eloquent\Model;

use Shamil\Tags\Taggable;

class LessonStub extends Model
{
	protected $connection = 'testbench';
    use Taggable;

    public $table = 'lessons';
}
