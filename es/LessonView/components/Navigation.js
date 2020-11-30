import React, { Fragment } from 'react';

import {
    MARKING_GRADED,
    MARKING_TUTOR
} from '../../constants';

const Navigation = ({ userLesson, previousLesson, nextLesson, marking, showResult, submitQuiz, resetQuiz }) => {
    const renderPreviousLessonLink = () => {
        if (previousLesson) {
            return (
                <a href={previousLesson.uri}>Previous lesson</a>
            );
        }
    }

    const renderContinueButton = () => {
        if (marking === MARKING_TUTOR) {
            return (
                <p>This lesson is marked by your tutor</p>
            );
        }

        if (marking === MARKING_GRADED) {
            if (userLesson.completed === true) {
                return (<a href={nextLesson.uri} className="btn">Continue</a>);
            }

            if (showResult) {
                return (
                    <form onSubmit={resetQuiz}>
                        <button type="submit" className="btn">Resit assessment</button>
                    </form>
                );
            }

            return (
                <form onSubmit={submitQuiz}>
                    <button type="submit" className="btn">Submit answers</button>
                </form>
            );
        }

        return (
            <form method="post">
                {renderSubmitButton()}
            </form>
        );
    }

    const renderSubmitButton = () => {
        if (userLesson.hasCompleted) {
            return (
                <button type="submit" className="btn">Mark as incomplete</button>
            );
        }

        return (
            <Fragment>
                <button type="submit" className="btn">Complete and continue</button>
                <input type="hidden" name="continue" value="true" />
            </Fragment>
        );
    }

    const renderNextLessonLink = () => {
        if (nextLesson) {
            return (
                <a href={nextLesson.uri}>Next lesson</a>
            );
        }
    }

    return (
        <div className="course-navigation" role="navigation">
            <div>{renderPreviousLessonLink()}</div>
            <div className="course-navigation-continue">
                {renderContinueButton()}
            </div>
            <div>{renderNextLessonLink()}</div>
        </div>
    );
}

export default Navigation;
