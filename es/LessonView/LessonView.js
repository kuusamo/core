import React from 'react';

import Lesson from './components/Lesson';

const LessonView = ({ lessonData, previousLesson, nextLesson, hasCompleted }) => {
    return (
        <Lesson
            lesson={lessonData}
            previousLesson={previousLesson}
            nextLesson={nextLesson}
            hasCompleted={hasCompleted}
        />
    );
}

export default LessonView;
