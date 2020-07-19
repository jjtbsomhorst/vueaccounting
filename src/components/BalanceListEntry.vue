<template>
  <tr @mouseover="onMouseOver()" @mouseleave="onMouseLeave()">
        <td>{{date}}</td>
        <td>{{description}}</td>
        <td class="right aligned">{{amount | currency}}</td>
        <td>{{dc}}</td>
        <td></td>
    </tr>
</template>

<script>
import moment from 'moment'
import formatters from "../mixins/formatters"

export default {
    name: 'BalanceListEntry',
    props: ['amount','year','month','description'],
    filters: formatters,
    computed: {
        dc: function(){
            if(this.amount < 0){
                return 'C';
            }
            return 'D';
        },
        date: function(){
            let m = moment().year(this.year).month(this.month-1).date(1);
            return m.format("YYYY-MM-DD");
        }
    },
    
    methods: {
        onMouseOver: function(){},
        onMouseLeave: function(){}
    }
}

</script>

<style>

</style>