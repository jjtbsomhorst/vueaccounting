<template>
  <tr v-if="isEditMode" @mouseover="onMouseOver()" @mouseleave="onMouseLeave()">
    <td class="collapsing" v-bind:class="{'field error':!validation.date}">
      <div class="ui transparent input">
        <input
          type="date"
          placeholder="date"
          required
          v-model="currentEntry.date"
          v-on:keypress.enter="saveEntry()"
        />
      </div>
    </td>
    <td v-bind:class="{'field error':!validation.description}">
      <div class="ui transparent input search">
        <input
          type="text"
          placeholder="description"
          required
          v-model="currentEntry.description"
          v-on:keypress.enter="saveEntry()"
          v-on:keyup.esc="hideSearch()"
          v-on:keyup="doSearch()"
        />
        <div :class="[showSearch ? 'visible' : 'hidden', 'results transition']">
          <a
            v-for="result in searchResults"
            v-bind:key="result"
            class="result"
            @click="setDescription(result)"
          >{{result}}</a>
        </div>
      </div>
    </td>
    <td v-bind:class="{'field error':!validation.amount, 'right aligned': true}">
      <div class="ui transparent input">
        <input
          type="text"
          placeholder="Ammount"
          required
          v-model="currentEntry.amount"
          v-on:keypress.enter="saveEntry()"
        />
      </div>
    </td>
    <td class="collapsing" v-bind:class="{'field error':!validation.dc}">
      <div class="ui transparent input">
        <input
          class="right aligned"
          type="text"
          placeholder="D/C"
          required
          v-model="currentEntry.dc"
          v-on:keypress.enter="saveEntry()"
        />
      </div>
    </td>
    <td>
      <div class="ui icon buttons">
        <button v-if="saveButton" class="ui icon compact button" @click="saveEntry()">
          <i class="save icon"></i>
        </button>
        <button v-if="removeButton" class="ui icon compact button" @click="removeEntry()">
          <i class="trash alternate outline icon"></i>
        </button>
        <button v-if="copyButton" class="ui icon compact button" @click="copyEntry()">
          <i class="copy outline icon"></i>
        </button>
        <button v-if="closeButton" class="ui icon compact button" @click="toggleEdit(true)">
          <i class="close icon"></i>
        </button>
        <button v-if="showMoveUp" class="ui icon compact button" @click="moveUp()">
          <i class="angle up icon"></i>
        </button>
        <button v-if="showMoveDown" class="ui icon compact button" @click="moveDown()">
          <i class="angle down icon"></i>
        </button>
      </div>
    </td>
  </tr>
  <tr v-else @mouseover="onMouseOver()" @mouseleave="onMouseLeave()">
    <td class="collapsing" @click="toggleEdit()">{{currentEntry.date}}</td>
    <td @click="toggleEdit()">{{currentEntry.description}}</td>
    <!-- v-bind:class="{'field error':!validation.description}"-->
    <td
      v-bind:class="[currentEntry.dc == 'C' ? 'negativeBalance':'positiveBalance','right aligned']"
      @click="toggleEdit()"
    >{{currentEntry.amount | currency}}</td>
    <td class="collapsing" @click="toggleEdit()">{{currentEntry.dc}}</td>
    <td class="collapsing">
      <div class="ui icon buttons">
        <button v-if="showMoveUp" class="ui icon compact button" @click="moveUp()">
          <i class="angle up icon"></i>
        </button>
        <button v-if="showMoveDown" class="ui icon compact button" @click="moveDown()">
          <i class="angle down icon"></i>
        </button>
      </div>
    </td>
  </tr>
</template>

<script>
import formatters from "../mixins/formatters";

