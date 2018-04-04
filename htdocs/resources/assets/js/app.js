
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

// questions-spotlig
const QuestionsSpotlight = new Vue({
	el: '#questions-spotlight',
	data: {
		options: [],
		questions: []
	},
	methods: {
		onSearch( search, loading ) {
			loading( true );
			this.search( loading, search, this );
		},
		selectedQuestion: function( val ) {
			this.$children.forEach(element => {
				if ( element.$el.className.indexOf('searchable') !== -1 ) {
					element.$el.val = '';
					element.$emit('focus');
				}
			});
			if ( val ) {
				this.questions.push( val );
				this.options = [];
			}
		},
		search: _.debounce( (loading, search, vm) => {
			axios.get('/api/v1/questions', {
				params : {
					'search' : search
				}
			}).then(function( response ){
				vm.options = response.data;
				loading( false );
			});
		}, 350 )
	}
});
