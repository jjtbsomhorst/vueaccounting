<template>
    
  <div class="item" @mouseover="showIcons = true" @mouseout="showIcons = false">
            <div class="right floated content compact" v-show="showIcons">
                <i class="close icon"></i>
            </div>
            <i class="balance scale icon"></i>
            <div class="content" v-on:click="$emit('openAccount',account)">
                <a class="header" >{{account.name}}</a>
                <div class="description">{{balance | currency}}</div>
            </div>
    </div>
    
</template>

<script>
import AccountingPlugins from '../plugins/AccountingUtils'
import formatters from "../mixins/formatters";
export default {
    name: 'AccountEntry',
    props: ['account'],
    computed: {
        balance: function(){
            
           return AccountingPlugins.getBalance(this.account.id)
        }
    },
    data: function(){
        return {
        showIcons: false
    }},
    filters: formatters.get(["currency"]),
}
</script>

<style>

</style>