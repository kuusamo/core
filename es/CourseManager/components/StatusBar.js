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
        <div className={`alert alert-${status.level} d-flex justify-content-between align-items-center`} role="alert">
            <div>{message}</div>
            <div>
                <button type="button" className="close" aria-label="Close" onClick={() => clearStatus()}>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    );
}

export default StatusBar;
