import React from 'react';
import ReactDOM from 'react-dom';

import CourseManager from './components/CourseManager';

window.courseManager = (htmlElement, courseId, previewUri) => {
    ReactDOM.render(
        <CourseManager courseId={courseId} previewUri={previewUri} />,
        htmlElement
    );
}
