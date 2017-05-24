<?php 

use Illuminate\Foundation\Testing\DatabaseMigrations;

class TagsModelUsageTest extends TestCase
{
	use DatabaseMigrations;
	
	protected $lesson;

	public function setUp()
	{
		parent::setUp();

		foreach(['PHP', 'Laravel', 'Symfony', 'React', 'Redis', 'Dev tools'] as $tag) {
			\TagStub::create([
				'name' => $tag,
				'slug' => str_slug($tag),
				'count' => 0
			]);
		}

		$this->lesson = \LessonStub::create([
			'title' => 'A lesson title',
		]);
	}

    /**
     * @test
     */
    public function can_tag_lesson()
    {
        $this->lesson->tag(\TagStub::where('slug', 'laravel')->first());

        $this->assertCount(1, $this->lesson->tags);

        $this->assertContains('Laravel', $this->lesson->tags->pluck('name'));
    }

    /**
     * @test
     */
    public function can_tag_lesson_with_collection_of_tags()
    {
        $tags = \TagStub::whereIn('slug', ['laravel', 'php', 'react'])->get();

        $this->lesson->tag($tags);

        $this->assertCount(3, $this->lesson->tags);

        foreach(['Laravel', 'PHP', 'React'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }

    /**
     * @test
     */
    public function can_untag_lesson_tags()
    {
        $tags = \TagStub::whereIn('slug', ['laravel', 'php', 'react'])->get();

        $this->lesson->tag($tags);

        //Using Laravel
        $this->lesson->untag($tags->first());

        $this->assertCount(2, $this->lesson->tags);

        foreach(['PHP', 'React'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }

    }

    /**
     * @test
     */
    public function can_untag_all_lesson_tags()
    {
        $tags = \TagStub::whereIn('slug', ['laravel', 'php', 'react'])->get();

        $this->lesson->tag($tags);

        $this->lesson->untag();

        $this->lesson->load('tags');

        $this->assertCount(0, $this->lesson->tags);

    }

    /**
     * @test
     */
    public function can_retag_lesson_tags()
    {
        $tags = \TagStub::whereIn('slug', ['laravel', 'php', 'react'])->get();
        $toRetag = \TagStub::whereIn('slug', ['laravel', 'symfony', 'redis'])->get();

        $this->lesson->tag($tags);
        $this->lesson->retag($toRetag);

        $this->lesson->load('tags');

        $this->assertCount(3, $this->lesson->tags);

        foreach(['Laravel', 'Symfony', 'Redis'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }

    /**
     * @test 
     */
    public function non_models_are_fltered_when_using_collection()
    {
        $tags = \TagStub::whereIn('slug', ['laravel', 'php', 'react'])->get();

        $tags->push('not a tag model');

        $this->lesson->tag($tags);

        $this->assertCount(3, $this->lesson->tags);

        foreach(['Laravel', 'PHP', 'React'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }
}
