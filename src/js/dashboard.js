class Stats {
    constructor(data, canvas, type, value) {
        this.$canvas = canvas.getContext('2d')
        this.data    = data
        this.type    = type
        this.value   = value

        this.prepare(this.data)
    }

    prepare(data) {
        let backgrounds = ['#2ecc71', '#3498db', '#95a5a6', '#9b59b6', '#f1c40f', '#e74c3c', '#34495e']

        let object    = {}
        object.labels = []

        let datasets             = {}
        datasets.backgroundColor = []
        datasets.data            = []

        let i = 0
        data.categories.forEach((category) => {
            object.labels.push(category.name)
            datasets.backgroundColor.push(backgrounds[i % 7])
            datasets.data.push(category[this.value])
            i++
        })

        object.datasets = [datasets]
        this.print(object)
    }

    print(object) {
        new Chart(this.$canvas, {
            type: this.type,
            data: object
        })
    }
}


let xhttp = new XMLHttpRequest()

xhttp.onreadystatechange = (e) => {
    if (e.target.readyState == 4 && e.target.status == 200) {
        let stats1 = new Stats(
            JSON.parse(e.target.responseText),
            document.querySelector('.categories_stats'),
            'doughnut',
            'count'
        )

        let stats2 = new Stats(
            JSON.parse(e.target.responseText),
            document.querySelector('.quantities_stats'),
            'polarArea',
            'quantity'
        )
    }
}

xhttp.open('GET', `api/stats/`, true)
xhttp.send()
