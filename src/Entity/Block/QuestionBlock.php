<?php

namespace Kuusamo\Vle\Entity\Block;

use JsonSerializable;

/**
 * @Entity
 * @Table(name="blocks_questions")
 */
class QuestionBlock extends Block implements JsonSerializable
{
    /**
     * @Column(type="string")
     */
    private $text;

    /**
     * @Column(type="json")
     */
    private $answers;

    public function __construct()
    {
        $this->answers = [];
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $value)
    {
        $this->text = $value;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function setAnswers(array $value)
    {
        $this->answers = $value;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'type' => Block::TYPE_QUESTION,
            'text' => $this->text,
            'answers' => $this->answers
        ];
    }
}
