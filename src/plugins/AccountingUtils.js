import moment from "moment"
import axios from 'axios';

const AccountingUtil = {
    // The install method is all that needs to exist on the plugin object.
    // It takes the global Vue object as well as user-defined options.
    getAccounts: async function(){
        let result = await axios.get("http://localhost/vue-accounting/src/api/api.php/accounts",{headers: {"OAuth-Token": localStorage.getItem("oauthToken")}});
        return result.data;
    },
    storeAccounts: function(accounts){
        localStorage.setItem("accounts",JSON.stringify(accounts));
    },
    //given an account number gets the current balance
    getBalance: function(accountid,y,m){
        console.log("Get balance for account "+accountid);
        let year = y || moment().format("YYYY");
        let month = m || moment().format("M");
        let key = this.getStorageKey(accountid,year,month);
        if(localStorage.getItem(key)!== null){
            return JSON.parse(localStorage.getItem(key)).balance
        }
        return 0;
    },
    // retrieve entries for the given account
    getAccount: async function(accountid){
        let url = "http://localhost/vue-accounting/src/api/api.php/accounts/?/entries";
        //let key = year+"::"+month;
        url = url.replace("?",accountid);
        //url += "?period="+key;
        let result = await axios.get(url,{headers:{"OAuth-Token": localStorage.getItem("oauthToken")}});
        return result.data;
    },
    // add a new entry to the given account
    addItem: async function(accountid,entry){
        let url = "http://localhost/vue-accounting/src/api/api.php/accounts/?/entries"
        url = url.replace("?",accountid);
        let result = await axios.post(url,entry,{headers:{"OAuth-Token": localStorage.getItem("oauthToken")}});
        return result.data;
    },
    updateItem: async function(accountid,entry){
        let url = "http://localhost/vue-accounting/src/api/api.php/accounts/?/entries/?"
        url = url.replace("?",accountid).replace("?",entry.id);
        let result = await axios.put(url,entry,{headers:{"OAuth-Token": localStorage.getItem("oauthToken")}});
        return result.data;
    },
    removeItem: async function(accountid,entry){
        let url = "http://localhost/vue-accounting/src/api/api.php/accounts/?/entries/?"
        url = url.replace("?",accountid).replace("?",entry.id);
        let result = await axios.delete(url,{},{headers:{"OAuth-Token": localStorage.getItem("oauthToken")}});
        return result.data;
    }
  };
  
  export default AccountingUtil;