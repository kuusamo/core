<?php

namespace Kuusamo\Vle\Helper\Transfer;

use Kuusamo\Vle\Entity\Course;
use Kuusamo\Vle\Entity\Lesson;
use Kuusamo\Vle\Entity\Module;
use Kuusamo\Vle\Helper\Block\BlockFactory;
use Kuusamo\Vle\Helper\Block\HydratorFactory;
use Kuusamo\Vle\Helper\Transfer\Export;
use Doctrine\ORM\EntityManager;

class Import
{
    const SUPPORTED_BLOCKS = [
        'markdown',
        'question',
        'video'
    ];

    private $db;
    private $report;

    /**
     * Setup a database connection.
     */
    public function __construct(EntityManager $db)
    {
        $this->db = $db;
        $this->report = new Report;
    }

    /**
     * This is the primary method to call when running an import.
     */
    public function import(string $jsonStr)
    {
        $jsonData = json_decode($jsonStr, true);
        return $this->hydrateCourse($jsonData['course']);
    }

    /**
     * Get the report.
     */
    public function getReport(): Report
    {
        return $this->report;
    }

    private function hydrateCourse(array $data): Course
    {
        $course = new Course;
        $course->setName(sprintf('%s (Imported)', $data['name']));
        $course->setSlug(sprintf('%s-%s', $data['slug'], date('his')));
        $course->setQualification($data['qualification']);
        $course->setCertificateAvailable($data['certificateAvailable']);
        $course->setPrivacy($data['privacy']);
        $course->setWelcomeText($data['welcomeText']);

        foreach ($data['modules'] as $module) {
            $this->hydrateModule($course, $module);
        }

        $this->db->persist($course);
        return $course;
    }

    private function hydrateModule(Course $course, array $data): Module
    {
        $module = new Module;
        $module->setCourse($course);
        $module->setName($data['name']);
        $module->setDescription($data['description']);
        $module->setPriority($data['priority']);
        $module->setStatus($data['status']);
        $module->setDelay($data['delay']);

        foreach ($data['lessons'] as $lesson) {
            $this->hydrateLesson($module, $lesson);
        }

        $this->db->persist($module);
        return $module;
    }

    private function hydrateLesson(Module $module, array $data): Lesson
    {
        $lesson = new Lesson;
        $lesson->setCourse($module->getCourse());
        $lesson->setModule($module);
        $lesson->setName($data['name']);
        $lesson->setPriority($data['priority']);
        $lesson->setStatus($data['status']);
        $lesson->setMarking($data['marking']);
        $lesson->setPassMark($data['passMark']);

        $priority = 1;

        foreach ($data['blocks'] as $blockData) {
            if (in_array($blockData['type'], self::SUPPORTED_BLOCKS)) {
                $block = BlockFactory::create($blockData['type']);
                $block->setLesson($lesson);
                $block->setPriority($priority);

                $hydrator = HydratorFactory::create(
                    $blockData['type'],
                    $this->db
                );

                $hydrator->hydrate($block, $blockData);
                $this->db->persist($block);

                $priority++;
            } else {
                $this->report->notice(sprintf(
                    'Skipping block %s from lesson %s',
                    $blockData['type'],
                    $lesson->getName()
                ));
            }
        }

        $this->db->persist($lesson);
        return $lesson;
    }
}
