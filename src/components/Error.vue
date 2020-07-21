///////////////////////////////////////////////////////////////////////////////
// ШАБЛОН

<template>

<span> 

    <h1 class="f-subheadline lh-solid">{{ title }}</h1>
    <div class="f4 lh-copy black measure-wide">{{ message }}</div>

</span>

</template>

///////////////////////////////////////////////////////////////////////////////
// СКРИПТ

<script>

export default {


    name: 'Error',


    props: {

        title:      '',
        message:    '',

    }, // props


};

</script>