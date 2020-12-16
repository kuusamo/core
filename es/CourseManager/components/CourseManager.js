import React, { Component, Fragment } from 'react';
import axios from 'axios';

import {
    STATUS_ACTIVE,
    STATUS_HIDDEN
} from '../../constants';

import StatusBar from './StatusBar';
import Module from './Module';
import CreateModule from './CreateModule';
import LessonEditor from './LessonEditor';

class CourseManager extends Component {
    constructor(props) {
        super(props);

        this.state = {
            modules: [],
            lessonEditor: null,
            scrollToModule: null,
            status: null,
            loading: true,
            options: { status: STATUS_HIDDEN }
        };
    }

    componentDidMount() {
        this.loadModules();
    }

    alertError(error) {
        const message = error.response.data.message;
        this.setState({ status: { level: 'danger', message }});
    }

    clearStatus() {
        this.setState({ status: null });
    }

    setOption(key, event) {
        const options = this.state.options;
        options[key] = event.target.value;
        this.setState({ options });
    }

    loadModules() {
        axios.get(`/admin/courses/${this.props.courseId}/modules`)
            .then(response => {
                this.setState({ modules: response.data.data, loading: false });
            }).catch(error => {
                this.alertError(error);
            });
    }

    addModule(module) {
        this.setState({ modules: [...this.state.modules, module]})
    }

    updateModule(index, module) {
        const modules = this.state.modules;
        modules[index] = module;
        this.setState({ modules });
    }

    removeModule(index) {
        const modules = this.state.modules;
        modules.splice(index, 1);
        this.setState({ modules });
    }

    moveModule(index, direction) {
        const indexToSwap = (direction == 'up') ? index-- : index++;
        const modules = this.state.modules;
        const module = modules[index];
        modules[index] = modules[indexToSwap];
        modules[indexToSwap] = module;
        const reindex = [];
        modules.map(module => {
            reindex.push(module.id);
        })
        axios.put(`/admin/courses/${this.props.courseId}/modules`, { order: reindex })
            .then(response => {
                this.setState({ modules });
            })
            .catch(error => {
                this.alertError(error);
            });
    }

    moveLesson(moduleIndex, index, direction) {
        const indexToSwap = (direction == 'up') ? index-- : index++;
        const modules = this.state.modules;
        const lesson = modules[moduleIndex].lessons[index];
        modules[moduleIndex].lessons[index] = modules[moduleIndex].lessons[indexToSwap];
        modules[moduleIndex].lessons[indexToSwap] = lesson;
        const reindex = [];
        modules[moduleIndex].lessons.map(lesson => {
            reindex.push(lesson.id)
        });
        axios.put(`/admin/courses/modules/${modules[moduleIndex].id}/lessons`, { order: reindex })
            .then(response => {
                this.setState({ modules });
            })
            .catch(error => {
                this.alertError(error);
            });
    }

    addLesson(moduleIndex, lesson) {
        const modules = this.state.modules;
        modules[moduleIndex].lessons.push(lesson);
        this.setState({ modules });
    }

    updateLesson(moduleIndex, index, lesson) {
        const modules = this.state.modules;
        modules[moduleIndex].lessons[index] = lesson;
        this.setState({ modules });
    }

    removeLesson(moduleIndex, index) {
        const modules = this.state.modules;
        modules[moduleIndex].lessons.splice(index, 1);
        this.setState({ modules });
    }

    openLessonEditor(moduleIndex, index) {
        const lessonEditor = { moduleIndex, index };
        this.setState({ lessonEditor });
    }

    closeLessonEditor() {
        const moduleIndex = this.state.lessonEditor.moduleIndex;
        this.setState({ lessonEditor: null, scrollToModule: moduleIndex });
    }

    updateLessonBlocks(moduleIndex, index, blocks) {
        const modules = this.state.modules;
        modules[moduleIndex].lessons[index].blocks = blocks;
        this.setState({ modules, lessonEditor: null });
    }

    renderModules() {
        return this.state.modules.map((module, index) => {
            const lastItem = ((index + 1) == this.state.modules.length);
            const scrollIntoView = this.state.scrollToModule === index;

            return (
                <Module
                    key={index}
                    scrollIntoView={scrollIntoView}
                    index={index}
                    lastItem={lastItem}
                    alertError={this.alertError.bind(this)}
                    updateModule={this.updateModule.bind(this)}
                    removeModule={this.removeModule.bind(this)}
                    moveModule={this.moveModule.bind(this)}
                    addLesson={this.addLesson.bind(this)}
                    updateLesson={this.updateLesson.bind(this)}
                    removeLesson={this.removeLesson.bind(this)}
                    moveLesson={this.moveLesson.bind(this)}
                    openLessonEditor={this.openLessonEditor.bind(this)}
                    previewUri={this.props.previewUri}
                    defaultStatus={this.state.options.status}
                    {...module}
                />
            );
        });
    }

    renderStatusBar() {
        if (this.state.status) {
            return (
                <StatusBar
                    status={this.state.status}
                    clearStatus={this.clearStatus.bind(this)}
                />
            );
        }
    }

    renderOptions() {
        return (
            <div className="card mt-3">
                <div className="cm-card-header">
                    <h2><i class="fas fa-cog" aria-hidden></i> Settings</h2>
                </div>
                <div className="card-body">
                    <form className="cm-form-grid">
                        <div>
                            <div><label htmlFor="defaultStatus">Default status</label></div>
                            <div>
                                <select
                                    id="defaultStatus"
                                    className="form-control"
                                    value={this.state.options.status}
                                    onChange={event => { this.setOption('status', event)}}>
                                    <option>{STATUS_ACTIVE}</option>
                                    <option>{STATUS_HIDDEN}</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        );
    }

    renderWorkArea() {
        if (this.state.lessonEditor) {
            const lesson = this.state.modules[this.state.lessonEditor.moduleIndex].lessons[this.state.lessonEditor.index];

            return (
                <LessonEditor
                    moduleIndex={this.state.lessonEditor.moduleIndex}
                    index={this.state.lessonEditor.index}
                    lesson={lesson}
                    alertError={this.alertError.bind(this)}
                    updateLessonBlocks={this.updateLessonBlocks.bind(this)}
                    closeLessonEditor={this.closeLessonEditor.bind(this)}
                />
            );
        }

        if (this.state.loading) {
            return (
                <div className="cm-loading">
                    <img src="/images/loading.gif" alt="Loading" />
                </div>
            );
        }

        return (
            <Fragment>
                {this.renderModules()}

                <CreateModule
                    courseId={this.props.courseId}
                    addModule={this.addModule.bind(this)}
                    alertError={this.alertError.bind(this)}
                    defaultStatus={this.state.options.status}
                />

                {this.renderOptions()}
            </Fragment>
        );
    }

    render() {
        return (
            <div>
                {this.renderStatusBar()}
                {this.renderWorkArea()}
            </div>
        );
    }
}

export default CourseManager;
