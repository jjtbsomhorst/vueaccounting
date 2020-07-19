<template>
    
    <div class="ui menu">
        
        <div v-bind:class="[currentView === 'settings' ? 'active' : '', 'item']" v-on:click="openSettings()">
            Instellingen
        </div>
        <div v-bind:class="[currentView == 'accountList' ? 'active' : '', 'item']" v-on:click="openAccounts()">
            Rekeningoverzicht
        </div>            
    </div>
</template>
<script>
export default {
    name: 'ApplicationMenu',
    
    data: function(){
        return {
            currentView: 'accountList',   
        }
    },
    methods: {
        openSettings: function(){
            this.currentView = 'settings';
            this.$emit('switch-menu','settings');
        },
        openAccounts: function(){
            this.currentView ='accountList';
            this.$emit('switch-menu','accountList');
        }

    }
}
</script>

<style>

</style>