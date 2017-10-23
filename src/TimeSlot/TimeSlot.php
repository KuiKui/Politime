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

    public function __construct($day, array $topics)
    {
        $this->day = $day;
        $this->topics = $topics;
    }

    public function expose()
    {
        return [
            'day' => $this->day,
            'topics' => $this->topics,
        ];
    }
}
