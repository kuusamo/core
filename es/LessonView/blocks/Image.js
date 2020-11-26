import React from 'react';

const Image = ({ image }) => {
    return (
        <p>
            <img src={"/content/images/crops/700/" + image.filename} alt={image.description} />
        </p>
    );
}

export { Image };
