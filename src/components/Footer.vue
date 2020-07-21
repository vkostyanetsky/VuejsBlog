///////////////////////////////////////////////////////////////////////////////
// ШАБЛОН

<template>

<footer class="ph3 ph4-ns pv4 bt b--black-10 black">

    <p class="f6 lh-solid">{{ $t('wrapper.email') }}: <a class="link dim bb black" :href="mailbox">{{$t('wrapper.mailbox')}}</a></p>
    <p class="f6 lh-solid">{{ $t('wrapper.telegram') }}: <a class="link dim bb black" href="tg://resolve?domain=username">username</a></p>

    <div class="mt3 f6 dib">

        <span class="pr2"><router-link class="link dim bb mid-gray" to="/">{{$t('wrapper.mainpage')}}</router-link></span>
        <span class="pr2"><router-link class="link dim bb mid-gray" to="/notes">{{$t('wrapper.notes')}}</router-link></span>
        <span class="pr2"><a class="link dim bb mid-gray" href="/rss.xml">RSS</a></span>
                  
    </div>

</footer>

</template>

///////////////////////////////////////////////////////////////////////////////
// СКРИПТ

<script>

export default {

    name: 'Footer',

    data() {

        return {
            mailbox: ''
        }

    },

    mounted() {
            
        this.mailbox = 'mailto:' + this._i18n.t('wrapper.mailbox')

    },

};

</script>