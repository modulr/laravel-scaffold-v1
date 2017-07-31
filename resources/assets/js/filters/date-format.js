const dateFormat = {}

dateFormat.install = function (Vue) {
    Vue.filter('date-format', (date) => {
        return moment(date).format('LL');
    })
}

import moment from 'moment';

export default dateFormat
