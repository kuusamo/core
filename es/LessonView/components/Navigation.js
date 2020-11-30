import React, { Fragment } from 'react';

import {
    MARKING_GRADED,
    MARKING_TUTOR
} from '../../constants';

const Navigation = ({ courseUri, previousLesson, nextLesson, marking, hasCompleted, isGrading, submitQuiz, resetQuiz, loading }) => {
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
            if (isGrading) {
                const text = loading ? 'Loading...' : 'Submit answers';
                return (
                    <form onSubmit={submitQuiz}>
                        <button
                            type="submit"
                            className="btn"
                            disabled={loading}>{text}</button>
                    </form>
                );
            }

            if (hasCompleted === true) {
                const nextLink = nextLesson ? nextLesson.uri : courseUri;
                return (<a href={nextLink} className="btn">Continue</a>);
            }

            return (
                <form onSubmit={resetQuiz}>
                    <button type="submit" className="btn">Resit assessment</button>
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
        if (hasCompleted) {
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
