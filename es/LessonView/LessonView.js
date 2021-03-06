import React from 'react';

import Lesson from './components/Lesson';

const LessonView = ({ courseUri, lessonData, userLessonData, previousLesson, nextLesson }) => {
    return (
        <Lesson
            courseUri={courseUri}
            lesson={lessonData}
            defaultUserLesson={userLessonData}
            previousLesson={previousLesson}
            nextLesson={nextLesson}
        />
    );
}

export default LessonView;
