<?php

namespace Politime\Topic;

class Topic
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var bool
     */
    public $visible;

    public function __construct(int $id, string $name, bool $visible = true)
    {
        $this->id = $id;
        $this->name = $name;
        $this->visible = $visible;
    }

    public function expose() : array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'visible' => $this->visible,
        ];
    }
}
