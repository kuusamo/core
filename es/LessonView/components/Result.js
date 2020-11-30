import React, { useEffect, useRef } from 'react';

const Result = ({ score, passMark, reset }) => {
    const renderCongrats = () => {
        if (score >= passMark) {
            return 'Great job!';
        }
    }

    const resultRef = useRef();

    React.useEffect(() => {
        console.log('Effect');
        resultRef.current.scrollIntoView({ behavior: "smooth" });
    }, []);

    return (
        <div className="card mb-3" ref={resultRef}>
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
