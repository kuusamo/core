import React, { useState } from 'react';

import {
    MARKING_GRADED
} from '../../constants';

const Question = ({ id, text, answers, disabled, currentAnswer, answerQuestion, showAnswers }) => {
    const renderAnswer = (answer, index) => {
        const elementId = `question-${id}-answer-${index}`;
        const classNames = [];
        const isSelected = (currentAnswer && currentAnswer.answer == index);

        if (showAnswers && isSelected) {
            const markingClass = answer.correct ? 'is-correct' : 'is-incorrect';
            classNames.push(markingClass);
        }

        if (disabled) {
            classNames.push('is-disabled');
        }

        return (
            <label for={elementId} className={classNames.join(' ')}>
                <div>
                    <input
                        type="radio"
                        id={elementId}
                        checked={isSelected}
                        disabled={disabled}
                        onChange={() => answerQuestion(id, index, answer.correct)}
                    />
                </div>
                <div>{answer.text}</div>
            </label>
        );
    }

    const renderAnswers = () => {
        return answers.map((answer, index) => {
            return renderAnswer(answer, index);
        });
    }

    const renderExplanation = () => {
        if (currentAnswer === null || currentAnswer === undefined || showAnswers === false) {
            return null;
        }

        return (
            <p>{answers[currentAnswer.answer].explanation}</p>
        );
    }

    return (
        <div className="course-question card">
            <div className="card-header">
                <h2>{text}</h2>
            </div>
            <div className="card-body">
                {renderAnswers()}
                {renderExplanation()}
            </div>
        </div>
    );
}

export { Question };
