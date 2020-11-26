<?php

namespace Kuusamo\Vle\Helper\Block;

use Kuusamo\Vle\Entity\Block\QuestionBlock;

class QuestionHydrator extends Hydrator
{
    public function hydrate(QuestionBlock $block, array $data)
    {
        $block->setText($data['text']);
        $block->setAnswers($data['answers']);
    }

    public function validate(QuestionBlock $block): bool
    {
        if ($block->getText() == '') {
            throw new ValidationException('No question text');
        }

        if (sizeof($block->getAnswers()) == 0) {
            throw new ValidationException('No answers provided');
        }

        $correctAnswers = 0;

        foreach ($block->getAnswers() as $answer) {
            if ($answer['correct']) {
                $correctAnswers++;
            }

            if (!isset($answer['text']) || $answer['text'] == '') {
                throw new ValidationException('Each answer must have text');
            }
        }

        if ($correctAnswers == 0) {
            throw new ValidationException('At least one answer must be correct');
        }

        return true;
    }
}
