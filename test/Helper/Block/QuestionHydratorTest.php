<?php

namespace Kuusamo\Vle\Test\Helper\Block;

use Kuusamo\Vle\Helper\Block\QuestionHydrator;
use PHPUnit\Framework\TestCase;

class QuestionHydratorTest extends TestCase
{
    public function testHydrate()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\QuestionBlock');
        $blockMock->expects($this->once())->method('setText');
        $blockMock->expects($this->once())->method('setAnswers');

        $hydrator = new QuestionHydrator;
        $hydrator->hydrate($blockMock, [
            'text' => 'Favourite food?',
            'answers' => ['Chocolate', 'Strawberries', 'Salad']
        ]);
    }

    public function testValidateValid()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\QuestionBlock');
        $blockMock->method('getText')->willReturn('Favourite food?');
        $blockMock->method('getAnswers')->willReturn([
            ['text' => 'Chocolate', 'correct' => true]
        ]);

        $hydrator = new QuestionHydrator;

        $this->assertSame(true, $hydrator->validate($blockMock));
    }

    /**
     * @expectedException Kuusamo\Vle\Helper\Block\ValidationException
     */
    public function testValidateNoQustion()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\QuestionBlock');
        $blockMock->method('getText')->willReturn('');

        $hydrator = new QuestionHydrator;

        $hydrator->validate($blockMock);
    }

    /**
     * @expectedException Kuusamo\Vle\Helper\Block\ValidationException
     */
    public function testValidateNoAnswers()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\QuestionBlock');
        $blockMock->method('getText')->willReturn('Favourite food?');
        $blockMock->method('getAnswers')->willReturn([]);

        $hydrator = new QuestionHydrator;

        $hydrator->validate($blockMock);
    }

    /**
     * @expectedException Kuusamo\Vle\Helper\Block\ValidationException
     */
    public function testValidateAnswersMissingText()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\QuestionBlock');
        $blockMock->method('getText')->willReturn('Favourite food?');
        $blockMock->method('getAnswers')->willReturn([
            ['text' => '', 'correct' => true]
        ]);

        $hydrator = new QuestionHydrator;

        $hydrator->validate($blockMock);
    }

    /**
     * @expectedException Kuusamo\Vle\Helper\Block\ValidationException
     */
    public function testValidateAnswersNoCorrectAnswer()
    {
        $blockMock = $this->createMock('Kuusamo\Vle\Entity\Block\QuestionBlock');
        $blockMock->method('getText')->willReturn('Favourite food?');
        $blockMock->method('getAnswers')->willReturn([
            ['text' => '', 'correct' => false]
        ]);

        $hydrator = new QuestionHydrator;

        $hydrator->validate($blockMock);
    }
}
