///////////////////////////////////////////////////////////////////////////////
// ШАБЛОН

<template>

    <span v-if="note.title !== undefined">

        <template v-if="link">
            <h1 class="f2 lh-title"><router-link class="link blue bb dim" :to="note.url">{{ note.title }}</router-link></h1>
        </template>
        <template v-else>
            <h1 class="f1 lh-title">{{ note.title }}</h1>
        </template>
        
        <div class="f4 lh-copy black measure-wide mb4"><component :is="textComponent"></component></div>

        <p class="f6 mb5">{{ $d(note.date, 'short') }}

            <span v-for="tag in note.tags" :key="tag.name">

                <span class="pl2">
                    <template v-if="tag.active">
                        <router-link :to="tag.url" class="link orange bb dim">{{ tag.title }}</router-link>
                    </template>    
                    <template v-else>
                        <router-link :to="tag.url" class="link blue bb dim">{{ tag.title }}</router-link>
                    </template>                        
                </span>

            </span>

        </p>

    </span>

</template>

///////////////////////////////////////////////////////////////////////////////
// СКРИПТ

<script>

import Vue from 'vue'

export default {

    name: 'Note',

    data () {

        return {
            textComponent: '',
        }

    }, // data

    props: {

        note: Object,
        link: Boolean,

    }, // props

    mounted: function(){

        this.setText();

    }, // mounted

    updated: function() {    
        
        this.setText();

    }, // updated

    methods: {

        setText: function () {

            // Создаем динамический компонент с текстом заметки.

            let textComponent = "note-" + this.note.name;

            if ( textComponent == this.textComponent ) return;

            this.textComponent = textComponent;

            Vue.component(
                this.textComponent, {
                    template: '<span>' + this.note.content + "</span>"
                }
            );
                    
        }, // setText

    }, // methods

};

</script>