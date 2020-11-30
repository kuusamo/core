import React from 'react';

import Lesson from './components/Lesson';

const LessonView = ({ lessonData, userLessonData, previousLesson, nextLesson }) => {
    return (
        <Lesson
            lesson={lessonData}
            defaultUserLesson={userLessonData}
            previousLesson={previousLesson}
            nextLesson={nextLesson}
        />
    );
}

export default LessonView;
