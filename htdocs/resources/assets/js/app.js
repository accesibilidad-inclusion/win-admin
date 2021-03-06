
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');
//
window.Vue = require('vue');
import vuedraggable from 'vuedraggable';
Vue.component('draggable', vuedraggable);
Vue.component(
	'selectize',
	require('./components/SelectizeComponent.vue')
);
const _ = require('lodash');
const axios = require('axios');

//
// /**
//  * Next, we will create a fresh Vue application instance and attach it to
//  * the page. Then, you may begin adding components to this application
//  * or customize the JavaScript scaffolding to fit your unique needs.
//  */
//
// Vue.component('example-component', require('./components/ExampleComponent.vue'));
//

// questions-spotlight
if ( typeof Script !== "undefined" ) {
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
}