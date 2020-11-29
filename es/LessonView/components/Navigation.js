import React, { Fragment } from 'react';

const Navigation = ({ previousLesson, nextLesson, isMarked, hasCompleted }) => {
    const renderPreviousLessonLink = () => {
        if (previousLesson) {
            return (
                <a href={previousLesson.uri}>Previous lesson</a>
            );
        }
    }

    const renderContinueButton = () => {
        if (isMarked) {
            return (
                <p>This lesson is marked by your tutor</p>
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
