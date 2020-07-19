<template>
    <div>
        {{viewMode}}
        <account-list v-if="viewMode === 'accountList'" @open-account='openAccount'/>
        <account-over-view v-if="viewMode === 'account' && currentAccount != null" v-bind:account="currentAccount" />
        <application-settings v-if="viewMode === 'settings'"/>
        <login-form v-if="viewMode === 'login'" @loggedIn="$emit('switch-menu','accountList');"/>
    </div>
</template>
<script>
import AccountList from './AccountList.vue'
import AccountOverView from './AccountOverView'
import ApplicationSettings from './ApplicationSettings'
import LoginForm from './LoginForm';

export default {
    name: 'ApplicationCanvas',
    props: ['viewMode'],
    components:{
        AccountList,
        AccountOverView,
        ApplicationSettings,
        LoginForm
    },
    data: function(){
        return {
            view: this.viewMode,
            currentAccount: null
        };
    },
    methods: {
        openAccount: function(account){
            this.currentAccount = account;
            this.$emit('switch-menu','account');
        }
    }
}
</script>