<template>
  <div class="ui segment basic">
    <h1>{{account.name}} ({{account.number}})</h1>
    <div class="ui row">
      <table class="ui celled table compacted">
                  <tbody>
                    <account-list-entry
                      v-bind:currentYear="this.year"
                      v-bind:currentMonth="this.month"
                      v-bind:onclick="false"
                      v-on:removeEntry="removeEntry"
                      v-on:saveEntry="addEntry"
                      v-bind:editable="true"
                      v-bind:showSaveButton="true"
                      v-bind:showRemoveButton="false"
                      v-bind:showCopyButton="false"
                    />
                  </tbody>
                </table>
    </div>
    <div class="ui row">
      <account-info
            v-bind:year="year"
            v-bind:month="monthDescription"
            v-on:nextmonth="nextMonth"
            v-on:prevmonth="prevMonth"
            v-on:nextyear="nextYear"
            v-on:prevyear="prevYear"
            v-on:today="today"
            v-bind:balance="balance"
            v-bind:currentBalance="currentBalance"
            v-bind:debit="debit"
            v-bind:credit="credit"
          />
    </div>

    <div class="ui row">
      <div v-bind:class="[showWarning ? 'show' : 'hidden', 'ui message transition']">
            <div class="header">Voeg standaardregels toe?</div>
            <p>Er zijn nog geen regels in deze periode. Standaard regels toepassen?</p>
            <a @click="showWarning=false;">Nee</a>&nbsp;
            <a @click="applyDefaults()">Ja</a>
          </div>
          <!-- graph stuffs -->
          <div class="ui container">
            <div class="ui grid">
              <div class="two wide column">
                <graph-dashlet type="pie" v-bind:data="gainings"></graph-dashlet>
              </div>
              <div class="two wide column">
                <graph-dashlet type="pie" v-bind:data="expenses"></graph-dashlet>
              </div>
            </div>
          </div>

          <!-- account content -->
          <table class="ui celled table compact">
            <thead>
              <tr>
                <th>Datum</th>
                <th>Omschrijving</th>
                <th>Bedrag</th>
                <th>D/C</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <balance-list-entry
                v-bind:year="year"
                v-bind:month="month"
                v-bind:amount="previousBalance"
                v-bind:description="'Saldo'"
              />
              <account-list-entry
                v-bind:onclick="true"
                v-bind:currentYear="year"
                v-bind:currentMonth="month"
                v-for="(entry,index) in entrykeys"
                v-bind:key="entry"
                v-bind:index="index"
                v-bind:maxIndex="entrykeys.length-1"
                @removeEntry="removeEntry"
                @saveEntry="addEntry"
                v-bind:entry="entries[index]"
                v-bind:editable="false"
                v-bind:showRemoveButton="true"
                v-bind:showCopyButton="false"
                v-bind:enableHover="true"
                v-on:moveup="moveUp"
                v-on:movedown="moveDown"
              />
              <tr v-if="entries.length === 0">
                <td colspan="4">No entries found for this account</td>
              </tr>
            </tbody>
          </table>
    </div>
    
  </div>
</template>

<script>
import moment from "moment";
import { uuid } from "vue-uuid";
import AccountListEntry from "./AccountListEntry";
import BalanceListEntry from "./BalanceListEntry";
import AccountInfo from "./AccountInfo";
import GraphDashlet from "./GraphDashlet";
import AccountingUtils from '../plugins/AccountingUtils'

