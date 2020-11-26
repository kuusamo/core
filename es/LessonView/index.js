import React from 'react';
import ReactDOM from 'react-dom';
import LessonView from './LessonView';

const htmlElement = document.querySelector('#lesson-view');

ReactDOM.render(
    <LessonView
        lessonData={lessonData}
    />,
    htmlElement
);