export default {
  name: "AccountListEntry",
  props: [
    "index",
    "maxIndex",
    "editable",
    "entry",
    "showSaveButton",
    "showRemoveButton",
    "showCopyButton",
    "enableHover",
    "onclick",
    "currentYear",
    "currentMonth"
  ],
  computed: {
    showSearch: function() {
      return this.searchResults.length > 0;
    },
    showMoveDown: function() {
      return this.index < this.maxIndex && !this.isEditMode;
    },
    showMoveUp: function() {
      return this.index > 0 && !this.isEditMode;
    }
  },
  filters: formatters.get(["currency"]),
  data: function() {
    return {
      searchResults: [],
      currentEntry: this.entry || {},
      saveButton: this.showSaveButton || false,
      removeButton: this.showRemoveButton || false,
      copyButton: this.showCopyButton || false,
      isEditMode: this.editable || false,
      closeButton: false,
      disableOnClick: this.onclick || false,
      validation: {
        description: true,
        amount: true,
        date: true,
        dc: true
      }
    };
  },
  methods: {
    hideSearch: function() {
      this.searchResults = [];
    },
    setDescription: function(result) {
      this.currentEntry.description = result;
      this.searchResults = [];
    },
    doSearch: function() {
      this.searchResults = [];
      let storage = localStorage.getItem("categories");
      storage = storage || "[]";
      let categories = JSON.parse(storage);
      if (
        this.currentEntry.hasOwnProperty("description") &&
        this.currentEntry.description !== null
      ) {
        let searchTerm = this.currentEntry.description.toLowerCase();
        if (searchTerm.length > 0) {
          this.searchResults = categories.filter(element => {
            let subject = element.toLowerCase();
            if (subject.startsWith(searchTerm)) {
              return element;
            }
          });
        }
      }
    },
    moveDown: function() {
      this.$emit("movedown", this.currentEntry.id);
    },
    moveUp: function() {
      this.$emit("moveup", this.currentEntry.id);
    },
    removeEntry: function() {
      console.log("remove entry");
      this.$emit("removeEntry", JSON.parse(JSON.stringify(this.currentEntry)));
      this.currentEntry = {};
    },
    copyEntry: function() {
      console.log("copy entry");
      this.$emit("copyEntry", JSON.parse(JSON.stringify(this.currentEntry)));
    },
    isValidEntry: function() {
      console.log("check if current entry is valid!!");
      // reset it to defaults
      this.validation.description = true;
      this.validation.amount = true;
      this.validation.date = true;
      this.validation.dc = true;

      if (
        !(
          this.currentEntry.hasOwnProperty("description") &&
          this.currentEntry != null &&
          this.currentEntry != ""
        )
      ) {
        this.validation.description = false;
      }
      if (
        !(
          this.currentEntry.hasOwnProperty("amount") &&
          this.currentEntry.amount != null &&
          this.currentEntry.amount != "" &&
          parseFloat(this.currentEntry.amount) > 0
        )
      ) {
        this.validation.amount = false;
      }

      this.validation.date =
        this.currentEntry.hasOwnProperty("date") &&
        this.currentEntry.date != null &&
        this.currentEntry.date != "";

      this.validation.dc =
        this.currentEntry.dc == "D" || this.currentEntry.dc == "C";

      return (
        this.validation.description &&
        this.validation.date &&
        this.validation.amount &&
        this.validation.dc
      );
    },
    saveEntry: function() {
      if (this.isValidEntry()) {
        let entry = JSON.parse(JSON.stringify(this.currentEntry));
        this.$emit("saveEntry", entry);
        if (this.entry != null && this.entry.hasOwnProperty("id")) {
          this.currentEntry = this.entry;
        } else {
          this.currentEntry = {};
        }
        this.validation = {
          description: true,
          amount: true,
          date: true,
          dc: true
        };
        this.toggleEdit(true);
      } else {
        console.log("entry is not valid..");
      }
    },
    onMouseOver: function() {},
    onMouseLeave: function() {},
    toggleEdit: function(force) {
      if (event.currentTarget.tagName == "TD" || force) {
        if (this.onclick) {
          console.log("toggle edit");
          this.isEditMode = !this.isEditMode;
          this.closeButton = this.isEditMode;
          this.saveButton = this.isEditMode;
        }
      }
    }
  }
};
</script>

<style>
.negativeBalance {
  color: #9f3a38 !important;
}
.positiveBalance {
  color: green;
}
</style>