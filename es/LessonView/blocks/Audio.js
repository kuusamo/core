import React from 'react';

const Audio = ({ file }) => {
    return (
        <p>
            <audio
                src={"/content/files/" + file.filename}
                preload="none"
                controls={true} />
        </p>
    );
}

export { Audio };
