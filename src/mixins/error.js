var errorMixin = {


    data () {

        return {

            errorTitle:     '',
            errorMessage:   '',

        }

    }, // data


    methods: {


        showNonExistentPage: function () {

            this.errorTitle     = this._i18n.t('errors.nonExistentPageTitle');
            this.errorMessage   = this._i18n.t('errors.nonExistentPageContent');

            this.showError();

        }, // showNonExistentPage


        showServerErrorPage: function () {

            this.errorTitle     = this._i18n.t('errors.serverErrorPageTitle');
            this.errorMessage   = this._i18n.t('errors.serverErrorPageContent');

            this.showError();

        }, // showServerErrorPage


        showError: function () {

            this.setPageTitle(this.errorTitle);

            this.scrollToTop();

        }, // showError


    } // methods


}; // errorMixin

export default errorMixin;