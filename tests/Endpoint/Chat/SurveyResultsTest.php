<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint\Chat;

use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Survey\Survey;
use ClosePartnerSdk\Tests\Endpoint\EndpointTestCase;
use Http\Message\RequestMatcher\RequestMatcher;
use Psr\Http\Message\RequestInterface;

class SurveyResultsTest extends EndpointTestCase
{
    private EventId $eventId;
    private ChatId $chatId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventId = new EventId('CLEV1234567890');
        $this->chatId = new ChatId('CLCH1234567890');
        $this->givenAnAuthorisedClient();
    }

    private function givenSurveys(array $surveys): void
    {
        $this->mockClient
            ->on(
                new RequestMatcher('/survey-results'),
                function (RequestInterface $request) use ($surveys) {
                    self::assertEquals('GET', $request->getMethod());
                    self::assertEquals(
                        '/api/v1/events/' . $this->eventId . '/chats/' . $this->chatId . '/survey-results',
                        $request->getUri()->getPath()
                    );

                    return $this->mockResponse(['surveys' => $surveys]);
                }
            );
    }

    /** @test */
    public function read_a_button_survey_with_its_options()
    {
        $this->givenSurveys([
            [
                'storage_name' => 'travel_mode',
                'type' => 'BUTTON_SURVEY',
                'title' => 'How do you travel?',
                'response_count' => 4,
                'options' => [
                    ['answer' => 'Car', 'value' => 'car', 'count' => 2],
                    ['answer' => 'Train', 'value' => 'train', 'count' => 2],
                ],
                'responses' => [
                    ['nickname' => 'sophie', 'values' => ['car']],
                ],
            ],
        ]);

        $surveys = $this->givenSdk()->chat()->getSurveyResults($this->eventId, $this->chatId);

        self::assertCount(1, $surveys);
        self::assertContainsOnlyInstancesOf(Survey::class, $surveys);

        $survey = $surveys[0];
        self::assertEquals('travel_mode', $survey->getStorageName());
        self::assertEquals(Survey::TYPE_BUTTON, $survey->getType());
        self::assertEquals('How do you travel?', $survey->getTitle());
        self::assertEquals(4, $survey->getResponseCount());

        self::assertCount(2, $survey->getOptions());
        self::assertEquals('Car', $survey->getOptions()[0]->getAnswer());
        self::assertEquals('car', $survey->getOptions()[0]->getValue());
        self::assertEquals(2, $survey->getOptions()[0]->getCount());

        self::assertCount(1, $survey->getResponses());
        self::assertEquals('sophie', $survey->getResponses()[0]->getNickname());
        self::assertEquals(['car'], $survey->getResponses()[0]->getValues());

        self::assertSame([], $survey->getQuestions());
    }

    /** @test */
    public function read_a_slider_survey_with_its_average()
    {
        $this->givenSurveys([
            [
                'storage_name' => 'scores',
                'type' => 'SLIDER_SURVEY',
                'title' => 'Rate the show',
                'response_count' => 2,
                'questions' => [
                    [
                        'storage_name' => 'q_overall',
                        'title' => 'Overall score',
                        'average' => 7.3,
                        'responses' => [
                            ['nickname' => 'sophie', 'value' => 8],
                            ['nickname' => 'daan', 'value' => 6.6],
                        ],
                    ],
                ],
            ],
        ]);

        $survey = $this->givenSdk()->chat()->getSurveyResults($this->eventId, $this->chatId)[0];

        self::assertEquals(Survey::TYPE_SLIDER, $survey->getType());
        self::assertSame([], $survey->getOptions());

        $question = $survey->getQuestions()[0];
        self::assertEquals('q_overall', $question->getStorageName());
        self::assertEquals('Overall score', $question->getTitle());
        self::assertEquals(7.3, $question->getAverage());
        self::assertCount(2, $question->getResponses());
        self::assertEquals('sophie', $question->getResponses()[0]->getNickname());
        self::assertEquals(8, $question->getResponses()[0]->getValue());
    }

    /** @test */
    public function a_text_survey_reports_string_answers_and_no_average()
    {
        $this->givenSurveys([
            [
                'storage_name' => 'feedback',
                'type' => 'TEXT_SURVEY',
                'title' => 'Anything else?',
                'response_count' => 1,
                'questions' => [
                    [
                        'storage_name' => 'q_free',
                        'title' => 'Your thoughts',
                        'responses' => [
                            ['nickname' => 'sophie', 'value' => 'Great show!'],
                        ],
                    ],
                ],
            ],
        ]);

        $question = $this->givenSdk()
            ->chat()
            ->getSurveyResults($this->eventId, $this->chatId)[0]
            ->getQuestions()[0];

        self::assertNull($question->getAverage());
        self::assertEquals('Great show!', $question->getResponses()[0]->getValue());
    }

    /** @test */
    public function a_chat_without_surveys_yields_an_empty_list()
    {
        $this->givenSurveys([]);

        self::assertSame(
            [],
            $this->givenSdk()->chat()->getSurveyResults($this->eventId, $this->chatId)
        );
    }

    /** @test */
    public function read_several_surveys_of_mixed_types()
    {
        $this->givenSurveys([
            ['storage_name' => 'a', 'type' => 'BUTTON_SURVEY', 'title' => 'A', 'response_count' => 1],
            ['storage_name' => 'b', 'type' => 'TEXT_SURVEY', 'title' => 'B', 'response_count' => 0],
        ]);

        $surveys = $this->givenSdk()->chat()->getSurveyResults($this->eventId, $this->chatId);

        self::assertCount(2, $surveys);
        self::assertEquals(Survey::TYPE_BUTTON, $surveys[0]->getType());
        self::assertEquals(Survey::TYPE_TEXT, $surveys[1]->getType());
        self::assertEquals(0, $surveys[1]->getResponseCount());
    }
}
