<?php

namespace Politime\TimeSlot;

use Politime\Topic\Topic;

class TimeSlotService
{
    /**
     * @var TimeSlot[]
     */
    public $timeSlots = [];

    /**
     * @var string
     */
    public $url;

    public function __construct($url)
    {
        $this->url = $url;

        if (!file_exists($this->url)) {
            return null;
        }

        $timeSlotsData = file_get_contents($url);

        if ($timeSlotsData === false) {
            return null;
        }

        $timeSlotsData = json_decode($timeSlotsData, true) ?: [];

        $this->timeSlots = [];
        foreach ($timeSlotsData as $day => $topicsData) {
            $topics = [];
            foreach ($topicsData as $topicData) {
                $topics[] = new Topic((int) $topicData['id'], $topicData['name']);
            }

            $this->timeSlots[] = new TimeSlot($day, $topics);
        }
    }

    public function getList()
    {
        $listed = [];

        foreach ($this->timeSlots as $timeSlot) {
            $listed[$timeSlot->day] = [
                $timeSlot->day,
                implode(', ', array_map(function ($topic) {
                    return $topic->name;
                }, $timeSlot->topics)),
            ];
        }

        // Tri du tableau selon la date
        ksort($listed);

        return $listed;
    }

    public function save($day, array $topics, $overwrite = false)
    {
        $toSave = [];

        // Récupération des time slots existant
        foreach ($this->timeSlots as $timeSlot) {
            $toSave[$timeSlot->day] = array_map(function (Topic $topic) {
                return $topic->expose();
            }, $timeSlot->topics);
        }

        $selectedTopics = array_map(function (Topic $topic) {
            return $topic->expose();
        }, $topics);

        if (!isset($toSave[$day])) {
            $toSave[$day] = [];
        }

        // Ajout du nouveau time slot...
        if ($overwrite) {
            // ...avec écrasements...
            $toSave[$day] = $selectedTopics;
        } else {
            // ...ou sans (avec dédoublonnage).
            $toSave[$day] = array_values(array_unique(array_merge($toSave[$day], $selectedTopics), SORT_REGULAR));
        }

        // Sauvegarde
        if (file_put_contents($this->url, json_encode($toSave)) === false) {
            return false;
        }

        return true;
    }
}
