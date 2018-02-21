'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var grid_button = document.querySelector('.products-actions-template-option-grid');
var list_button = document.querySelector('.products-actions-template-option-list');
var grid = document.querySelector('.products-grid');

grid_button.addEventListener('click', function (e) {
    e.preventDefault();
    grid.classList.add('products-grid-cards');
    list_button.classList.remove('products-actions-template-option-active');
    grid_button.classList.add('products-actions-template-option-active');
});

list_button.addEventListener('click', function (e) {
    e.preventDefault();
    grid.classList.remove('products-grid-cards');
    list_button.classList.add('products-actions-template-option-active');
    grid_button.classList.remove('products-actions-template-option-active');
});

var Products = function () {
    function Products(search, order, grid, count) {
        var _this = this;

        _classCallCheck(this, Products);

        this.$el = {};
        this.$el.search = search;
        this.$el.order = order;
        this.$el.grid = grid;
        this.$el.count = count;
        this.url = null;

        this.$el.search.addEventListener('keyup', function (e) {
            _this.request(_this.$el.search.value, _this.$el.order.value);
        });

        this.$el.order.addEventListener('change', function (e) {
            _this.request(_this.$el.search.value, _this.$el.order.value);
        });

        this.base_url();
    }

    _createClass(Products, [{
        key: 'request',
        value: function request(search, order) {
            var _this2 = this;

            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function (e) {
                if (e.target.readyState == 4 && e.target.status == 200) {
                    _this2.print(JSON.parse(e.target.responseText));
                }
            };

            xhttp.open('GET', 'api/products/?query=' + search + '&order=' + order, true);
            xhttp.send();
        }
    }, {
        key: 'base_url',
        value: function base_url() {
            var _this3 = this;

            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function (e) {
                if (e.target.readyState == 4 && e.target.status == 200) {
                    _this3.url = e.target.responseText;
                }
            };

            xhttp.open('GET', 'api/index', true);
            xhttp.send();
        }
    }, {
        key: 'print',
        value: function print(results) {
            var _this4 = this;

            this.$el.count.innerHTML = results.length + ' item(s)';
            this.$el.grid.innerHTML = '';

            results.forEach(function (result) {
                _this4.$el.grid.innerHTML += '\n                <div class="product-element">\n                    <div class="product">\n                        <div class="product-id">\n                            <span>' + _this4.pad(result.id) + '</span>\n                        </div>\n\n                        <div class="product-image">\n                            <img src="' + _this4.url + '/uploads/' + result.media + '" alt="Test">\n                        </div>\n\n                        <div class="product-title">\n                            <span>' + result.title + '</span>\n                        </div>\n\n                        <div class="product-category">\n                            <span>' + result.category + '</span>\n                        </div>\n\n                        <div class="product-price">\n                            <span>Price: </span>\n                            <span>$' + result.price + '</span>\n                        </div>\n\n                        <div class="product-quantity">\n                            <span>Quantity:</span>\n                            <span>' + result.quantity + '</span>\n                        </div>\n\n                        <div class="product-actions">\n                            <div class="product-actions-delete">\n                                <a href="' + _this4.url + 'products/6/delete">\n                                    <i class="fa fa-trash" aria-hidden="true"></i>\n                                </a>\n                            </div>\n\n                            <div class="product-actions-edit">\n                                <a href="' + _this4.url + 'products/6/edit">\n                                    <i class="fa fa-pencil" aria-hidden="true"></i>\n                                </a>\n                            </div>\n                        </div>\n                    </div>\n                </div>';
            });
        }
    }, {
        key: 'pad',
        value: function pad(value) {
            var size = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 5;

            var s = value + '';
            while (s.length < size) {
                s = '0' + s;
            }return s;
        }
    }]);

    return Products;
}();

var products = new Products(document.querySelector('#search'), document.querySelector('#order'), document.querySelector('.products-grid'), document.querySelector('.products-actions-count'));