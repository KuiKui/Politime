<?php

namespace Politime\Topic;

class TopicService
{
    /**
     * @var Topic[]
     */
    public $topics = [];

    public function __construct($url)
    {
        $topicsData = file_get_contents($url);

        if ($topicsData === false) {
            return null;
        }

        $topicsData = json_decode($topicsData, true);

        $this->topics = [];
        foreach ($topicsData as $topic) {
            $this->topics[] = new Topic((int) $topic['id'], $topic['name'], (bool) $topic['visible']);
        }
    }

    public function getList($all = false)
    {
        if ($all) {
            return $this->topics;
        }

        $returnedSubjects = [];
        foreach ($this->topics as $topic) {
            if (!$topic->visible) {
                continue;
            }
            $returnedSubjects[] = $topic;
        }

        return $returnedSubjects;
    }

    public function searchByName(array $names)
    {
        $found = [];

        foreach ($this->getList() as $topic) {
            if (in_array($topic->name, $names)) {
                $found[] = $topic;
            }
        }

        return $found;
    }
}
