<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Block;

use ClosePartnerSdk\Dto\BlockContent;
use ClosePartnerSdk\Dto\BlockGroup;
use ClosePartnerSdk\Dto\BlockId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class BlockProgramTest extends EndpointTestCase
{
    private EventId $eventId;
    private BlockId $blockId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventId = new EventId('CLEV1234567890');
        $this->blockId = new BlockId('CLBP1234567890');
        $this->givenAnAuthorisedClient();
    }

    private function givenGroups(array $groups): void
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/events/' . $this->eventId . '/groups'),
                function (RequestInterface $request) use ($groups) {
                    self::assertEquals('GET', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $this->eventId . '/groups',
                        $request->getUri()->getPath()
                    );

                    return $this->mockResponse($groups);
                }
            );
    }

    /** @test */
    public function read_the_groups_and_their_blocks_from_a_bare_list()
    {
        $this->givenGroups([
            [
                'group_id' => 'CLGR1111111111',
                'name' => 'Avond 1',
                'sort_order' => 1,
                'blocks' => [
                    [
                        'block_id' => 'CLBP1111111111',
                        'type' => 'TEXT',
                        'name' => 'Welcome',
                        'sort_order' => 1,
                        'can_be_updated' => true,
                        'editable_fields' => ['text'],
                        'values' => [
                            'nl-NL' => ['text' => 'Welkom in Weert'],
                            'en-GB' => ['text' => 'Welcome to Weert'],
                        ],
                    ],
                ],
            ],
        ]);

        $groups = $this->givenSdk()->block()->getGroupsForEvent($this->eventId);

        self::assertCount(1, $groups);
        self::assertContainsOnlyInstancesOf(BlockGroup::class, $groups);
        self::assertEquals('CLGR1111111111', $groups[0]->getGroupId());
        self::assertEquals('Avond 1', $groups[0]->getName());
        self::assertEquals(1, $groups[0]->getSortOrder());

        $block = $groups[0]->getBlocks()[0];
        self::assertEquals('CLBP1111111111', (string)$block->getBlockId());
        self::assertEquals('TEXT', $block->getType());
        self::assertTrue($block->canBeUpdated());
        self::assertEquals(['text'], $block->getEditableFields());
        self::assertEquals(['nl-NL', 'en-GB'], $block->getLanguageTags());
        self::assertEquals('Welkom in Weert', $block->getValue('nl-NL', 'text'));
        self::assertEquals('Welcome to Weert', $block->getValue('en-GB', 'text'));
        self::assertNull($block->getValue('de-DE', 'text'));
    }

    /** @test */
    public function a_block_with_nothing_editable_reports_empty_values()
    {
        $this->givenGroups([
            [
                'group_id' => 'CLGR1111111111',
                'name' => 'Avond 1',
                'sort_order' => 1,
                'blocks' => [
                    [
                        'block_id' => 'CLBP2222222222',
                        'type' => 'DIVIDER',
                        'name' => 'Divider',
                        'sort_order' => 2,
                        'can_be_updated' => false,
                        'editable_fields' => [],
                        'values' => [],
                    ],
                ],
            ],
        ]);

        $block = $this->givenSdk()->block()->getGroupsForEvent($this->eventId)[0]->getBlocks()[0];

        self::assertFalse($block->canBeUpdated());
        self::assertSame([], $block->getEditableFields());
        self::assertSame([], $block->getValues());
        self::assertSame([], $block->getLanguageTags());
    }

    /** @test */
    public function updatable_blocks_can_be_picked_out_of_a_group()
    {
        $this->givenGroups([
            [
                'group_id' => 'CLGR1111111111',
                'name' => 'Avond 1',
                'sort_order' => 1,
                'blocks' => [
                    ['block_id' => 'CLBP1111111111', 'type' => 'TEXT', 'name' => 'A', 'sort_order' => 1,
                     'can_be_updated' => true, 'editable_fields' => ['text'], 'values' => []],
                    ['block_id' => 'CLBP2222222222', 'type' => 'DIVIDER', 'name' => 'B', 'sort_order' => 2,
                     'can_be_updated' => false, 'editable_fields' => [], 'values' => []],
                ],
            ],
        ]);

        $group = $this->givenSdk()->block()->getGroupsForEvent($this->eventId)[0];

        self::assertCount(2, $group->getBlocks());
        self::assertCount(1, $group->getUpdatableBlocks());
        self::assertEquals('CLBP1111111111', (string)$group->getUpdatableBlocks()[0]->getBlockId());
    }

    /** @test */
    public function an_event_without_groups_yields_an_empty_list()
    {
        $this->givenGroups([]);

        self::assertSame([], $this->givenSdk()->block()->getGroupsForEvent($this->eventId));
    }

    /** @test */
    public function patch_the_content_of_one_block()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/blocks/' . $this->blockId),
                function (RequestInterface $request) {
                    self::assertEquals('PATCH', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $this->eventId . '/blocks/' . $this->blockId,
                        $request->getUri()->getPath()
                    );
                    self::assertEquals(
                        ['values' => [
                            'nl-NL' => ['text' => 'Welkom in Weert'],
                            'en-GB' => ['text' => 'Welcome to Weert'],
                        ]],
                        json_decode($request->getBody()->getContents(), true)
                    );

                    return $this->mockResponse(['updated' => true]);
                }
            );

        $content = BlockContent::forLanguage('nl-NL', ['text' => 'Welkom in Weert'])
            ->withLanguage('en-GB', ['text' => 'Welcome to Weert']);

        self::assertTrue(
            $this->givenSdk()->block()->updateBlockContent($this->eventId, $this->blockId, $content)
        );
    }

    /** @test */
    public function a_null_clears_a_field_rather_than_being_dropped()
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/blocks/' . $this->blockId),
                function (RequestInterface $request) {
                    $body = json_decode($request->getBody()->getContents(), true);
                    self::assertArrayHasKey('subtitle', $body['values']['nl-NL']);
                    self::assertNull($body['values']['nl-NL']['subtitle']);

                    return $this->mockResponse(['updated' => true]);
                }
            );

        $this->givenSdk()->block()->updateBlockContent(
            $this->eventId,
            $this->blockId,
            BlockContent::forLanguage('nl-NL', ['subtitle' => null])
        );
    }

    /** @test */
    public function adding_a_language_leaves_the_original_content_alone()
    {
        $dutch = BlockContent::forLanguage('nl-NL', ['text' => 'Welkom']);
        $dutch->withLanguage('en-GB', ['text' => 'Welcome']);

        self::assertEquals(['nl-NL'], $dutch->getLanguageTags());
    }
}
