import React from 'react';

import {
    Audio,
    Download,
    Image,
    Markdown,
    Question,
    Video
} from '../blocks';

import {
    MARKING_AUTOMATIC
} from '../../constants'

import Navigation from './Navigation';

const Lesson = ({ lesson, previousLesson, nextLesson, hasCompleted }) => {
    const renderBlock = (block) => {
        switch(block.type) {
            case 'audio':
                return(<Audio {...block} />);
            case 'download':
                return(<Download {...block} />);
            case 'image':
                return(<Image {...block} />);
            case 'markdown':
                return(<Markdown {...block} />);
            case 'question':
                return(<Question {...block} />);
            case 'video':
                return(<Video {...block} />);
            default:
                console.warn('Unrecognised component: ' + block.type);
        }

        return null;
    }

    const renderBlocks = () => {
        return lesson.blocks.map(function(block) {
            const renderedBlock = renderBlock(block);

            if (renderedBlock !== null) {
                return (
                    <div key={block.bid}>
                        {renderedBlock}
                    </div>
                );
            }
        });
    }

    const isMarked = lesson.marking !== MARKING_AUTOMATIC;

    return (
        <div>
            {renderBlocks()}
            <Navigation
                previousLesson={previousLesson}
                nextLesson={nextLesson}
                isMarked={isMarked}
                hasCompleted={hasCompleted}
            />
        </div>
    );
}

export default Lesson;
