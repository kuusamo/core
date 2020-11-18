import React from 'react';

const AudioBlock = props => {
    const onChange = event => {
        const download = { id: event.currentTarget.value };
        props.callback(props.index, 'download', download);
    }

    return (
        <div>
            <input
                type="number"
                className="form-control"
                value={props.download?.id || ''}
                onChange={onChange}
            />
        </div>
    );
}

export { AudioBlock };
