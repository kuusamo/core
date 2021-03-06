import React from 'react';

const defaultMessage = level => {
    switch (level) {
        case 'success':
            return 'Success';
        case 'danger':
            return 'Unknown error';
    }
}

const StatusBar = ({ status, clearStatus }) => {
    const message = status.message || defaultMessage(status.level);

    return (
        <div className={`alert alert-${status.level} alert-flex mb-3`} role="alert">
            <div>{message}</div>
            <div>
                <button type="button" className="cm-close" aria-label="Close" onClick={() => clearStatus()}>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    );
}

export default StatusBar;
