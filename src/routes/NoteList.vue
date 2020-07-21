///////////////////////////////////////////////////////////////////////////////
// ШАБЛОН

<template>

<article>

    <template v-if="errorMessage == '' ">
            
        <h1 class="f-subheadline lh-solid">{{ pageTitle }}</h1>
            
        <component :is="tagDescriptionComponent"></component>

        <p v-if="this.nextPageUrl != ''">
            <router-link :to="nextPageUrl" class="link blue bb dim mr1 mt3 mb3">{{$t('notes.after')}}</router-link> Ctrl + &uarr;
        </p>

        <span v-for="note in notes" :key="note.name">
            <Note :note="note" link/>
        </span>

        <p v-if="this.previousPageUrl != ''">
            <router-link :to="previousPageUrl" class="link blue bb dim mr1 mt3 mb3">{{$t('notes.before')}}</router-link> Ctrl + &darr;
        </p>

    </template>

    <template v-else>
        <Error :title="errorTitle" :message="errorMessage" />
    </template>    

</article>

</template>

///////////////////////////////////////////////////////////////////////////////
// СКРИПТ

<script>

import Vue from 'vue';
import {api} from '../api';

import errorMixin   from '../mixins/error';
import routeMixin   from '../mixins/route';
import notesMixin   from '../mixins/notes';
import twitterMixin from '../mixins/twitter';

export default {


    name: 'Notes',


    components: {

        Note:   () => import('../components/Note'),
        Error:  () => import('../components/Error'),

    }, // components


    mixins: [errorMixin, routeMixin, notesMixin, twitterMixin],


    created: function () {        

        window.addEventListener('keyup', this.ctrlJump);

    }, // created


    destroyed: function () {

        window.removeEventListener('keyup', this.ctrlJump);

    }, // destroyed


    data () {

        return {
                        
            previousPageUrl:    '',
            nextPageUrl:        '',

            pageTitle:                  '',
            tagDescriptionComponent:    'tagDescription',

            notes: [],

        };

    }, // data


    beforeRouteEnter (to, from, next) {

        next(vm => {
            vm.init();
        })

    }, // beforeRouteEnter


    beforeRouteUpdate (to, from, next) {
        
        next();

        this.init();

    }, // beforeRouteUpdate


    methods: {


        init: function () {

            this.loadNotes();
    
        }, // init


        loadNotes: function () {

            let tag     = this.$route.params.tag;
            let page    = this.$route.params.page;

            let query   = '?module=notes&entity=notes';
            
            if ( tag != undefined) {
                query = query + '&tag=' + tag;
            }

            if ( page != undefined) {
                query = query + '&page=' + page;
            }            

            api
                .get(query)
                .then(response => {

                    this.readNotesData(response.data);
                    
                })
                .catch(() => {

                    this.showServerErrorPage();

                });

        }, // loadNotes


        readNotesData: function (data) {

            let dataFields = ['tag', 'notes', 'previousPageNumber', 'nextPageNumber'];

            if (this.isDataValid(data, dataFields)) this.showNotes(data);
            
        }, // readNotesData


        showNotes: function (data) {
            
            let tagDescription = '';

            if (data.tag.description !== undefined) {
                tagDescription = data.tag.description;
            }

            Vue.component(
                this.tagDescriptionComponent, {
                    template: '<span>' + tagDescription + '</span>'
                }
            );

            this.notes = [];

            let initiateTwitterWidget = false;
            
            for (var index in data.notes) {

                let noteData    = data.notes[index];
                let note        = this.processNoteData(noteData)

                this.notes.push(note);

                if (this.containsTwitterWidget(note.content)) {
                    initiateTwitterWidget = true;
                }

            }

            this.previousPageUrl    = this.pageUrl(data.previousPageNumber);
            this.nextPageUrl        = this.pageUrl(data.nextPageNumber);

            if (initiateTwitterWidget) this.initiateTwitterWidget();
            
            let pageTitle = '';

            if (data.tag.title !== undefined) {
                pageTitle = this.tagTitle(data.tag.title);
            }
            else {
                pageTitle = this._i18n.t('wrapper.notes');
                
            }
            
            this.setPageTitle(pageTitle);
            this.scrollToTop();

        }, // showNotes


        pageUrl: function (pageNumber) {

            var url = '';

            if (pageNumber > 0) {
                
                let tag = this.$route.params.tag;
                
                url = tag != undefined ? `/notes/tags/${tag}/page/${pageNumber}` : `/notes/page/${pageNumber}`;

            }
            
            return url;

        }, // pageUrl


        ctrlJump: function (zEvent) {
        
            if (! zEvent.ctrlKey) return;

            if (zEvent.keyCode === 40 && this.previousPageUrl != '') {
                this.$router.push(this.previousPageUrl);
            }
            else if (zEvent.keyCode === 38 && this.nextPageUrl != '') {
                this.$router.push(this.nextPageUrl);
            }

        }, // ctrlJump


    }, // methods


};   

</script>