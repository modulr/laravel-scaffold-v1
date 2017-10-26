const dateFormat = {}

dateFormat.install = function (Vue) {
    Vue.filter('date-format', (date) => {
        return moment(date).format('LL')
    });
    Vue.filter('time-format', (date) => {
        return moment(date, 'HH:mm:ss').format('LT')
    })
}

import moment from 'moment';

export default dateFormat
