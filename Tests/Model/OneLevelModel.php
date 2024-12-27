<?php

namespace Sandronimus\NelmioFormGetParameterPluginBundle\Tests\Model;

use DateTime;

class OneLevelModel
{
    public ?string $choice = null;
    public ?array $multipleChoice = null;
    public ?int $integer = null;
    public ?float $number = null;
    public ?bool $checkbox = null;
    public ?DateTime $date = null;
    public ?DateTime $dateTime = null;
    public ?array $collection = null;
    public ?string $text = null;

    /**
     * Text field with documentation in summary
     */
    public ?string $fieldWithSummaryDoc = null;

    /**
     * @var string|null Text field with variable documentation
     */
    public ?string $fieldWithVarDoc = null;

    public ?string $fieldWithFormTypeDoc = null;
}
