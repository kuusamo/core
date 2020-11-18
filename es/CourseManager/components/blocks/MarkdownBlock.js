import React from 'react';

const MarkdownBlock = props => {
    const onChange = event => {
        props.callback(props.index, 'markdown', event.currentTarget.value);
    }

    return (
        <div>
            <textarea
                className="form-control"
                rows="5"
                value={props.markdown}
                onChange={onChange}
            />
        </div>
    );
}

export { MarkdownBlock };
