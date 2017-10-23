<?php

namespace Politime\TimeSlot;

use Politime\Topic\Topic;

class TimeSlot
{
    /**
     * @var string
     */
    public $day;

    /**
     * @var Topic[]
     */
    public $topics;

    public function __construct(string $day, array $topics)
    {
        $this->day = $day;
        $this->topics = $topics;
    }

    public function expose() : array
    {
        return [
            'day' => $this->day,
            'topics' => $this->topics,
        ];
    }
}
