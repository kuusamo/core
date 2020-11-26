import React from 'react';

const Video = ({ provider, providerId }) => {
    const renderVimeo = () => {
        return (
            <p>
                <div className="responsive-embed">
                    <iframe src={"https://player.vimeo.com/video/" + providerId}
                        width="640"
                        height="480"
                        frameborder="0"
                        allowFullScreen={true}></iframe>
                </div>
            </p>
        );
    }

    const renderYouTube = () => {
        return (
            <p>
                <div className="responsive-embed">
                    <iframe
                        width="853"
                        height="480"
                        src={"https://www.youtube-nocookie.com/embed/" + providerId}
                        frameborder="0"
                        allowFullScreen={true}></iframe>
                </div>
            </p>

        );
    }

    switch (provider) {
        case 'vimeo':
            return renderVimeo();
        case 'youtube':
            return renderYouTube();
    }

    console.warn('Unsupported video provider: ' + provider);
    return null;
}

export { Video };
