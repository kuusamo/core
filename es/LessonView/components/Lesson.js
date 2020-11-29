import React from 'react';

import {
    Audio,
    Download,
    Image,
    Markdown,
    Question,
    Video
} from '../blocks';

const Lesson = ({ lesson }) => {
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

    return (
        <div>
            {renderBlocks()}
        </div>
    );
}

export default Lesson;
