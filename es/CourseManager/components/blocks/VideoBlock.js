import React from 'react';

const VideoBlock = props => {
    const onChangeProvider = event => {
        props.callback(props.index, 'provider', event.currentTarget.value);
    }

    const onChangeProviderId = event => {
        props.callback(props.index, 'providerId', event.currentTarget.value);
    }

    return (
        <div>
            <select className="form-control mr-1" aria-label="Provider" value={props.provider} onChange={onChangeProvider}>
                <option value="vimeo">Vimeo</option>
                <option value="youtube">YouTube</option>
            </select>
            <input type="text" className="form-control" aria-label="Provider ID" placeholder="Provider ID" value={props.providerId} onChange={onChangeProviderId} />
        </div>
    );
}

export { VideoBlock };
