<?php

namespace Kuusamo\Vle\Entity\Block;

use Kuusamo\Vle\Entity\Lesson;

/**
 * @Entity
 * @Table(name="blocks")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"audio" = "AudioBlock", "download" = "DownloadBlock", "image" = "ImageBlock", "markdown" = "MarkdownBlock", "question" = "QuestionBlock", "video" = "VideoBlock"})
 */
abstract class Block
{
    const TYPE_AUDIO = 'audio';
    const TYPE_DOWNLOAD = 'download';
    const TYPE_IMAGE = 'image';
    const TYPE_MARKDOWN = 'markdown';
    const TYPE_QUESTION = 'question';
    const TYPE_VIDEO = 'video';

    /**
     * @Column(type="integer")
     * @Id
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Kuusamo\Vle\Entity\Lesson", inversedBy="blocks")
     */
    private $lesson;

    /**
     * @Column(type="integer")
     */
    private $priority;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value)
    {
        $this->id = $value;
    }

    public function getLesson(): Lesson
    {
        return $this->lesson;
    }

    public function setLesson(Lesson $value)
    {
        $this->lesson = $value;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $value)
    {
        $this->priority = $value;
    }
}
