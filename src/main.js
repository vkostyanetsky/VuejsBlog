import Vue from 'vue';
import App from './App.vue';

Vue.config.productionTip = false;

///////////////////////////////////////////////////////////////////////////////
// tachyons

import 'tachyons/css/tachyons.min.css';

///////////////////////////////////////////////////////////////////////////////
// VueI18n

import VueI18n from 'vue-i18n';

Vue.use(VueI18n);

import language from './language';

const i18n = new VueI18n({ 

    locale:             language.locale,
    messages:           language.messages,
    dateTimeFormats:    language.dateTimeFormats,

});

VueI18n.prototype.getChoiceIndex = function (choice, choicesLength) {
    
    if (this.locale !== 'ru') {
        // proceed to the default implementation
    }
  
    if (choice === 0) {
        return 0;
    }
  
    const teen          = choice > 10 && choice < 20;
    const endsWithOne   = choice % 10 === 1;
  
    if (!teen && endsWithOne) {
        return 1;
    }
  
    if (!teen && choice % 10 >= 2 && choice % 10 <= 4) {
        return 2;
    }
  
    return (choicesLength < 4) ? 2 : 3;
}

///////////////////////////////////////////////////////////////////////////////
// VueRouter

import VueRouter from 'vue-router';

import Page     from './routes/Page';
import NotePage from './routes/NotePage';
import NoteList from './routes/NoteList';
import NoteTags from './routes/NoteTags';

Vue.use(VueRouter);

const routes = [


    // /notes/tags/games/page/1
    
    {
        path:       '/notes/tags/:tag/page/:page',
        component:  NoteList,
    },        

    // /notes/tags/games/gris

    {
        path:       '/notes/tags/:tag/:note',
        component:  NotePage,
    },

    // /notes/tags/games

    {
        path:       '/notes/tags/:tag',
        component:  NoteList,
    },    

    // /notes/page/1

    {
        path:       '/notes/page/:page',
        component:  NoteList,
    },

    // /notes/tags

    {
        path:       '/notes/tags',
        component:  NoteTags,
    },    

    // /notes/draugen

    {
        path:       '/notes/:note',
        component:  NotePage,
    },

    // /notes/

    {
        path:       '/notes/',
        component:  NoteList,
    },         

    // /anything

    {
        path:       '*',
        component:  Page,
    },    

]

const router = new VueRouter({

    linkExactActiveClass:   '',
    linkActiveClass:        '',
    
    mode: 'history',

    routes,
    
});

///////////////////////////////////////////////////////////////////////////////
// Vue

new Vue({

    i18n,
    router,

    render: h => h(App),

}).$mount('#app');