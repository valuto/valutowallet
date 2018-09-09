Vue.component('product-slider-product', {
    template: '#product-slider-product-template',
    props: [
        'product',
        'vlumarketBaseUrl',
    ],
    created: function() {

    },
    methods: {
        gotoProduct: function() {
            window.location.href = '/' + this.product.category.slug + '/' + this.product.slug;
        }
    }
});

Vue.component('product-slider', {
    template: '#product-slider-template',
    props: [
        'products', 
        'vlumarketBaseUrl',
    ],
    data() {
        return {
            productOne: false,
            productTwo: false,
            productThree: false,
            indexOne: 0,
            indexTwo: 1,
            indexThree: 2,
        }
    },
    created: function() {
        this.rotateProducts();
    },
    methods: {
        prev: function () {

            this.indexOne--;
            this.indexTwo--;
            this.indexThree--;

            if (this.indexOne < 0) {
                this.indexOne = this.products.length-1;
            }

            if (this.indexTwo < 0) {
                this.indexTwo = this.products.length-1;
            }

            if (this.indexThree < 0) {
                this.indexThree = this.products.length-1;
            }

            console.log(this.indexOne, this.indexTwo, this.indexThree);

            this.rotateProducts();
        },
        next: function () {
            this.rotateProducts();
        },
        gotoProduct: function() {
            window.location.href = '/' + this.product.category.slug + '/' + this.product.slug;
        },
        rotateProducts: function() {

            if (this.products.length > 0) {
                this.productOne = this.products[this.indexOne];
            }
            
            if (this.products.length > 1) {
                this.productTwo = this.products[this.indexTwo];
            }
            
            if (this.products.length > 2) {
                this.productThree = this.products[this.indexThree];
            }
        },
    }
});

var vueapp = new Vue({
    el: '#vueapp',
    data: {
        showtab: 'wallet',
    }
});
var navigation = new Vue({
    el: '#navigation',
    methods: {
        makeActive: function(item, event) {
            vueapp.showtab = item;
            $(event.currentTarget).closest('nav').find('li').removeClass('active');
            $(event.currentTarget).closest('li').addClass('active');
        }
    }
});