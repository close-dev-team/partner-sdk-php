<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Dto\Survey;

class SurveyQuestion
{
    private string $storageName;
    private string $title;
    private ?float $average;
    private array $responses;

    /**
     * @param float|null        $average   only a slider survey reports one
     * @param QuestionResponse[] $responses
     */
    public function __construct(string $storageName, string $title, ?float $average, array $responses)
    {
        $this->storageName = $storageName;
        $this->title = $title;
        $this->average = $average;
        $this->responses = $responses;
    }

    public function getStorageName(): string
    {
        return $this->storageName;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return float|null null unless the survey is a slider survey
     */
    public function getAverage(): ?float
    {
        return $this->average;
    }

    /**
     * @return QuestionResponse[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }

    public static function buildFromResponseObject(\StdClass $obj): self
    {
        $responses = [];
        foreach ($obj->responses ?? [] as $response) {
            $responses[] = QuestionResponse::buildFromResponseObject($response);
        }

        return new self(
            $obj->storage_name,
            $obj->title,
            isset($obj->average) ? (float)$obj->average : null,
            $responses
        );
    }
}
