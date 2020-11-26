import React, { Component, Fragment } from 'react';
import axios from 'axios';
import {
    STATUS_ACTIVE,
    STATUS_HIDDEN,
    TYPE_CONTENT,
    TYPE_ASSESSMENT,
    MARKING_AUTOMATIC,
    MARKING_TUTOR
} from '../constants';

class Lesson extends Component {
    constructor(props) {
        super(props);

        this.state = {
            editing: false,
            name: this.props.name,
            status: this.props.status,
            type: this.props.type,
            marking: this.props.marking,
            passMark: this.props.passMark
        };
    }

    toggleEditing() {
        this.setState({ editing: !this.state.editing });
    }

    cancelEdit() {
        event.preventDefault();
        this.setState({
            name: this.props.name,
            status: this.props.status,
            type: this.props.type,
            marking: this.props.marking,
            passMark: this.props.passMark
        });
        this.toggleEditing();
    }

    updateLesson(event) {
        event.preventDefault();

    axios.put(`/admin/courses/lessons/${this.props.id}`, {
            name: this.state.name,
            status: this.state.status,
            type: this.state.type,
            marking: this.state.marking,
            passMark: this.state.passMark
        }).then (response => {
            this.toggleEditing();
            this.props.updateLesson(
                this.props.moduleIndex,
                this.props.index,
                response.data.data
            );
        }).catch(error => {
            this.props.alertError(error);
        });
    }

    updateName(event) {
        event.preventDefault();
        this.setState({ name: event.currentTarget.value });
    }

    updateStatus(event) {
        event.preventDefault();
        this.setState({ status: event.currentTarget.value });
    }

    updateType(event) {
        event.preventDefault();
        this.setState({ type: event.currentTarget.value });
    }

    updateMarking(event) {
        event.preventDefault();
        this.setState({ marking: event.currentTarget.value });
    }

    updatePassMark(event) {
        event.preventDefault();
        const newValue = (event.currentTarget.value != '') ? parseInt(event.currentTarget.value) : '';
        this.setState({ passMark: newValue });
    }

    renderEditForm() {
        return (
            <div className="cm-modal" tabIndex="-1" role="dialog">
                <div className="modal-dialog" role="document">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h3 className="modal-title">Edit lesson</h3>
                        </div>
                        <div className="modal-body">
                            <form className="cm-form-grid" onSubmit={this.updateLesson.bind(this)}>
                                <div className="form-group row">
                                    <label htmlFor="name" className="col-sm-3 col-form-label">Name</label>
                                    <div className="col-sm-9">
                                        <input
                                            type="text"
                                            id="name"
                                            className="form-control"
                                            name="name"
                                            value={this.state.name}
                                            onChange={this.updateName.bind(this)}
                                        />
                                    </div>
                                </div>
                                <div className="form-group row">
                                    <label htmlFor="status" className="col-sm-3 col-form-label">Status</label>
                                    <div className="col-sm-9">
                                        <select
                                            name="status"
                                            id="status"
                                            className="form-control"
                                            value={this.state.status}
                                            onChange={this.updateStatus.bind(this)}>
                                            <option>{STATUS_ACTIVE}</option>
                                            <option>{STATUS_HIDDEN}</option>
                                        </select>
                                    </div>
                                </div>
                                <div className="form-group row">
                                    <label htmlFor="type" className="col-sm-3 col-form-label">Type</label>
                                    <div className="col-sm-9">
                                        <select
                                            name="type"
                                            id="type"
                                            className="form-control"
                                            value={this.state.type}
                                            onChange={this.updateType.bind(this)}>
                                            <option>{TYPE_CONTENT}</option>
                                            <option>{TYPE_ASSESSMENT}</option>
                                        </select>
                                    </div>
                                </div>
                                <div className="form-group row">
                                    <label htmlFor="marking" className="col-sm-3 col-form-label">Marking</label>
                                    <div className="col-sm-9">
                                        <select
                                            name="marking"
                                            id="marking"
                                            className="form-control"
                                            value={this.state.marking}
                                            onChange={this.updateMarking.bind(this)}>
                                            <option>{MARKING_AUTOMATIC}</option>
                                            <option>{MARKING_TUTOR}</option>
                                        </select>
                                    </div>
                                </div>
                                <div className="form-group row">
                                    <label htmlFor="passMark" className="col-sm-3 col-form-label">Pass mark</label>
                                    <div className="col-sm-9">
                                        <input
                                            type="text"
                                            className="form-control"
                                            name="passMark"
                                            id="passMark"
                                            value={this.state.passMark}
                                            onChange={this.updatePassMark.bind(this)}
                                        />
                                    </div>
                                </div>
                                <div className="cm-form-grid-buttons">
                                    <button className="btn">Save</button>{" "}
                                    <button className="btn btn-warning" onClick={this.cancelEdit.bind(this)}>Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    renderTitle() {
        const renderEditForm = () => {
            if (this.state.editing) {
                return this.renderEditForm();
            }
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

        const renderMarking = () => {
            if (this.props.marking !== MARKING_AUTOMATIC) {
                return (
                    <span className="badge badge-info">
                        {this.props.marking}
                    </span>
                );
            }
        }

        return (
            <Fragment>
                {renderEditForm()}
                <span onClick={this.toggleEditing.bind(this)}>
                    {this.props.name}{" "}{renderStatus()}{renderMarking()}
                </span>
            </Fragment>
        );
    }

    renderMoveUpButton() {
        if (this.props.index > 0) {
            return (
                <a
                    href=""
                    onClick={event => {
                        event.preventDefault();
                        this.props.moveLesson(
                            this.props.moduleIndex,
                            this.props.index,
                            'up'
                        );
                    }}
                >Move up</a>
            );
        }
    }

    renderMoveDownButton() {
        if (!this.props.lastItem) {
            return (
                <a
                    href=""
                    onClick={event => {
                        event.preventDefault();
                        this.props.moveLesson(
                            this.props.moduleIndex,
                            this.props.index,
                            'down'
                        );
                    }}
                >Move down</a>
            );
        }
    }

    render() {
        const previewUrl = `${this.props.previewUri}/${this.props.id}`;

        return (
            <tr>
                <td>{this.renderTitle()}</td>
                <td><a href="" onClick={event => {
                    event.preventDefault();
                    this.props.openLessonEditor(this.props.moduleIndex, this.props.index);
                }}>Edit content</a></td>
                <td><a href={previewUrl} target="_blank">Preview</a></td>
                <td>{this.renderMoveUpButton()}</td>
                <td>{this.renderMoveDownButton()}</td>
            </tr>
        );
    }
}

export default Lesson;
