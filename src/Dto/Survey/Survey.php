<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Survey;

class Survey
{
    public const TYPE_BUTTON = 'BUTTON_SURVEY';
    public const TYPE_SLIDER = 'SLIDER_SURVEY';
    public const TYPE_TEXT = 'TEXT_SURVEY';

    private string $storageName;
    private string $type;
    private string $title;
    private int $responseCount;
    private array $options;
    private array $responses;
    private array $questions;

    /**
     * @param SurveyOption[]   $options
     * @param SurveyResponse[] $responses
     * @param SurveyQuestion[] $questions
     */
    public function __construct(
        string $storageName,
        string $type,
        string $title,
        int $responseCount,
        array $options = [],
        array $responses = [],
        array $questions = []
    ) {
        $this->storageName = $storageName;
        $this->type = $type;
        $this->title = $title;
        $this->responseCount = $responseCount;
        $this->options = $options;
        $this->responses = $responses;
        $this->questions = $questions;
    }

    public function getStorageName(): string
    {
        return $this->storageName;
    }

    /**
     * @return string one of the TYPE_ constants
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getResponseCount(): int
    {
        return $this->responseCount;
    }

    /**
     * @return SurveyOption[] the offered answers, for a button survey
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return SurveyResponse[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }

    /**
     * @return SurveyQuestion[] the questions, for a slider or text survey
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }

    /**
     * Which of options, responses and questions are filled depends on the
     * survey type, so each is read as optional.
     */
    public static function buildFromResponseObject(\StdClass $obj): self
    {
        $options = [];
        foreach ($obj->options ?? [] as $option) {
            $options[] = SurveyOption::buildFromResponseObject($option);
        }

        $responses = [];
        foreach ($obj->responses ?? [] as $response) {
            $responses[] = SurveyResponse::buildFromResponseObject($response);
        }

        $questions = [];
        foreach ($obj->questions ?? [] as $question) {
            $questions[] = SurveyQuestion::buildFromResponseObject($question);
        }

        return new self(
            $obj->storage_name,
            $obj->type,
            $obj->title,
            (int)($obj->response_count ?? 0),
            $options,
            $responses,
            $questions
        );
    }
}
