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