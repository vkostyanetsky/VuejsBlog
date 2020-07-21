var routeMixin = {


    data () {

        return {

            pageTitle: '',

        }

    }, // data


    methods: {


        setPageTitle: function (pageTitle) {

            let siteTitle = this._i18n.t('wrapper.site');

            this.pageTitle = pageTitle;
                
            if (pageTitle == '') {
                document.title = siteTitle;
            }
            else {
                document.title = `${pageTitle} - ${siteTitle}`;
            }

        }, // setPageTitle


        scrollToTop: function () {

            window.scrollTo(0, 0);

        }, // scrollToTop


        isDataValid: function (data, dataFields) {

            // Флаг "isError" должен быть в ответе всегда. Если его там нет,
            // это какой-то неправильный ответ; отображаем страницу ошибки.

            if (!('isError' in data)) {

                this.showServerErrorPage();
                return false;                
            }

            // Проверяем, что значение флага ошибки "isError" — не "истина".
            // Если это так — определяем, что за ошибка и выводим её.

            if (data.isError) {

                switch(data.errorCode) {

                    case 404:
                        this.showNonExistentPage();
                        break;

                    default:
                        this.showServerErrorPage();

                }

                return false;
            }

            // Проверям, что в данных есть ожидаемые поля, характерные
            // для нормального (успешного) ответа на запрос.

            for (var index in dataFields) {

                let field = dataFields[index];

                if (!(field in data)) {

                    this.showServerErrorPage();
                    return false;                
                }
            }

            // Что же, данные выглядят корректными.

            return true;

        }, // isDataValid


    } // methods


}; // routeMixin

export default routeMixin;