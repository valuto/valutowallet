<script type="text/x-template" id="product-slider-template">
    <div class="product-slider" v-show="products.length">
        <div class="inner">

            <product-slider-product :product="productOne" :vlumarketBaseUrl="vlumarketBaseUrl" v-show="products.length > 0"></product-slider-product>
            <product-slider-product :product="productTwo" :vlumarketBaseUrl="vlumarketBaseUrl" v-show="products.length > 1"></product-slider-product>
            <product-slider-product :product="productThree" :vlumarketBaseUrl="vlumarketBaseUrl" v-show="products.length > 2"></product-slider-product>
    
        </div>
        <div class="arrow-left">
            <img v-on:click="prev()" src="/assets/img/arrow_left_180px.png" srcset="/assets/img/arrow_left_180px.png 3x" alt="">
        </div>
        <div class="arrow-right">
            <img v-on:click="next()" src="/assets/img/arrow_right_180px.png" srcset="/assets/img/arrow_right_180px.png 3x" alt="">
        </div>
    </div>
</script>