import React from 'react';

const Download = ({ file }) => {
    return (
        <p>
            <div className="download">
                <i class="fas fa-file-alt" aria-hidden="true"></i>
                <a href={"/content/files/" + file.filename}>Download {file.name}</a>
            </div>
        </p>
    );
}

export { Download };
