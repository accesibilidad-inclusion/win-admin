<script>
	import Selectize from 'selectize';
	import $ from 'jquery';
	export default {
		components: {
			Selectize
		},
		mounted() {
			var vm = this;
			var request;
			$(this.$el).selectize({
				valueField  : 'id',
				labelField  : 'formulation',
				searchField : 'formulation',
				create      : false,
				load        : function( query, callback ) {
					if ( ! query.length ) {
						return callback();
					}
					if ( typeof request !== 'undefined' ) {
						request.abort();
					}
					let request = $.ajax({
						url      : '/api/v1/questions',
						data     : {
							search : query
						},
						dataType : 'json',
						method   : 'GET',
						success  : function( value ) {
							callback( value );
						}
					});
				},
				closeAfterSelect: true,
				loadThrottle: null,
				placeholder: 'Busca el enunciado de la pregunta que deseas agregar',
				onChange: function( value ) {
					vm.$emit('selectized', this.options[ value ] );
					this.clear();
				}
			})
		},
		data() {
			return {
				settings: {
					closeAfterSelect: true,
					placeholder: 'Busca la pregunta que deseas agregar al guión',
					load: function( query, callback ) {
						if ( ! query.length ) {
							return callback();
						}
						if ( typeof request !== 'undefined' ) {
							request.abort();
						}
						request = $.ajax({
							url: '/api/v1/questions',
							data: {
								search: query
							},
							dataType: 'json',
							method: 'GET',
							success: function( value ) {
								callback( value );
							}
						} );
					},
					onChange: function( value ) {
						vm.$emit('selectized', this.options[ value ]);
						this.clear();
					}
				},
				selected: 1
			}
		}
	}
</script>

<template>
	<div class="selectize">
		<input type="text" class="form-control form-control-lg selectize__input">
	</div>
</template>

<style scoped>
	@import "~selectize/dist/css/selectize.default.css";
</style>
