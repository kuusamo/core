import React, { Component, Fragment } from 'react';
import axios from 'axios';
import BlockUtils from '../utils/BlockUtils';

import { TYPE_ASSESSMENT } from '../constants';

import {
    AudioBlock,
    DownloadBlock,
    ImageBlock,
    MarkdownBlock,
    QuestionBlock,
    VideoBlock
} from './blocks';

class LessonEditor extends Component {
    constructor(props) {
        super(props);

        this.state = {
            blocks: this.props.lesson.blocks
        };

        this.updateBlock = this.updateBlock.bind(this);
    }

    createBlock(type, defaults) {
        const blocks = this.state.blocks;
        blocks.push({ type, ...defaults });
        this.setState({ blocks });
    }

    updateBlock(index, field, value) {
        const blocks = this.state.blocks;
        blocks[index][field] = value;
        this.setState({ blocks });
    }

    deleteBlock(index) {
        const confirmation = confirm('Are you sure?');

        if (confirmation === true) {
            const blocks = this.state.blocks;
            blocks.splice(index, 1);
            this.setState({ blocks });
        }
    }

    moveBlock(step, index) {
        const blocks = this.state.blocks;
        const indexToSwap = (index + step);
        const block = blocks[index];
        blocks[index] = blocks[indexToSwap];
        blocks[indexToSwap] = block;
        this.setState({ blocks });
    }

    saveBlocks() {
        axios.put(`/admin/courses/lessons/${this.props.lesson.id}/blocks`, {
            blocks: this.state.blocks,
        }).then (response => {
            this.props.updateLessonBlocks(
                this.props.moduleIndex,
                this.props.index,
                response.data.data.blocks
            );
        }).catch(error => {
            this.props.alertError(error);
        });
    }

    renderBlock(block, index) {
        switch (block.type) {
            case 'audio':
                return <AudioBlock key={index} index={index} callback={this.updateBlock} {...block} />;
            case 'download':
                return <DownloadBlock key={index} index={index} callback={this.updateBlock} {...block} />;
            case 'image':
                return <ImageBlock key={index} index={index} callback={this.updateBlock} {...block} />;
            case 'markdown':
                return <MarkdownBlock key={index} index={index} callback={this.updateBlock} {...block} />;
            case 'question':
                return <QuestionBlock key={index} index={index} callback={this.updateBlock} {...block} />;
            case 'video':
                return <VideoBlock key={index} index={index} callback={this.updateBlock} {...block} />;
            default:
                console.warn('Unrecognised component: ' + block.type);
        }
    }

    renderBlocks(blocks) {
        return blocks.map((block, index) => {
            const renderedBlock = this.renderBlock(block, index);

            if (renderedBlock) {
                return (
                    <div key={index} className="cm-card">
                        <div className="cm-card-header cm-block-header">
                            <div>{BlockUtils.getLabel(block.type)}</div>
                            <div>
                                { this.renderMoveUpButton(index) }&nbsp;
                                { this.renderMoveDownButton(index, blocks) }&nbsp;
                                <button className="btn btn-secondary btn-sm" onClick={() => this.deleteBlock(index)}>X</button>
                            </div>
                        </div>
                        <div className="cm-card-body">
                            {renderedBlock}
                        </div>
                    </div>
                );
            }
        });
    };

    renderMoveUpButton(index) {
        if (index !== 0) {
            return (
                <button className="btn btn-secondary btn-sm"
                        onClick={() => this.moveBlock(-1, index)}>↑</button>
            );
        }

        return (
            <button className="btn btn-secondary btn-sm" disabled>↑</button>
        );
    }

    renderMoveDownButton(index, blocks) {
        if (index !== (blocks.length - 1)) {
            return (
                <button className="btn btn-secondary btn-sm"
                        onClick={() => this.moveBlock(1, index)}>↓</button>
            );
        }

        return (
            <button className="btn btn-secondary btn-sm" disabled>↓</button>
        );
    }

    renderCreateButtons() {
        return (
            <Fragment>
                <button className="btn mb-1 mr-1" onClick={() => this.createBlock('markdown')}>Add Markdown</button>
                <button className="btn mb-1  mr-1" onClick={() => this.createBlock('video', { provider: 'vimeo' })}>Add Video</button>
                <button className="btn mb-1  mr-1" onClick={() => this.createBlock('image')}>Add Image</button>
                <button className="btn mb-1  mr-1" onClick={() => this.createBlock('download')}>Add Download</button>
                <button className="btn mb-1 " onClick={() => this.createBlock('audio')}>Add Audio</button>
                <button className="btn mb-1 " onClick={() => this.createBlock('question', { answers: [] })}>Add Question</button>
            </Fragment>
        );
    }

    render() {
        return (
            <div>
                <h2>Lesson editor</h2>

                {this.renderBlocks(this.state.blocks)}

                <p>
                    {this.renderCreateButtons()}
                </p>

                <p>
                    <button className="btn btn-primary" onClick={this.saveBlocks.bind(this)}>Save</button>{" "}
                    <button className="btn btn-secondary" onClick={() => this.props.closeLessonEditor()}>Close editor</button>
                </p>
            </div>
        );
    }
}

export default LessonEditor;
