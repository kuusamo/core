import React from 'react';

const Result = ({ score, reset }) => {
    return (
        <div className="card mb-3">
            <div className="card-header">
                <p>Your previous score on this assessment was {score}%.</p>
                <p>
                    <button
                        type="button"
                        className="btn"
                        onClick={reset}
                    >Resit assessment</button>
                </p>
            </div>
        </div>
    );
}

export default Result;
