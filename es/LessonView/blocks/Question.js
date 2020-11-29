import React, { useState } from 'react';

const Question = ({ id, text, answers }) => {
    const [selectedIndex, setSelectedIndex] = useState(null);

    const renderAnswer = (answer, index) => {
        const elementId = `question-${id}-answer-${index}`;
        const classNames = [];
        const isSelected = (index == selectedIndex);
        const marked = false; // @todo Unused

        if (isSelected) {
            const markingClass = answer.correct ? 'is-correct' : 'is-incorrect';
            classNames.push(markingClass);
        }

        return (
            <label for={elementId} className={classNames.join(' ')}>
                <div>
                    <input
                        type="radio"
                        id={elementId}
                        checked={isSelected}
                        disabled={marked}
                        onChange={() => { setSelectedIndex(index)}}
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
        if (selectedIndex === null) {
            return null;
        }

        return (
            <p>{answers[selectedIndex].explanation}</p>
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
