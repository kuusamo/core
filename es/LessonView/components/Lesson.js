import React, { useState } from 'react';

import {
    Audio,
    Download,
    Image,
    Markdown,
    Question,
    Video
} from '../blocks';

import {
    MARKING_GRADED
} from '../../constants'

import Navigation from './Navigation';
import Result from './Result';

const Lesson = ({ lesson, defaultUserLesson, previousLesson, nextLesson }) => {
    // track user progress
    const [userLesson, setUserLesson] = useState(defaultUserLesson);

    // store and set question answers
    const [responses, setResponses] = useState([]);

    const answerQuestion = (questionId, answerIndex, correct) => {
        const newResponses = [...responses];
        newResponses[questionId] = { answer: answerIndex, correct };
        setResponses(newResponses);
    }

    // is the user currently undertaking a graded quiz?
    const defaultMode = lesson.marking == MARKING_GRADED && defaultUserLesson.score === null
    const [isGrading, setIsGrading] = useState(defaultMode);

    // derive what we should show
    const showAnswers = isGrading === false;
    const disableInputs = showAnswers && (lesson.marking === MARKING_GRADED);
    const showResult = lesson.marking === MARKING_GRADED && userLesson.score !== null && isGrading === false;
    const showBlocks = (lesson.marking !== MARKING_GRADED || isGrading || responses.length > 0);

    const submitQuiz = event => {
        event.preventDefault();
        setIsGrading(false);
        setUserLesson({ score: 50, completed: false });
    }

    const resetQuiz = event => {
        event.preventDefault();
        setIsGrading(true);
        setResponses([]);
    }

    const renderBlock = (block) => {
        switch(block.type) {
            case 'audio':
                return(<Audio {...block} />);
            case 'download':
                return(<Download {...block} />);
            case 'image':
                return(<Image {...block} />);
            case 'markdown':
                return(<Markdown {...block} />);
            case 'question':
                return(
                    <Question
                        {...block}
                        disabled={disableInputs}
                        showAnswers={showAnswers}
                        currentAnswer={responses[block.id]}
                        answerQuestion={answerQuestion}
                    />
                );
            case 'video':
                return(<Video {...block} />);
            default:
                console.warn('Unrecognised component: ' + block.type);
        }

        return null;
    }

    const renderBlocks = () => {
        if (showBlocks) {
            return lesson.blocks.map(function(block) {
                const renderedBlock = renderBlock(block);

                if (renderedBlock !== null) {
                    return (
                        <div key={block.bid}>
                            {renderedBlock}
                        </div>
                    );
                }
            });
        }
    }

    const renderResult = () => {
        if (showResult) {
            return (
                <Result score={userLesson.score} reset={resetQuiz} />
            );
        }
    }

    return (
        <div>
            {renderResult()}
            {renderBlocks()}
            <Navigation
                previousLesson={previousLesson}
                nextLesson={nextLesson}
                marking={lesson.marking}
                hasCompleted={userLesson.completed}
                isGrading={isGrading}
                submitQuiz={submitQuiz}
                resetQuiz={resetQuiz}
            />
        </div>
    );
}

export default Lesson;
