<template>
  <div class="ui text menu">
    <a class="active item" v-on:click="$emit('today')">Vandaag</a>
    <a class="active item" v-on:click="$emit('prevyear')">&lt;</a>
    <a class="item">{{this.year}}</a>
    <a class="item" v-on:click="$emit('nextyear')">&gt;</a>
    <a class="active item" v-on:click="$emit('prevmonth')">&lt;</a>
    <a class="item">{{this.month}}</a>
    <a class="item" v-on:click="$emit('nextmonth')">&gt;</a>
    <p class="item right align">Totaal inkomsten: {{debit | currency}}</p>
    <p class="item right align">Totaal uitgaven: {{credit | currency}}</p>
    <p class="item right align">Huidig saldo: {{currentBalance | currency}}</p>
    <p class="item right align">Saldo: {{balance | currency}}</p>
  </div>
</template>

<script>
import formatters from "../mixins/formatters"
export default {
    name: "AccountInfo",
    props: ['year','month','debit','credit','currentBalance','balance'],
    filters:formatters.get(['currency'])
};
</script>

<style>
</style>