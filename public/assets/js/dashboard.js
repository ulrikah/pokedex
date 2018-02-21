'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Stats = function () {
    function Stats(data, canvas, type, value) {
        _classCallCheck(this, Stats);

        this.$canvas = canvas.getContext('2d');
        this.data = data;
        this.type = type;
        this.value = value;

        this.prepare(this.data);
    }

    _createClass(Stats, [{
        key: 'prepare',
        value: function prepare(data) {
            var _this = this;

            var backgrounds = ['#2ecc71', '#3498db', '#95a5a6', '#9b59b6', '#f1c40f', '#e74c3c', '#34495e'];

            var object = {};
            object.labels = [];

            var datasets = {};
            datasets.backgroundColor = [];
            datasets.data = [];

            var i = 0;
            data.categories.forEach(function (category) {
                object.labels.push(category.name);
                datasets.backgroundColor.push(backgrounds[i % 7]);
                datasets.data.push(category[_this.value]);
                i++;
            });

            object.datasets = [datasets];
            this.print(object);
        }
    }, {
        key: 'print',
        value: function print(object) {
            new Chart(this.$canvas, {
                type: this.type,
                data: object
            });
        }
    }]);

    return Stats;
}();

var xhttp = new XMLHttpRequest();

xhttp.onreadystatechange = function (e) {
    if (e.target.readyState == 4 && e.target.status == 200) {
        var stats1 = new Stats(JSON.parse(e.target.responseText), document.querySelector('.categories_stats'), 'doughnut', 'count');

        var stats2 = new Stats(JSON.parse(e.target.responseText), document.querySelector('.quantities_stats'), 'polarArea', 'quantity');
    }
};

xhttp.open('GET', 'api/stats/', true);
xhttp.send();