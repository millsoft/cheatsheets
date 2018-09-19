var app = new Vue({
    el: '#cheatapp',
    delimiters: ['${', '}'],
    data: {
        cheatsheets : {},
        cardVisibility: "all"
    },

    methods: {
        loadSheets: function(){
            var t = this;
            axios.get("cheatsheet/" + BOOK_ID + "/j").then(function(resp){
                t.cheatsheets = resp.data.cheatsheets;
            });
        },

        toggleCards: function(){
            if(this.cardVisibility == 0){
                this.cardVisibility = 1;
            }else if(this.cardVisibility == 1){
                this.cardVisibility = 2;
            }else if(this.cardVisibility == 2){
                this.cardVisibility = 0;
            }
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