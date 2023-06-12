import React, { Component } from 'react';
import axios from 'axios';

class CreateModule extends Component {
    constructor(props) {
        super(props);

        this.state = {
            name: '',
            description: ''
        };
    }

    onChangeName(event) {
        this.setState({ name: event.currentTarget.value });
    }

    onChangeDescription(event) {
        this.setState({ description: event.currentTarget.value });
    }

    onSubmit(event) {
        event.preventDefault();

        axios.post(`/admin/courses/${this.props.courseId}/modules`, {
                ...this.state
            })
            .then(response => {
                this.setState({ name: '', description: '' });
                this.props.addModule(response.data.data);
            })
            .catch(error => {
                this.props.alertError(error);
            });
    }

    render() {
        return (
            <form onSubmit={this.onSubmit.bind(this)}>
                <input
                    type="text"
                    className="form-control mb-1 mr-1"
                    placeholder="Name"
                    aria-label="Module name"
                    name="name"
                    maxLength={128}
                    value={this.state.name}
                    onChange={this.onChangeName.bind(this)}
                />{" "}
                <input
                    type="text"
                    className="form-control mb-1 mr-1"
                    placeholder="Description"
                    aria-label="Module description"
                    name="description"
                    value={this.state.description}
                    onChange={this.onChangeDescription.bind(this)}
                />{" "}
                <button className="btn mb-1">Create module</button>
            </form>
        );
    }
}

export default CreateModule;
