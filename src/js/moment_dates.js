let dates = document.querySelectorAll('.moment-date')

dates.forEach((date) => {
    let date_date  = date.innerHTML
    date.innerHTML = moment(date_date).fromNow()
})