export default {
  name: "AccountOverView",
  props: {
    account: Object
  },
  components: {
    AccountListEntry,
    BalanceListEntry,
    AccountInfo,
    GraphDashlet
  },
  
  computed: {
    expenses: function() {
      let expenses = this.entries.filter(element => {
        return element.dc == "C";
      });
      return expenses;
    },
    gainings: function() {
      let gainings = this.entries.filter(element => {
        return element.dc == "D";
      });
      return gainings;
    },
    
    currentBalance: function() {
      // get current date
      // get all entries until this date
      // calculate difference
      let today = this.endPeriod;
      if(moment().isBetween(this.startPeriod,this.endPeriod)){
        today = moment();
      }
      
      let entries = this.entries.filter(element => {
        if (element.date !== null && !element.date !== "") {
          let elementDate = moment(element.date);
          return elementDate.isSameOrBefore(today);
        }
        return false;
      });

      let amount = this.previousBalance;
      entries.forEach(element => {
        let entryAmount = parseFloat(element.amount);
        if (element.dc == "D") {
          amount += entryAmount;
        } else {
          amount -= entryAmount;
        }
      });
      return amount;
    },

startPeriod: function(){ return moment().year(this.year).month(this.month-1).date(1)},
    endPeriod: function(){ return this.startPeriod.clone().add(1,"month").subtract(1,"days")},

    balance: function() {
      return this.previousBalance + (this.debit - this.credit);
    },

    previousBalance: function() {
      
      let year = this.year;
      let month = this.month - 1;

      if (month == 0) {
        month = 12;
        year = year - 1;
      }

      return AccountingUtils.getBalance(year,month,this.account.id);
    },
    debit: function() {
      let amount = 0;
      this.entries.forEach(element => {
        if (element.dc == "D") {
          amount += parseFloat(element.amount);
        }
      });

      return amount;
    },
    credit: function() {
      let amount = 0;
      this.entries.forEach(element => {
        if (element.dc == "C") {
          amount += parseFloat(element.amount);
        }
      });
      return amount;
    },

    monthDescription: function() {
      return this.months[this.month - 1];
    }
  },
  mounted() {
    this.today();
    
    this.retrieveEntries();
    this.checkForEntries();
  },

  beforeDestroy() {
    this.storeEntries();
  },

  data: function() {
    return {
      showgraphs: false,
      showWarning: false,
      current: this.account,
      entrykeys: [],
      entries: [],
      year: 0,
      month: 0,
      months: [
        "Januari",
        "Februari",
        "Maart",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Augustus",
        "September",
        "Oktober",
        "November",
        "December"
      ]
    };
  },
  watch: {},
  methods: {
    toggleGraphs: function() {
      this.showgraphs = !this.showgraphs;
      console.log(this.showgraphs);
    },
    moveUp: function(id) {
      let index = this.entrykeys.indexOf(id);
      if (index - 1 >= 0) {
        let prevIndex = index - 1;
        let prevItem = this.entries[prevIndex];
        let curItem = this.entries[index];
        this.entries[prevIndex] = curItem;
        this.entries[index] = prevItem;
        this.updateEntryKeys();
      }
    },
    moveDown: function(id) {
      let index = this.entrykeys.indexOf(id);

      if (index + 1 < this.entrykeys.length - 1) {
        let nxtIndex = index + 1;
        let curItem = this.entries[index];
        let nxtItem = this.entries[nxtIndex];
        this.entries[nxtIndex] = curItem;
        this.entries[index] = nxtItem;
        this.updateEntryKeys();
      }
    },

    removeEntry: function(entry) {
      let items = this.entries.filter(element => {
        return element.id !== entry.id;
      });
      this.entries = items;
      this.updateEntryKeys();
    },

    getStorageKey: function(year, month, account) {
      return AccountingUtils.getStorageKey(account.id,this.year,this.month);
    },

    retrieveEntries: function() {
      let promise = AccountingUtils.getAccount(this.account.id,this.year,this.month);
      promise.then((data)=>{
        this.entries = data;
        this.showWarning = this.entries.length == 0;
        this.updateEntryKeys();
      }).catch((error)=>{
        console.log(error);
        console.log("error while retrieving account entries");
      });
      
    },
    storeEntries: function() {
      AccountingUtils.setItems(this.account.id,this.year,this.month,this.entries);
    },
    checkForEntries: function() {
      if (this.entries.length == 0) {
        this.showWarning = true;
      }
    },

    applyDefaults: function() {
      let bookingItems = JSON.parse(localStorage.getItem("bookingItems")) || [];
      bookingItems.forEach(element => {
        delete element.id;
        let recurence = 1;
        let periodeStart = moment(element.periodeStart);
        let periodeEnd = null;
        if(element.hasOwnProperty('periodeEnd') && element.periodeEnd !== ""){
            periodeEnd = moment(element.periodeEnd);
        }
        let c = false;
        if((this.startPeriod.isSameOrAfter(periodeStart,"day") && periodeStart.isSameOrBefore(this.endPeriod,"day")) ){
          c = true;
        }

        if(periodeEnd != null){
          c = false;
          if(periodeEnd.isAfter(this.startPeriod,"days")){
              c = true;
          }
        }

        if(c){
        if (element.hasOwnProperty("recurence")) {
          let recurrenceString = element.recurence;
          recurrenceString = recurrenceString.split(" ").join("");
          if (recurrenceString.indexOf("keerper")) {
            let r = element.recurence.split("keerper");
            recurence = parseInt(r[0]);
            if (r[1] == "week") {
              recurence = recurence > 7 ? 7 : recurence;
            }
            if(r[1] == "maand"){
              console.log('stuff');
            }
          }

          if (recurrenceString.startsWith("elke")) {
            recurrenceString = recurrenceString.replace("elke");
            if (recurrenceString.endsWith("maanden")) {
              recurrenceString = recurrenceString.replace("maanden");
            }
            if (recurrenceString.endsWith("weken")) {
              recurrenceString = recurrenceString.replace("weken");
            }
          }
        }
        for (let i = 0; i < recurence; i++) {
          let entry = JSON.parse(JSON.stringify(element));
          this.addEntry(entry);
        }
        }else{
          console.log('skipped');
          console.log(element);
        }
      });

      this.showWarning = false;
    },
    addEntry: function(entry) {
      if (!entry.hasOwnProperty("id") || entry.id == null) {
        entry.id = uuid.v4();
        this.entries.push(entry);
        this.entrykeys.push(entry.id);
        this.storeEntries();
      } else {
        let index = this.entrykeys.indexOf(entry.id);
        this.entries[index] = entry;
        this.storeEntries();
      }
    },

    sortEntries: function() {
      this.entries.sort((a, b) => {
        let ma = moment(a.date);
        let mb = moment(b.date);
        if (ma.isBefore(mb)) {
          return -1;
        } else if (ma.isAfter(mb)) {
          return 1;
        }
        return 0;
      });

      this.updateEntryKeys();
    },
    updateEntryKeys: function() {
      this.entrykeys = [];
      this.entries.forEach(element => {
        if (element != null) {
          this.entrykeys.push(element.id);
        }
      });
    },

    prevMonth: function() {
      this.storeEntries();
      if (this.month - 1 < 1) {
        this.month = 12;
        this.year--;
      } else {
        this.month--;
      }
      
      this.retrieveEntries();
      this.checkForEntries();
    },
    nextMonth: function() {
      this.storeEntries();
      if (this.month + 1 > 12) {
        this.month = 1;
        this.year++;
      } else {
        this.month++;
      }
      
      this.retrieveEntries();
      this.checkForEntries();
    },

    prevYear: function() {
      this.storeEntries();
      this.year--;
      
      this.retrieveEntries();
    },
    nextYear: function() {
      this.storeEntries();
      this.year++;
      
      this.retrieveEntries();
    },
    today: function() {
      this.storeEntries();
      this.year = parseInt(moment().format("YYYY"));
      this.month = parseInt(moment().format("M"));

      
      this.retrieveEntries();
    }
  }
};
</script>

<style>
</style>