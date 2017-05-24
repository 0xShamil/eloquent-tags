<?php 

use Illuminate\Foundation\Testing\DatabaseMigrations;

class TagsStringUsageTest extends TestCase
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
        $this->lesson->tag(['laravel', 'php']);

        $this->assertCount(2, $this->lesson->tags);

        foreach(['Laravel', 'PHP'] as $tag) {
        	$this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }

    /**
     * @test
     */
    public function can_untag_lesson()
    {
        $this->lesson->tag(['laravel', 'php', 'react']);
        
        $this->lesson->untag(['laravel']);

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
    	$this->lesson->tag(['laravel', 'php', 'react']);
    	$this->lesson->untag();

    	$this->lesson->load('tags');

    	$this->assertCount(0, $this->lesson->tags);
    }

    /**
     * @test 
     */
    public function can_retag_lesson_tags()
    {
    	$this->lesson->tag(['laravel', 'php', 'react']);
    	$this->lesson->retag(['laravel', 'symfony', 'dev-tools']);

    	$this->lesson->load('tags');

    	$this->assertCount(3, $this->lesson->tags);

    	foreach(['Laravel', 'Symfony', 'Dev tools'] as $tag) {
        	$this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }

    /**
     * @test
     */
    public function non_existing_tags_are_ignored_on_tagging()
    {
        $this->lesson->tag(['laravel', 'cpp', 'react']);

        $this->assertCount(2, $this->lesson->tags);

        foreach(['Laravel', 'React'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }

    /**
     * @test
     */
    public function inconsistent_tag_cases_are_normalised()
    {
        $this->lesson->tag(['Laravel', 'RedIS', 'Dev tools']);

        $this->assertCount(3, $this->lesson->tags);

        foreach(['Laravel', 'Redis', 'Dev tools'] as $tag) {
            $this->assertContains($tag, $this->lesson->tags->pluck('name'));
        }
    }
}
