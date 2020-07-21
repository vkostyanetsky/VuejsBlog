///////////////////////////////////////////////////////////////////////////////
// ФОРМАТЫ ДАТЫ И ВРЕМЕНИ (dateTimeFormats)

const dateTimeFormats = {
    
    'ru-Ru': {
        short: {
            year: 'numeric', month: 'long', day: 'numeric'
        },
        long: {
            year: 'numeric', month: 'short', day: 'numeric', weekday: 'short', hour: 'numeric', minute: 'numeric'
        },
    },

    'en-US': {
        short: {
            year: 'numeric', month: 'long', day: 'numeric'
        },
        long: {
            year: 'numeric', month: 'short', day: 'numeric', weekday: 'short', hour: 'numeric', minute: 'numeric'
        },
    },    

};

///////////////////////////////////////////////////////////////////////////////
// СООБЩЕНИЯ (messages)

const messages = {

    'ru-Ru': {

        // Общие

        wrapper: {
            site:       "Сайт",
            notes:      "Заметки",
            mainpage:   "Главная",
            email:      "Эл. почта",
            mailbox:    "username@server.ru",
            telegram:   "Телеграм",            
        },

        // Ошибки

        errors: {
            nonExistentPageTitle:       "Страница не найдена",
            nonExistentPageContent:     "Возможно, я её удалил или переименовал. А может, вы ошиблись адресом.",
            serverErrorPageTitle:       "Ошибка",
            serverErrorPageContent:     "Похоже, что-то сломалось. Скоро починю!",
        },

        // Заметки

        notes: {
            before:         "Ранее",
            after:          "Позднее",
            tags:           "Теги",
            notesCounter:   "нет заметок | 1 заметка | {n} заметки | {n} заметок",
        },

        // ...

    },

    'en-US': {

        // Общие

        wrapper: {
            site:       "The Site",
            notes:      "Notes",
            mainpage:   "Main",
            email:      "Email",
            mailbox:    "username@server.me",
            telegram:   "Telegram",            
        },

        // Ошибки

        errors: {
            nonExistentPageTitle:       "Page Not Found",
            nonExistentPageContent:     "I could have deleted it, or something else could happen. You could have typed the link incorrectly, for instance.",
            serverErrorPageTitle:       "Error",
            serverErrorPageContent:     "Something is broken here, I guess. I will fix this soon!",
        },

        // Заметки

        notes: {
            before:         "Before",
            after:          "Later",
            tags:           "Tags",
            notesCounter:   "no notes | 1 note | {n} notes | {n} notes",
        },

        // ...

    }

};

///////////////////////////////////////////////////////////////////////////////
// ЛОКАЛЬ (locale)

import settings from './settings';

let isEnglishDomain = document.domain.indexOf(settings.domains['en-US']) ==! -1;
let locale          = (isEnglishDomain ? 'en-US' : 'ru-Ru');

///////////////////////////////////////////////////////////////////////////////
// РЕЗУЛЬТАТ

export default {    
    dateTimeFormats:    dateTimeFormats,
    messages:           messages,
    locale:             locale,    
};