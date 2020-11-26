import React from 'react';
import MarkdownToJsx from 'markdown-to-jsx';

const Markdown = ({ markdown }) => {
    return (
        <MarkdownToJsx children={markdown} />
    );
}

export { Markdown };
