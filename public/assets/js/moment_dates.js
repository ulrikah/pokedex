'use strict';

var dates = document.querySelectorAll('.moment-date');

dates.forEach(function (date) {
    var date_date = date.innerHTML;
    date.innerHTML = moment(date_date).fromNow();
});