///////////////////////////////////////////////////////////////////////////////
// ШАБЛОН

<template>

<article>
        
    <template v-if="errorMessage == '' ">

        <h1 class="f-subheadline lh-solid">{{ pageTitle }}</h1>
                        
        <p class="f4 lh-copy black measure-wide">    
                        
            <ul class="list pl0 lh-copy">
                        
                <li v-for="tag in tags" :key="tag.name">
                    <router-link :to="tag.url" class="link blue bb dim">{{ tag.title }}</router-link> ({{ $tc('notes.notesCounter', tag.counter) }})
                </li>

            </ul>

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

import errorMixin from '../mixins/error';
import routeMixin from '../mixins/route';
import notesMixin from '../mixins/notes';

export default {


    name: 'NoteTags',


    mixins: [errorMixin, routeMixin, notesMixin],


    components: {

        Error: () => import('../components/Error'),

    }, // components


    data () {

        return {

            pageTitle:  '',
            tags:       [],

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

            this.loadTags();
    
        }, // init


        loadTags: function () {

            let query = '?module=notes&entity=tags';
                                    
            api
                .get(query)
                .then(response => {

                    this.readTagsData(response.data);
                    
                })
                .catch(() => {

                    this.showServerErrorPage();

                });

        }, // loadTags


        readTagsData: function (data) {

            let dataFields = ['tags'];

            if (this.isDataValid(data, dataFields)) this.showTags(data);
            
        }, // readTagsData


        showTags: function (data) {

            this.tags = [];
                        
            for (var index in data.tags) {
                
                let tagData     = data.tags[index];
                let tagTitle    = this.tagTitle(tagData.title);
                let tagUrl      = this.tagUrl(tagData.name);

                let tag = {
                    url:        tagUrl,
                    name:       tagData.name,
                    title:      tagTitle,
                    counter:    tagData.counter,
                };

                this.tags.push(tag);
            }

            this.setPageTitle(this._i18n.t('notes.tags'));
            this.scrollToTop();

        }, // showTags


    }, // methods


};   

</script>