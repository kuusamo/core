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
            Provider: <select className="form-control" value={props.provider} onChange={onChangeProvider}>
                <option value="vimeo">Vimeo</option>
            </select>
            Provider ID: <input type="text" className="form-control" value={props.providerId} onChange={onChangeProviderId} />
        </div>
    );
}

export { VideoBlock };
