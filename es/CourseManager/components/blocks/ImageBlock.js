import React from 'react';

const ImageBlock = props => {
    const onChange = event => {
        const image = { id: event.currentTarget.value };
        props.callback(props.index, 'image', image);
    }

    return (
        <div>
            <input
                type="number"
                className="form-control"
                aria-label="Image ID"
                placeholder="Image ID"
                value={props.image?.id || ''}
                onChange={onChange}
            />
        </div>
    );
}

export { ImageBlock };
