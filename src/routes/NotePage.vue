///////////////////////////////////////////////////////////////////////////////
// ШАБЛОН

<template>

<article>

    <template v-if="errorMessage == '' ">
            
        <Note :note="note" />

        <p class="mt5 mb3 f6" v-if="this.previousNoteUrl != '' || this.nextNoteUrl != ''">

            <template v-if="this.previousNoteUrl != ''">
                <router-link :to="previousNoteUrl" class="mr4 link blue bb dim">{{ previousNoteTitle }}</router-link> &larr;
            </template>
            Ctrl 
            <template v-if="this.nextNoteUrl != ''">
                &rarr; <router-link :to="nextNoteUrl" class="ml4 link blue bb dim">{{ nextNoteTitle }}</router-link>
            </template>            

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

import {api} from '../api';

import errorMixin   from '../mixins/error';
import routeMixin   from '../mixins/route';
import notesMixin   from '../mixins/notes';
import twitterMixin from '../mixins/twitter';

export default {


    name: 'NotePage',


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

            previousNoteUrl:    '',
            nextNoteUrl:        '',

            previousNoteTitle:  '',
            nextNoteTitle:      '',

            note:               [],
        
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

            this.loadNote();                

        }, // init


        loadNote: function () {

            let query   = '?module=notes&entity=note&note=' + this.$route.params.note;
            let tag     = this.$route.params.tag;
                                    
            if ( tag != undefined) {
                query = query + '&tag=' + tag;
            }

            api
                .get(query)
                .then(response => {

                    this.readNoteData(response.data);
                    
                })
                .catch(() => {

                    this.showServerErrorPage();

                });

        }, // loadNote


        readNoteData: function (data) {

            let dataFields = ['note', 'previousNote', 'nextNote'];
               
            if (this.isDataValid(data, dataFields)) this.showNote(data);

        }, // readNoteData


        showNote: function (data) {

            this.note = this.processNoteData(data.note);
            
            this.previousNoteTitle  = data.previousNote.title;
            this.nextNoteTitle      = data.nextNote.title;

            this.previousNoteUrl    = this.noteUrl(data.previousNote);
            this.nextNoteUrl        = this.noteUrl(data.nextNote);

            if (this.containsTwitterWidget(data.note.content)) {
                this.initiateTwitterWidget();
            }
            
            this.setPageTitle(this.note.title);
            this.scrollToTop();

        }, // showNote


        ctrlJump: function (zEvent) {
        
            if (! zEvent.ctrlKey) return;

            if (zEvent.keyCode === 37 && this.previousNoteUrl != '') {
                this.$router.push(this.previousNoteUrl);
            }
            else if (zEvent.keyCode === 39 && this.nextNoteUrl != '') {
                this.$router.push(this.nextNoteUrl);
            }

        }, // ctrlJump


    }, // methods


};   

</script>