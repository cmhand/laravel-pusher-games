
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import catchGame from './components/catch-game.vue';

if (document.getElementById('game')) {
	const app = new Vue({
	    el: '#game',
	    render: h => h(catchGame)
	});
}