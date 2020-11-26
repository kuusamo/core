import React from 'react';

import Lesson from './components/Lesson';

const LessonView = ({ lessonData }) => {
    return (
        <Lesson lesson={lessonData} />
    );
}

export default LessonView;
