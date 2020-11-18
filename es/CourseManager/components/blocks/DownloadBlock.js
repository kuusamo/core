import React from 'react';

const DownloadBlock = props => {
    const onChange = event => {
        const file = { id: event.currentTarget.value };
        props.callback(props.index, 'file', file);
    }

    return (
        <div>
            <input
                type="number"
                className="form-control"
                value={props.file?.id || ''}
                onChange={onChange}
            />
        </div>
    );
}

export { DownloadBlock };
