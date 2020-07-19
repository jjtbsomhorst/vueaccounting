<template>
<div class="ui container">
    <div class="ui secondary text  menu">
        <a class="active item" v-on:click='addMode = true'>
            Nieuwe rekening
        </a>
    </div>
    <div class="ui list">
        
        <div v-show="accounts.length == 0">
            <p>Geen rekeningen gevonden</p>
        </div>
        <account-entry v-for="account in accounts" v-bind:key="account.accountnumber" v-bind:account="account" v-on:openAccount="openAccount"/>


        <div class="item" v-show="addMode">
            <i class="balance scale icon"/>
            <div class="content">
                <a class="header ui transparent input"><input placeholder="rekeningnaam" type="text" v-model="currentAccount.name" v-on:keyup.enter="addAccount()"/></a>
                <div class="description ui transparent input">
                    <input type="text"  v-model="currentAccount.number" placeholder="rekeningnummer" v-on:keyup.enter="addAccount()"/>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import AccountingPlugins from '../plugins/AccountingUtils'
import AccountEntry from './AccountEntry'

import { uuid } from 'vue-uuid'; 

export default {
    name: 'AccountList',
    components: {
        AccountEntry
    },
    mounted(){
        let response = AccountingPlugins.getAccounts();
        response.then((data)=>{
            console.log('stuff');
            this.accounts = data;
        }).catch((error)=>{
            console.log(error);
            this.accounts=null;
        });

    },
    data: function(){
        return{
            currentAccount: {},
            addMode: false,
            accounts:[],
        }
    },
    beforeDestroy(){
        AccountingPlugins.storeAccounts(this.accounts);
    }, 

    methods: {
        created: function(){
           
        },
        addAccount: function(){
            this.currentAccount.id = uuid.v4();
            this.accounts.push(this.currentAccount);
            this.currentAccount = {};
            this.addMode=false;
        },
        openAccount: function(account){
            console.debug('open account');
            this.$emit('open-account',account);
        }
    }
}
</script>

<style>

</style>