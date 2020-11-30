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
    const [showAnswers, setShowAnswers] = useState(lesson.marking !== MARKING_GRADED);
    const disableInputs = showAnswers && (lesson.marking === MARKING_GRADED);

    // @todo Do we need all of this state?
    const [hideResult, setHideResult] = useState(false);
    const [showBlocks, setShowBlocks] = useState(lesson.marking !== MARKING_GRADED || defaultUserLesson.score === null);
    const [userLesson, setUserLesson] = useState(defaultUserLesson);

    const [responses, setResponses] = useState([]);

    const answerQuestion = (questionId, answerIndex, correct) => {
        const newResponses = [...responses];
        newResponses[questionId] = { answer: answerIndex, correct };
        setResponses(newResponses);
    }

    const showResult = lesson.marking === MARKING_GRADED && userLesson.score !== null &&hideResult === false;

    const submitQuiz = event => {
        event.preventDefault();
        setShowAnswers(true);
        setUserLesson({ score: 50, completed: false });
        setHideResult(false);
    }

    const resetQuiz = event => {
        event.preventDefault();
        setResponses([]);
        setShowAnswers(false);
        setShowBlocks(true);
        setHideResult(true);
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
                userLesson={userLesson}
                previousLesson={previousLesson}
                nextLesson={nextLesson}
                marking={lesson.marking}
                showResult={showResult}
                submitQuiz={submitQuiz}
                resetQuiz={resetQuiz}
            />
        </div>
    );
}

export default Lesson;
