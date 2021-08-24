

// import './bootstrap';
import Vue from 'vue';
import ArticleLike from './components/ArticleLike.vue';
import ArticleTagsInput from './components/ArticleTagsInput.vue';
import FollowButton from './components/FollowButton.vue';


window.Vue = require('vue').default;

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


Vue.component('example-component', require('./components/ExampleComponent.vue').default);


const app = new Vue({
    el: '#app',
    components: {
        ArticleLike,
        ArticleTagsInput,
        FollowButton,
    }
});
