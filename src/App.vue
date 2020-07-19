<template>
  <div class="ui container">
    <application-menu current-view="currentView" v-on:switch-menu="switchMenu"/>
    <application-canvas :view-mode="currentView" v-on:switch-menu="switchMenu"/>
  </div>
</template>

<script>
import ApplicationMenu from './components/ApplicationMenu.vue'
import AccountingPlugins from './plugins/AccountingUtils'
import ApplicationCanvas from './components/ApplicationCanvas.vue'
export default {
  name: 'app',
  components: {
    ApplicationMenu,
    ApplicationCanvas
  },
  data: function(){
    return {
      currentView: 'login',
    }
      
  },
  methods: {
    switchMenu: function(v){
        this.currentView = v;
    }
  },
  beforeMount: function(){
    let token = localStorage.getItem("oauthToken");
    if(token != null){
      let response = AccountingPlugins.getAccounts();
      response.then((data)=>{
        console.log(data);
        this.currentView = 'accountList';
      }).catch((error)=>{
        console.log(error);
        localStorage.removeItem("oauthToken");
      });
    }
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
