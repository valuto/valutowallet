<script type="text/x-template" id="product-slider-product-template">

    <div class="product" v-if="product">
        <div class="product-image">
            <img v-bind:src="vlumarketBaseUrl + '/storage/' + product.primary_picture.directory + '/normal_images/' + product.primary_picture.image_url" :alt="product.subject" />
        </div>

        <div class="under" v-on:click="gotoProduct()">
            <div class="under-inner">
                <h1>
                    <a v-bind:href="'/vlumarket/redirect?url=' + vlumarketBaseUrl + '/' + product.category.slug + '/' + product.slug">{{ product.subject }}</a>
                </h1>
                <div class="orange-container">
                    <span class="pre-price">ONLY</span> <span>EUR</span> {{ product.price }}
                </div>
            </div>
        </div>
    </div>

</script>