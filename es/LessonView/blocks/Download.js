import React from 'react';

const Download = ({ file }) => {
    return (
        <p>
            <a href={"/content/files/" + file.filename}>Download {file.name}</a>
        </p>
    );
}

export { Download };
