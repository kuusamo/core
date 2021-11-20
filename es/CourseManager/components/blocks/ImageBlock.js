import React, { useState } from 'react';
import axios from 'axios';

const ImageBlock = props => {
    const [file, setFile] = useState();
    const [description, setDescription] = useState();
    const [canUpload, setCanUpload] = useState(false);

    const onChange = event => {
        setFile(event.target.files[0]);
        setCanUpload(true);
    }

    const updateDescription = event => {
        setDescription(event.target.value);
    }

    const upload = event => {
        let formData = new FormData();
        formData.append('file', file);
        formData.append('description', description);

        setCanUpload(false);

        axios.post(`/admin/images/inline-upload`, formData)
            .then(response => {
                props.callback(props.index, 'image', response.data.data);
            }).catch(error => {
                setCanUpload(true);
            });
    }

    const renderPreview = () => {
        if (props.image) {
            const { filename, description } = props.image;

            return (
                <div className="admin-block-image-preview">
                    <img
                        src={"/content/images/crops/200/" + filename}
                        alt={description}
                    />
                </div>
            );
        }
    }

    return (
        <div className="admin-block-image">
            {renderPreview()}
            <div className="admin-block-image-form">
                <input
                    type="file"
                    className="form-control mb-1"
                    aria-label="Image file"
                    onChange={onChange}
                />
                <input
                    type="text"
                    className="form-control mb-1"
                    aria-label="Description"
                    placeholder="Description"
                    value={description}
                    onChange={updateDescription}
                />
                <button
                    className="btn btn-primary"
                    disabled={!canUpload}
                    onClick={upload}>Upload</button>
            </div>
        </div>
    );
}

export { ImageBlock };
