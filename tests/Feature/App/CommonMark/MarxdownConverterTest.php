<?php

namespace Tests\Feature\App\CommonMark;

use Tests\TestCase;
use Illuminate\Support\Str;

class MarxdownConverterTest extends TestCase
{
    public function test_it_renders_headings_with_ids() : void
    {
        $this->assertStringContainsString(
            'id="dabc-def"',
            Str::marxdown("# D'abc def-._&")
        );
    }

    public function test_it_adds_rel_attribute_values_to_external_links() : void
    {
        $this->assertStringContainsString(
            'rel="nofollow noopener noreferrer" target="_blank"',
            Str::marxdown('[Apple](https://www.apple.com)')
        );
    }

    public function test_it_does_not_add_any_attribute_to_internal_links() : void
    {
        $this->assertStringContainsString(
            'href="' . url('/') . '">',
            Str::marxdown('[Foo](' . url('/') . ')')
        );

        $this->assertStringContainsString(
            'href="#foo"',
            Str::marxdown('[Foo](#foo)')
        );
    }

    public function test_it_adds_a_click_event_to_affiliate_links() : void
    {
        $this->assertStringContainsString(
            '@click="window.fathom?.trackGoal(\'LBJL4VHK\', 0)"',
            Str::marxdown('[Foo](' . url('/recommends/foo') . '')
        );
    }

    public function test_it_adds_a_click_event_to_external_links() : void
    {
        $this->assertStringContainsString(
            "window.fathom?.trackGoal('SMD2GKMN', 0)",
            Str::marxdown('[Apple](https://www.apple.com)')
        );
    }

    public function test_it_renders_tweets() : void
    {
        $this->assertStringContainsString(
            'blockquote',
            Str::marxdown('https://twitter.com/benjamincrozat/status/1621932404066631681?s=61&t=w2avQneIIJBEMC01xpggqA')
        );
    }

    public function test_it_renders_vimeo() : void
    {
        $this->assertStringContainsString(
            'iframe',
            Str::marxdown('https://vimeo.com/783455878')
        );
    }

    public function test_it_renders_youtube() : void
    {
        $this->assertStringContainsString(
            'iframe',
            Str::marxdown('https://www.youtube.com/watch?v=68DCCdzFxjM')
        );
    }
}