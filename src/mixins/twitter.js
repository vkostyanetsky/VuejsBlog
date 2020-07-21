var twitterMixin = {


    data () {

        return {

            twitterWidgetURL: 'https://platform.twitter.com/widgets.js',

        }

    }, // data


    methods: {


        containsTwitterWidget: function (content) {
            
            return (content.indexOf(this.twitterWidgetURL) !== -1);

        }, // containsTwitterWidget


        initiateTwitterWidget: function () {

            let twitterWidget = document.createElement('script');

            twitterWidget.setAttribute('src', this.twitterWidgetURL);
    
            document.head.appendChild(twitterWidget);    

        }, // initiateTwitterWidget


    } // methods


}; // twitterMixin

export default twitterMixin;