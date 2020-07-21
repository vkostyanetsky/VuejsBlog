///////////////////////////////////////////////////////////////////////////////
// ШАБЛОН

<template>

    <article> 

        <template v-if="errorMessage == '' ">
            
            <template v-if="pageTitle !== ''">
                <h1 class="f-subheadline lh-solid">{{ pageTitle }}</h1>
            </template>

            <div class="f4 lh-copy black measure-wide"><component :is="textComponent"></component></div>

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

import errorMixin from '../mixins/error';
import routeMixin from '../mixins/route';

export default {


    name: 'Page',


    components: {

        Error: () => import('../components/Error'),

    }, // components


    mixins: [errorMixin, routeMixin],


    data () {

        return {

            textComponent: '',

        };

    }, // data


    beforeRouteEnter (to, from, next) {

        next(vm => {
            vm.init(to.fullPath);
        })

    }, // beforeRouteEnter


    beforeRouteUpdate (to, from, next) {
        
        next();

        this.init(to.fullPath);

    }, // beforeRouteUpdate


    methods: {


        init: function (fullPath) {

            this.loadPage(fullPath);
    
        }, // init


        loadPage: function (fullPath) {

            let query = '?module=pages&path=' + fullPath;
                                    
            api
                .get(query)
                .then(response => {

                    this.readPageData(response.data);
                    
                })
                .catch(() => {

                    this.showServerErrorPage();

                });

        }, // loadPage


        readPageData: function (data) {

            let dataFields = ['page'];
            
            if (this.isDataValid(data, dataFields)) this.showPage(data);

        }, // readPageData


        showPage: function (data) {

            let page = this.processPageData(data.page);

            this.setText(page.content, page.path);
            this.setPageTitle(page.title);
            this.scrollToTop();

        }, // showPage


        setText: function (content, path) {

            let textComponent = "page-" + path;

            if ( textComponent == this.textComponent ) return;

            this.textComponent = textComponent;

            Vue.component(
                this.textComponent, {
                    template: '<span>' + content + "</span>"
                }
            );
                    
        }, // setText


        processPageData: function (pageData) {

            return {
                path:       pageData.path,
                title:      pageData.title,
                content:    pageData.content,
            };

        }, // processPageData


    }, // methods

};   

</script>