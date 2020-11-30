import React from 'react';

const Result = ({ score, passMark, reset }) => {
    const renderCongrats = () => {
        if (score >= passMark) {
            return 'Great job!';
        }
    }

    return (
        <div className="card mb-3">
            <div className="card-header">
                <p>
                    Your score on this assessment was {score}%. To pass, you must achieve {passMark}%.  {renderCongrats()}
                </p>
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
