import React from 'react';
import {
  Formik, Form, Field, FieldArray, ErrorMessage,
} from 'formik';
import * as Yup from 'yup';
import MD5 from 'crypto-js/md5';
import { RadioButton, RadioButtonGroup } from './FormElement';

function NewQuizForm() {
  const initialValues = {
    name: '',
    questions: [{
      text: '',
      answers: [{
        text: '',
      }],
      correct: '',
    }],
  };

  const validationSchema = Yup.object().shape({
    name: Yup.string().required('Name quiz is required'),
    questions: Yup.array().of(
      Yup.object().shape({
        text: Yup.string().required('Name question is required'),
        answers: Yup.array().of(
          Yup.object().shape({
            text: Yup.string().required('Name answer is required'),
          }),
        ),
        correct: Yup.string().required('Correct answer is required'),
      }),
    ),
  });
  return (
    <Formik
      initialValues={initialValues}
      validationSchema={validationSchema}
      onSubmit={async (values, actions) => {
        actions.setSubmitting(true);
        const formData = {
          token: window.token,
          csrfToken: window.csrfToken,
          name: values.name,
        };

        formData.questions = values.questions.map((question) => {
          const selectCorrectAnswer = parseInt(question.correct, 10);
          const answers = question.answers.map((answer, index) => {
            const isSelectAnswer = index === selectCorrectAnswer;
            return {
              text: answer.text,
              correct: isSelectAnswer,
            };
          });
          return {
            text: question.text,
            answers,
          };
        });

        return fetch('/quiz/create', {
          method: 'POST',
          cache: 'no-cache',
          redirect: 'follow',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(formData),
        }).then((response) => {
          if (response.ok) {
            window.location.href = '/quiz/created';
          } else {
            response.json().then((body) => alert(body.message));
          }
        })
          .catch(() => {
            // setSubmitting(false);
            // alertService.error(error);
          });
      }}
    >
      {(props) => (
        <Form method="post">
          <div className="row">
            <div className="col form-group">
              <label htmlFor="quiz-name">Set name of Quiz</label>
              <Field
                name="name"
                className={`form-control${props.errors.name && props.touched.name ? ' is-invalid' : ''}`}
                type="text"
                id="quiz-name"
              />
              <ErrorMessage name="name" component="div" className="invalid-feedback" />
            </div>
          </div>
          <FieldArray name="questions">
            {(questionArrayHelpers) => (
              <>
                <div className="row pt-2">
                  <div className="col-8">
                    <legend className="col-form-label required">Questions</legend>
                  </div>
                  <div className="col-2">
                    <button
                      type="button"
                      className="add_item_links"
                      onClick={() => questionArrayHelpers.push({
                        text: '',
                        answers: [{ text: '' }],
                        correct: '',
                      })}
                    >
                      Add a question
                    </button>
                  </div>
                </div>
                {props.values.questions.map((question, indexQuestion) => {
                  const questionErrors = (props.errors.questions?.length
                      && props.errors.questions[indexQuestion]) || {};
                  const questionTouched = (props.touched.questions?.length
                      && props.touched.questions[indexQuestion]) || {};
                  return (
                    <div key={MD5(`question${indexQuestion}`).toString()} className="list-group list-group-flush">
                      <div className="list-group-item">
                        <div className="row pt-2">
                          <div className="col-8">
                            <h5 className="card-title">Question</h5>
                          </div>
                          <div className="col-2">
                            <button
                              type="button"
                              className="add_item_links"
                              onClick={() => {
                                if (props.values.questions.length > 1) {
                                  questionArrayHelpers.remove(indexQuestion);
                                }
                              }}
                            >
                              Remove question
                            </button>
                          </div>
                        </div>
                        <div className="form-row">
                          <div className="form-group mb-3">
                            <label htmlFor={`questions-${indexQuestion}-text`}>Set name of question</label>
                            <Field
                              name={`questions.${indexQuestion}.text`}
                              type="text"
                              className={
                                `form-control${questionErrors.text && questionTouched.text ? ' is-invalid' : ''}`
                              }
                              id={`questions-${indexQuestion}-text`}
                            />
                            <ErrorMessage
                              name={`questions.${indexQuestion}.text`}
                              component="div"
                              className="invalid-feedback"
                            />
                          </div>
                        </div>
                        <FieldArray name="answers">
                          {(answerArrayHelpers) => (
                            <>
                              <div className="row pt-2">
                                <div className="col-8">
                                  <div className="col-form-label required">Answers</div>
                                </div>
                                <div className="col-2">
                                  <button
                                    type="button"
                                    onClick={
                                      () => {
                                        const pos = question.answers.length;
                                        question.answers.push({ text: '' });
                                        answerArrayHelpers.insert(pos, '');
                                      }
                                    }
                                  >
                                    Add a answer
                                  </button>
                                </div>
                              </div>
                              <RadioButtonGroup
                                id="radioGroup"
                                label="List Answers"
                                error={questionErrors.correct}
                                touched={questionTouched.correct}
                              >
                                {question.answers.map((answer, answerIndex) => {
                                  const answerErrors = (questionErrors?.answers?.length
                                    && questionErrors.answers[answerIndex]) || {};
                                  const answerTouched = (props.touched.questions?.length
                                    && questionTouched?.answers?.length
                                    && questionTouched.answers[answerIndex]) || {};
                                  return (
                                    <div
                                      className="row pt-3 d-flex align-items-center"
                                      key={MD5(`question${indexQuestion}.answer${answerIndex}`).toString()}
                                    >
                                      <div className="col-2">
                                        <div className="form-check">
                                          <Field
                                            component={RadioButton}
                                            name={`questions.${indexQuestion}.correct`}
                                            id={`questions-${indexQuestion}-${answerIndex}-answer`}
                                            type="radio"
                                            value={answerIndex}
                                            label="Correct"
                                          />
                                        </div>
                                      </div>
                                      <div className="col-8">
                                        <label htmlFor={`questions-${indexQuestion}-answers-${answerIndex}`}>
                                          Set answer of question
                                        </label>
                                        <Field
                                          name={`questions.${indexQuestion}.answers.${answerIndex}.text`}
                                          type="text"
                                          className={
                                          `form-control${answerErrors.text && answerTouched.text ? ' is-invalid' : ''}`
                                        }
                                          id={`questions-${indexQuestion}-answers-${answerIndex}`}
                                        />
                                        <ErrorMessage
                                          name={`questions.${indexQuestion}.answers.${answerIndex}.text`}
                                          component="div"
                                          className="invalid-feedback"
                                        />
                                      </div>
                                      <div className="col-2 pt-3">
                                        <button
                                          type="button"
                                          onClick={
                                              () => {
                                                if (question.answers.length > 1) {
                                                  question.answers.splice(answerIndex, 1);
                                                  answerArrayHelpers.remove(answerIndex);
                                                }
                                              }
                                          }
                                        >
                                          -
                                        </button>
                                      </div>
                                    </div>
                                  );
                                })}
                              </RadioButtonGroup>
                            </>
                          )}
                        </FieldArray>
                      </div>
                    </div>
                  );
                })}
              </>
            )}
          </FieldArray>
          <div className="card-footer border-top-0">
            <button disabled={props.isSubmitting} type="submit" className="btn btn-primary mr-1">
              Save
            </button>
          </div>
        </Form>
      )}
    </Formik>
  );
}

export default NewQuizForm;
