import React, { Component } from 'react';
import axios from 'axios';

class CreateLesson extends Component {
    constructor(props) {
        super(props);

        this.state = {
            name: ''
        };

        this.textInput = React.createRef();
    }

    onChangeName(event) {
        this.setState({ name: event.currentTarget.value });
    }

    onSubmit(event) {
        event.preventDefault();

        return axios.post(`/admin/courses/lessons`, {
            ...this.state,
            moduleId: this.props.moduleId,
            status: this.props.defaultStatus
        }).then(response => {
            this.setState({ name: '' });
            this.props.addLesson(this.props.moduleIndex, response.data.data);
            this.textInput.current.focus();
        })
        .catch(error => {
            this.props.alertError(error);
        });
    }

    render() {
        return (
            <form onSubmit={this.onSubmit.bind(this)} className="form-inline-wide">
                <input
                    type="text"
                    className="form-control mr-1"
                    placeholder="Name"
                    aria-label="Lesson name"
                    name="name"
                    maxLength={128}
                    value={this.state.name}
                    onChange={this.onChangeName.bind(this)}
                    ref={this.textInput}
                />{" "}
                <button className="btn btn-primary">Create lesson</button>
            </form>
        );
    }
}

export default CreateLesson;
