import React, { Component, Fragment } from 'react';
import axios from 'axios';
import Lesson from './Lesson';
import CreateLesson from './CreateLesson';
import { STATUS_ACTIVE, STATUS_HIDDEN } from '../constants';

class Module extends Component {
    constructor(props) {
        super(props);

        this.state = {
            editing: false,
            name: this.props.name,
            description: this.props.description,
            status: this.props.status,
            delay: this.props.delay
        };
    }

    toggleEditing() {
        this.setState({ editing: !this.state.editing });
    }

    cancelEdit() {
        event.preventDefault();
        this.setState({
            name: this.props.name,
            description: this.props.description,
            status: this.props.status
        });
        this.toggleEditing();
    }

    updateName(event) {
        event.preventDefault();
        this.setState({ name: event.currentTarget.value });
    }

    updateDescription(event) {
        event.preventDefault();
        this.setState({ description: event.currentTarget.value });
    }

    updateStatus(event) {
        event.preventDefault();
        this.setState({ status: event.currentTarget.value });
    }

    updateDelay(event) {
        event.preventDefault();
        this.setState({ delay: event.currentTarget.value });
    }

    updateModule(event) {
        event.preventDefault();

        axios.put(`/admin/courses/modules/${this.props.id}`, {
            name: this.state.name,
            description: this.state.description,
            status: this.state.status,
            delay: this.state.delay
        }).then (response => {
            this.toggleEditing();
            this.props.updateModule(this.props.index, response.data.data);
        }).catch (error => {
            this.props.alertError(error);
        });
    }

    renderEditForm() {
        return (
            <div className="cm-card-header">
                <form onSubmit={this.updateModule.bind(this)}>
                    <input
                        type="text"
                        className="form-control mb-1 mr-1"
                        name="name"
                        value={this.state.name}
                        onChange={this.updateName.bind(this)}
                    />
                    <input
                        type="text"
                        className="form-control mb-1 mr-1"
                        name="description"
                        placeholder="Description"
                        value={this.state.description}
                        onChange={this.updateDescription.bind(this)}
                    />
                    <select
                        className="form-control mb-1 mr-1"
                        value={this.state.status}
                        onChange={this.updateStatus.bind(this)}>
                        <option>{STATUS_ACTIVE}</option>
                        <option>{STATUS_HIDDEN}</option>
                    </select>
                    <input
                        type="number"
                        className="form-control mb-1 mr-1"
                        name="delay"
                        placeholder="Delay"
                        maxLength="3"
                        style={{ width: '80px' }}
                        value={this.state.delay}
                        onChange={this.updateDelay.bind(this)}
                    />
                    <button className="btn btn-primary mb-1 mr-1">Save</button>
                    <button className="btn btn-warning mb-1" onClick={this.cancelEdit.bind(this)}>Cancel</button>
                </form>
            </div>
        );
    }

    renderTitle() {
        if (this.state.editing) {
            return this.renderEditForm();
        }

        const renderStatus = () => {
            if (this.props.status !== STATUS_ACTIVE) {
                return (
                    <span className="badge badge-secondary">
                        {this.props.status}
                    </span>
                );
            }
        }

        const renderDelay = () => {
            if (this.props.delay > 0) {
                return (
                    <span className="badge badge-info">
                        {this.props.delay} days delay
                    </span>
                );
            }
        }

        return (
            <div className="cm-card-header" onClick={this.toggleEditing.bind(this)}>
                <h2>{this.props.name}{" "}{renderStatus()}{" "}{renderDelay()}</h2>
                <p>{this.props.description}</p>
            </div>
        );
    }

    renderMoveUpButton() {
        if (this.props.index > 0) {
            return (
                <button
                    className="btn btn-secondary btn-sm"
                    onClick={() => this.props.moveModule(this.props.index, 'up')}
                >Move up</button>
            );
        }
    }

    renderMoveDownButton() {
        if (!this.props.lastItem) {
            return (
                <button
                    className="btn btn-secondary btn-sm"
                    onClick={() => this.props.moveModule(this.props.index, 'down')}
                >Move down</button>
            );
        }
    }

    renderLessons(lessons) {
        return lessons.map((lesson, index) => {
            const lastItem = ((index + 1) == lessons.length);

            return (
                <Lesson
                    key={index}
                    index={index}
                    lastItem={lastItem}
                    moduleIndex={this.props.index}
                    alertError={this.props.alertError}
                    updateLesson={this.props.updateLesson}
                    moveLesson={this.props.moveLesson}
                    openLessonEditor={this.props.openLessonEditor}
                    previewUri={this.props.previewUri}
                    {...lesson}
                />
            );
        });
    }

    render() {
        return (
            <div className="cm-card">
                {this.renderTitle()}
                <div className="cm-card-body">
                    <div className="mb-3">
                        {this.renderMoveUpButton()}{" "}
                        {this.renderMoveDownButton()}
                    </div>

                    <table className="cm-table">
                        <tbody>
                            {this.renderLessons(this.props.lessons)}
                        </tbody>
                    </table>

                    <CreateLesson
                        moduleId={this.props.id}
                        moduleIndex={this.props.index}
                        alertError={this.props.alertError}
                        addLesson={this.props.addLesson}
                    />
                </div>
            </div>
        );
    }
}

export default Module;
