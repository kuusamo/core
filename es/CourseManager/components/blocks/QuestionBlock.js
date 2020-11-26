import React from 'react';

const QuestionBlock = props => {
    const onChangeText = event => {
        props.callback(props.index, 'text', event.currentTarget.value);
    }

    const createAnswer = () => {
        const answers = props.answers;
        answers.push({ correct: true });
        props.callback(props.index, 'answers', answers);
    }

    const updateAnswer = (index, field, value) => {
        const answers = props.answers;
        answers[index][field] = value;
        props.callback(props.index, 'answers', answers);
    }

    const toggleCorrect = index => {
        const answers = props.answers;
        answers[index]['correct'] = !props.answers[index]['correct'];
        props.callback(props.index, 'answers', answers);
    }

    const deleteAnswer = index => {
        const confirmation = confirm('Are you sure?');

        if (confirmation === true) {
            const answers = props.answers;
            answers.splice(index, 1);
            props.callback(props.index, 'answers', answers);
        }
    }

    const renderAnswers = () => {
        return props.answers.map((answer, index) => {
            const textValue = answer.text || '';
            const explanationValue = answer.explanation || '';
            const correctText = answer.correct ? 'Correct' : 'Incorrect';

            return (
                <div key={index} className="mb-3">
                    <input
                        type="text"
                        className="form-control mr-1"
                        placeholder="Answer text"
                        value={textValue}
                        onChange={event => updateAnswer(index, 'text', event.currentTarget.value)}
                    />
                    <input
                        type="text"
                        className="form-control mr-1"
                        placeholder="Answer explanation"
                        value={explanationValue}
                        onChange={event => updateAnswer(index, 'explanation', event.currentTarget.value)}
                    />
                    <button className="btn btn-secondary mr-1" onClick={() => toggleCorrect(index)}>{correctText}</button>
                    <button className="btn btn-danger" aria-label="Delete" onClick={() => deleteAnswer(index)}>&times;</button>
                </div>
            );
        });
    }

    return (
        <div>
            <textarea
                className="form-control mb-3"
                rows="2"
                value={props.text}
                placeholder="Question text"
                onChange={onChangeText}
            />

            {renderAnswers()}

            <button className="btn" onClick={createAnswer}>Add answer</button>
        </div>
    );
}

export { QuestionBlock };
