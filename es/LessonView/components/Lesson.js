import React, { useState } from 'react';
import axios from 'axios';

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

const Lesson = ({ courseUri, lesson, defaultUserLesson, previousLesson, nextLesson }) => {
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
    const [loading, setLoading] = useState(false);

    // derive what we should show
    const showAnswers = isGrading === false;
    const disableInputs = showAnswers && (lesson.marking === MARKING_GRADED);
    const showResult = lesson.marking === MARKING_GRADED && userLesson.score !== null && isGrading === false;
    const showBlocks = (lesson.marking !== MARKING_GRADED || isGrading || responses.length > 0);

    const submitQuiz = event => {
        event.preventDefault();
        setLoading(true);

        axios.post(
            `${courseUri}/lessons/${lesson.id}/score`,
            { score: calculateScore() }
        ).then(response => {
            setLoading(false);
            setIsGrading(false);
            setUserLesson(response.data);
        }).catch(error => {
            setLoading(false);
        });
    }

    const resetQuiz = event => {
        event.preventDefault();
        setIsGrading(true);
        setResponses([]);
    }

    const calculateScore = () => {
        const questions = lesson.blocks.filter(block => block.type == 'question').length;
        const correctAnswers = responses.filter(answer => answer && answer.correct).length;

        if (questions === 0) {
            return 100;
        } else if (correctAnswers === 0) {
            return 0;
        }

        return Math.round((correctAnswers / questions) * 100);
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
                <Result
                    score={userLesson.score}
                    passMark={lesson.passMark}
                    reset={resetQuiz}
                />
            );
        }
    }

    return (
        <article>
            <h1>{lesson.name}</h1>
            <div>
                {renderResult()}
                {renderBlocks()}
                <Navigation
                    courseUri={courseUri}
                    previousLesson={previousLesson}
                    nextLesson={nextLesson}
                    marking={lesson.marking}
                    hasCompleted={userLesson.completed}
                    isGrading={isGrading}
                    submitQuiz={submitQuiz}
                    resetQuiz={resetQuiz}
                    loading={loading}
                />
            </div>
        </article>
    );
}

export default Lesson;
