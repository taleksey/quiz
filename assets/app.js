/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

const $ = require('jquery');

require('bootstrap');

$(document).ready(function() {
  $(document).on('submit','form#answersQuestion',function() {
    const selectedRadioInputElement = $(this).find('input[type=radio]:checked');
    if (selectedRadioInputElement.length === 0) {
      alert('Please select answer');
      return false;
    }
    return true;
  });

  $(document).on('click', 'button.add_item_link', function (){
    const questionSelector = $($(this).data('question-selector'));
    let totalQuestions = questionSelector.data('widget-counter') || questionSelector.children().length;
    const originalTotalQuestions = totalQuestions;
    let newWidget = questionSelector.attr('data-prototype');
    const manageAnswerBlock = getAnswerButton(totalQuestions);

    newWidget = newWidget.replace(/__name__/g, totalQuestions);
    totalQuestions++;
    questionSelector.data('widget-counter', totalQuestions);

    const answersMainBlockText = $(newWidget).find('legend');
    const originalText = answersMainBlockText.prop("outerHTML");
    newWidget = newWidget.replace(new RegExp(originalText,'g'), manageAnswerBlock);
    const newElem = $(newWidget);

    const blockAnswers = $(newElem).find('#quiz_questions_'+ originalTotalQuestions +'_answers');
    const counterAnswers = blockAnswers.data('widget-counter') || blockAnswers.children().length;
    let answersHtmlCodeFromPrototype = blockAnswers.attr('data-prototype');
    answersHtmlCodeFromPrototype = answersHtmlCodeFromPrototype.replace(/__value__/g, counterAnswers);
    answersHtmlCodeFromPrototype = insertInRadioButtonCorrectValueInsteadTemplateValue(answersHtmlCodeFromPrototype, originalTotalQuestions, counterAnswers);
    answersHtmlCodeFromPrototype = answersHtmlCodeFromPrototype.replace(/__que__/g, counterAnswers);
    const formWithDifferentAnswers = $(answersHtmlCodeFromPrototype).find('div[id^="quiz_questions_"]').addClass('row pt-3');
    const newChild = formWithDifferentAnswers.addClass('row pt-3');

    blockAnswers.html(newChild.parent().html());

    newElem.appendTo(questionSelector);
  })

  $(document).on('click', 'button.add_item_answer', function (){
    const questionNumber  = $(this).data('question-id');
    const divHasPrototype = $('#quiz_questions_'+ questionNumber +'_answers');
    let htmlPrototype = divHasPrototype.attr('data-prototype');
    let totalAnswerForms = divHasPrototype.data('widget-counter') || divHasPrototype.children().length;
    htmlPrototype = htmlPrototype.replace(/__value__/g, totalAnswerForms);
    htmlPrototype = insertInRadioButtonCorrectValueInsteadTemplateValue(htmlPrototype, questionNumber, 0);
    htmlPrototype = htmlPrototype.replace(/__que__/g, totalAnswerForms);
    divHasPrototype.data('widget-counter', ++totalAnswerForms);
    let id = $(htmlPrototype).children('div').attr('id');
    let newElem = $(htmlPrototype).find('#' + id).unwrap();
    newElem.addClass('row pt-3');

    newElem.appendTo(divHasPrototype);
  });

  $('.QuizAnswers').each(function (index, element){
    const $element = $(element);
    const headerAnswer = $element.find('legend');
    const headerText = getAnswerButton(index);
    headerAnswer.remove();
    $element.find('div[id^="quiz_questions_'+ index +'_answers_"]').each(function (index, element) {
      $(element).addClass('row').unwrap();
      element.outerHTML
        .replace(/__value__/, index)
        . replace(/(quiz\[questions]\[)(\d+)(]\[answers]\[)\d+(]\[correct])/,"\$1\$2\$30\$4");
    });

    $(headerText).prependTo($element);
  });
});

function getAnswerButton(totalQuestions) {
  const template = document.querySelector('#AnswerButton');
  const clone = template.content.cloneNode(true);
  const buttonAddNewAnswer = clone.querySelector('.add_item_answer');
  buttonAddNewAnswer.dataset.questionId = totalQuestions;

  const div = document.createElement('div');
  div.appendChild(clone);

  return  div.innerHTML;
}

function insertInRadioButtonCorrectValueInsteadTemplateValue(htmlCode, questionNumber, answerNumberInQuestion)
{
  const originalNameRadioButton =  'quiz\\[questions\\]\\['+questionNumber+'\\]\\[answers\\]\\[__que__\\]\\[correct\\]';
  const changeNameRadioButtonOn = 'quiz[questions]['+questionNumber+'][answers]['+ answerNumberInQuestion +'][correct]';
  return htmlCode.replace(new RegExp(originalNameRadioButton, 'g'), changeNameRadioButtonOn);
}
