<template>
  <form class="ui form" @submit="validateAndLogin">
  <div class="field">
    <label>First Name</label>
    <input type="text" name="first-name" v-model="username" placeholder="username"/>
  </div>
  <div class="field">
    <label>Last Name</label>
    <input type="password" name="last-name" v-model="password" placeholder="password"/>
  </div>
  
  <button class="ui button" type="submit">Submit</button>
</form>
</template>

<script>
import axios from 'axios';
export default {
    name: "LoginForm",
    props: [],
    data: function(){
        return {
            username: null,
            password: null,
            waiting: false,
        }
    },
    methods:{
        validateAndLogin: function(e){
             e.preventDefault();
            if(this.username != null && this.password != null){
                axios.post("http://localhost/vue-accounting/src/api/api.php/oauth/token", {
               
                    username: this.username,
                    password: this.password
                }
    )
    .then(response => {
      console.log('stuff');
        localStorage.setItem("oauthToken",response.data.token);
        this.$emit('loggedIn');
    })
    .catch(()=>{
      console.log('fout!');
    })
            }
        }
    }
}
</script>

<style>

</style>