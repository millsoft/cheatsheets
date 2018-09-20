var app = new Vue({
    el: '#cheatapp',
    delimiters: ['${', '}'],
    data: {
        cheatsheets : {},
        cardVisibility: "all"
    },

    methods: {
        loadSheets: function(){
            if(typeof BOOK_ID === "undefined"){
                return false;
            }
            var t = this;
            axios.get("cheatsheet/" + BOOK_ID + "/j").then(function(resp){
                t.cheatsheets = resp.data.cheatsheets;
            });
        },

        toggleVisibility: function(card){
            var newVal = null;
            if(typeof card['show'] === 'undefined' || card['show'] == false){
                newVal = true;
            }else{
                newVal = false;
            }

            Vue.set(card, 'show', newVal);
        }
    },
    mounted: function(){
        this.loadSheets();
    }
});