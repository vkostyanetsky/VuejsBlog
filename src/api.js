import axios from 'axios';

import settings from './settings';
import language from './language';

let protocol    = window.location.protocol;
let domain      = settings.domains[language.locale];
let url         = `${protocol}//${domain}/api.php`;

export const api = axios.create({
    baseURL: url
});