///////////////////////////////////////////////////////////////////////////////
// ШАБЛОН

<template>

<header class="db dt-l w-100-l border-box pa3 ph5-l">    
        
    <nav class="flex items-center lh-copy pa3 ph0-l">

        <div class="pl4 flex-auto">

            <template v-if="isHomepage">
                <span class="black dib f4 mr3">{{$t('wrapper.site')}}</span>
            </template>
            <template v-else>
                <router-link class="dib f4 mr3 link dim bb blue" to="/">{{$t('wrapper.site')}}</router-link>
            </template>

            <template v-if="isNotes">
                <span class="orange dib f4 mr3">{{$t('wrapper.notes')}}</span>
            </template>
            <template v-else-if="isNotesSubpage">
                <router-link class="dib f4 mr3 link dim bb orange" to="/notes">{{$t('wrapper.notes')}}</router-link>
            </template>            
            <template v-else>
                <router-link class="dib f4 mr3 link dim bb blue" to="/notes">{{$t('wrapper.notes')}}</router-link>
            </template>

        </div>

    </nav>

    <nav class="dtc-l v-mid tc tr-l">                            
        <a class="link f5 f5-ns dim blue bb" :href="languageSwitcherURL">{{ languageSwitcherText }}</a>
    </nav>

</header>

</template>

///////////////////////////////////////////////////////////////////////////////
// СКРИПТ

<script>

import language from '../language';
import settings from '../settings';

export default {

    name: 'Header',

    data() {

        return {

            isHomepage:         false,
            isNotes:            false,
            isNotesSubpage:     false,

            languageSwitcherURL:    '',
            languageSwitcherText:   '',

        };

    }, // data

    methods: {

        updateLanguageSwitcher: function () {

            let anotherLocale = (language.locale == 'ru-Ru' ? 'en-US' : 'ru-Ru');            

            let protocol    = window.location.protocol;
            let domain      = settings.domains[anotherLocale];
            let path        = window.location.pathname;

            this.languageSwitcherText   = (anotherLocale == 'ru-Ru' ? 'RU' : 'EN');
            this.languageSwitcherURL    = `${protocol}//${domain}${path}`;

        }, // updateLanguageSwitcher

        updateMainMenu: function () {

            let path = window.location.pathname;

            this.isHomepage = path == '/';
            
            this.updateMainMenuItem('/notes', 'isNotes', 'isNotesSubpage');

        }, // updateMainMenu

        updateMainMenuItem: function (itemPath, isPage, isSubpage) {

            let path = window.location.pathname;

            if (path == itemPath) {

                this[isPage]      = true;
                this[isSubpage]   = false;

            }
            else {

                this[isPage]      = false;
                this[isSubpage]   = path.indexOf(itemPath) == 0;

            }

        }, // updateMainMenuItem

    }, // methods

    mounted() {
        
        this.updateMainMenu();
        this.updateLanguageSwitcher();

    }, // mounted

    watch: {

        '$route'() {

            this.updateMainMenu();
            this.updateLanguageSwitcher();

        }
        
    }, // watch

};

</script>