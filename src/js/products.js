let grid_button = document.querySelector('.products-actions-template-option-grid')
let list_button = document.querySelector('.products-actions-template-option-list')
let grid = document.querySelector('.products-grid')

grid_button.addEventListener('click', (e) => {
    e.preventDefault()
    grid.classList.add('products-grid-cards')
    list_button.classList.remove('products-actions-template-option-active')
    grid_button.classList.add('products-actions-template-option-active')
})

list_button.addEventListener('click', (e) => {
    e.preventDefault()
    grid.classList.remove('products-grid-cards')
    list_button.classList.add('products-actions-template-option-active')
    grid_button.classList.remove('products-actions-template-option-active')
})

class Products {
    constructor(search, order, grid, count) {
        this.$el        = {}
        this.$el.search = search
        this.$el.order  = order
        this.$el.grid   = grid
        this.$el.count  = count
        this.url        = null

        this.$el.search.addEventListener('keyup', (e) => {
            this.request(this.$el.search.value, this.$el.order.value)
        })

        this.$el.order.addEventListener('change', (e) => {
            this.request(this.$el.search.value, this.$el.order.value)
        })

        this.base_url()
    }

    request(search, order) {
        let xhttp = new XMLHttpRequest()

        xhttp.onreadystatechange = (e) => {
            if (e.target.readyState == 4 && e.target.status == 200) {
                this.print(JSON.parse(e.target.responseText))
            }
        }

        xhttp.open('GET', `api/products/?query=${search}&order=${order}`, true)
        xhttp.send()
    }

    base_url() {
        let xhttp = new XMLHttpRequest()

        xhttp.onreadystatechange = (e) => {
            if (e.target.readyState == 4 && e.target.status == 200) {
                this.url = e.target.responseText
            }
        }

        xhttp.open('GET', `api/index`, true);
        xhttp.send();
    }

    print(results) {
        this.$el.count.innerHTML = `${results.length} item(s)`
        this.$el.grid.innerHTML = ''

        results.forEach((result) => {
            this.$el.grid.innerHTML += `
                <div class="product-element">
                    <div class="product">
                        <div class="product-id">
                            <span>${this.pad(result.id)}</span>
                        </div>

                        <div class="product-image">
                            <img src="${this.url}/uploads/${result.media}" alt="Test">
                        </div>

                        <div class="product-title">
                            <span>${result.title}</span>
                        </div>

                        <div class="product-category">
                            <span>${result.category}</span>
                        </div>

                        <div class="product-price">
                            <span>Price: </span>
                            <span>$${result.price}</span>
                        </div>

                        <div class="product-quantity">
                            <span>Quantity:</span>
                            <span>${result.quantity}</span>
                        </div>

                        <div class="product-actions">
                            <div class="product-actions-delete">
                                <a href="${this.url}products/6/delete">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </div>

                            <div class="product-actions-edit">
                                <a href="${this.url}products/6/edit">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>`
        })
    }

    pad(value, size = 5) {
        let s = value + '';
        while (s.length < size) s = '0' + s;
        return s;
    }
}

let products = new Products(
    document.querySelector('#search'),
    document.querySelector('#order'),
    document.querySelector('.products-grid'),
    document.querySelector('.products-actions-count')
)
