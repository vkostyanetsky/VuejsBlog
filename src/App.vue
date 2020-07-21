///////////////////////////////////////////////////////////////////////////////
// ШАБЛОН

<template>

<div id="app">

    <main>

        <Header/>

        <section class="pa3 pa5-ns bt b--black-10 black-70 bg-white">
            <router-view></router-view>
        </section>

    </main>

    <Footer/>

</div>        

</template>

///////////////////////////////////////////////////////////////////////////////
// СКРИПТ

<script>

export default {

    name: 'app',

    components: {

        Header: () => import('./components/Header'),
        Footer: () => import('./components/Footer'),

    }, // components
   
}

</script>

<style>

#app {    
    font-family: 'PT Sans', 'Avenir', Helvetica, Arial, sans-serif;
}

code {
    font-family: "Courier New", Courier, monospace, sans-serif;
	text-align: left;
	color: #555;
}

pre code {
	line-height: 1.6em;
	font-size: 14px;
}

pre {
	padding: 0.1em 0.5em 0.3em 0.7em;
	border-left: 11px solid #ccc;
	margin: 1.7em 0 1.7em 0.3em;
	overflow: auto;
	width: 93%;
}

</style>