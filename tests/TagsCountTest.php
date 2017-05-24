<?php 

use Illuminate\Foundation\Testing\DatabaseMigrations;

class TagsCountTest extends TestCase
{
	use DatabaseMigrations;
	
	protected $lesson;

	public function setUp()
	{
		parent::setUp();

		$this->lesson = \LessonStub::create([
			'title' => 'A lesson title',
		]);
	}

    /**
     * @test
     */
    public function tag_count_incremented_when_tagged()
    {
        $tag = \TagStub::create([
            'name' => 'Laravel',
            'slug' => str_slug('Laravel'),
            'count' => 0
        ]);
        
        $this->lesson->tag(['laravel']);

        $tag = $tag->fresh();

        $this->assertEquals(1, $tag->count);
    }

    /**
     * @test
     */
    public function tag_count_decremented_when_untagged()
    {
        $tag = \TagStub::create([
            'name' => 'Laravel',
            'slug' => str_slug('Laravel'),
            'count' => 10
        ]);
        
        $this->lesson->tag(['laravel']);
        $this->lesson->untag(['laravel']);

        $tag = $tag->fresh();

        $this->assertEquals(10, $tag->count);
    }

    /**
     * @test
     */
    public function tag_count_is_not_decremented_beyond_zero()
    {
        $tag = \TagStub::create([
            'name' => 'Laravel',
            'slug' => str_slug('Laravel'),
            'count' => 0
        ]);
        
        $this->lesson->untag(['laravel']);

        $tag = $tag->fresh();

        $this->assertEquals(0, $tag->count);
    }

    /**
     * @test
     */
    public function tag_count_does_not_increment_if_already_exists()
    {
        $tag = \TagStub::create([
            'name' => 'Symfony',
            'slug' => str_slug('Symfony'),
            'count' => 0
        ]);
        
        $this->lesson->tag(['symfony']);
        $this->lesson->retag(['symfony']);
        $this->lesson->tag(['symfony']);
        $this->lesson->tag(['symfony']);

        $tag = $tag->fresh();

        $this->assertEquals(1, $tag->count);
    }

}
