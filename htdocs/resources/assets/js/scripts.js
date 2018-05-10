/**
 * JS para administración de guiones (scripts)
 */

window.Vue = require('vue');
import vuedraggable from 'vuedraggable';
Vue.component('draggable', vuedraggable);
Vue.component(
	'selectize',
	require('./components/SelectizeComponent.vue')
);
const _ = require('lodash');
const axios = require('axios');

// questions-spotlight
const QuestionsSpotlight = new Vue({
    el: '#questions-spotlight',
    data: {
        options: [],
        questions: Script.questions_order || [],
        stages : 1,
    },
    methods: {
        addQuestion: function( val ) {
            if ( val ) {
                if ( ! _.find( this.questions, function( item ){
                    return item.id == val.id;
                } ) ) {
                    this.questions.push( val );
                }
            }
        },
        addStage: function() {
            this.stages++;
            this.questions.push({
                id: 'stage_'+ this.stages,
                formulation: 'Nueva etapa',
                container_class: 'bg-secondary text-white'
            });
        },
        removeQuestion: function( element_id ) {
            this.questions = _.reject( this.questions, function( item ){
                return item.id == element_id;
            } );
        },
        addOnboarding: function() {

        }
    }
});
$(document).ready(function(){
    $('form').on('submit', function( event ){
        $('#script__order').val( JSON.stringify( QuestionsSpotlight.$data.questions ) );
    });
});