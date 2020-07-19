<template>
  <div>
    <div class="ui pointing menu">
      <a
        v-on:click="activeMenuItem = menuItem.id"
        v-for="menuItem in menuItems"
        v-bind:key="menuItem.id"
        v-bind:class="activeMenuItem === menuItem.id ? 'item active' : 'item'"
      >{{menuItem.description}}</a>
    </div>

    <div class="ui segment" v-if="activeMenuItem === '1'">
      <h1>General Application Settings</h1>

      <p>Hier komen generieke instellingen..</p>
    </div>
    <div class="ui segment" v-if="activeMenuItem === '2'">
      <h1 class="ui header">Standaard regels</h1>

      <p>
        Hieronder kun je de standaard posten definieren. Deze kunnen per 'periode' worden geimporteerd. Standaard posten hebben
        bedrag, een omschrijving en start en eind datum. Als laatste is het mogelijk om er een herhalende post van te maken.
        (elke week , elke maand, elk jaar.) en het aantal keren dat herhaald word in de huidige periode
      </p>
      <p>
        Bij het importerne van boekingsposten binnen een bepaalde periode zal er gekeken worden of dat de huidige periode ( maand )
        nog binnen de opgegeven start/eind datum past. Zo ja dan zal deze worden toegevoegd en anders overgeslagen.
      </p>

      <table class="ui celled table">
        <thead>
          <tr>
            <th>Omschrijving</th>
            <th>Bedrag</th>
            <th>D/C</th>
            <th>periode start</th>
            <th>periode eind</th>
            <th>herhaling</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div class="ui transparent input">
                <input
                  type="text"
                  v-model="currentItem.description"
                  required
                  v-on:keyup.enter="addEntry"
                />
              </div>
            </td>
            <td>
              <div class="ui transparent input">
                <input
                  type="text"
                  v-model="currentItem.amount"
                  required
                  v-on:keyup.enter="addEntry"
                />
              </div>
            </td>
            <td>
              <div class="ui transparent input">
                <input type="text" v-model="currentItem.dc" required v-on:keyup.enter="addEntry" />
              </div>
            </td>
            <td>
              <div class="ui transparent input">
                <input
                  type="date"
                  v-model="currentItem.periodeStart"
                  required
                  v-on:keyup.enter="addEntry"
                />
              </div>
            </td>
            <td>
              <div class="ui transparent input">
                <input
                  type="date"
                  v-model="currentItem.periodeEnd"
                  required
                  v-on:keyup.enter="addEntry"
                />
              </div>
            </td>
            <td>
              <div class="ui transparent input">
                <input
                  type="text"
                  v-model="currentItem.recurence"
                  required
                  v-on:keyup.enter="addEntry"
                />
              </div>
            </td>
            <td></td>
          </tr>
          <booking-item
            v-for="entry in items"
            v-bind:key="entry.id"
            v-bind:entry="entry"
            v-on:remove="removeEntry"
            v-on:save="addEntry"
          />
        </tbody>
      </table>
    </div>
    <div class="ui segment" v-if="activeMenuItem === '3'">
      <div class="ui action input">
        <input type="text" placeholder="Categorie toevoegen" v-model="category" />
        <button class="ui icon button" @click="addCategory(category)">
          <i class="save outline icon"></i>
        </button>
      </div>
      <div class="ui list">
        <div class="item" v-for="c in categories" v-bind:key="c">{{c}}</div>
      </div>
    </div>
  </div>
</template>

<script>
import { uuid } from "vue-uuid";
import bookingItem from "./BookingItem";
import formatters from "../mixins/formatters";
export default {
  name: "ApplicationSettings",
  props: {},
  filters: formatters.get(["currency"]),
  components: {
    bookingItem
  },
  mounted: function() {
    this.items = JSON.parse(localStorage.getItem("bookingItems")) || [];

    this.items.forEach(element => {
      this.keys.push(element.id);
    });

    this.categories = JSON.parse(localStorage.getItem("categories")) || [];
  },
  beforeDestroy: function() {
    this.storeEntries();
    this.storeCategories();
  },
  data: function() {
    return {
      category: "",
      items: [],
      keys: [],
      categories: [],
      currentItem: {},
      activeMenuItem: "1",
      menuItems: [
        {
          id: "1",
          description: "General",
          view: "generalsettings"
        },
        {
          id: "2",
          description: "Standaard regels",
          view: "bookingItems"
        },
        {
          id: "3",
          description: "Boekingsposten",
          view: "defaultPosts"
        }
      ]
    };
  },
  methods: {
    addCategory: function(c) {
      if (this.categories.indexOf(c) == -1) {
        this.categories.push(c);
        this.storeCategories();
      }
    },
    removeEntry: function(entry) {
      let filteredItems = this.items.filter(element => {
        return element.id !== entry.id;
      });
      this.items = filteredItems;
      this.storeEntries();
    },
    addEntry: function(entry) {
      if (entry.hasOwnProperty("id") && entry.id != "" && entry.id != null) {
        let index = this.keys.indexOf(entry.id);
        if (index >= 0) {
          this.items[index] = JSON.parse(JSON.stringify(entry));
        }
      } else {
        entry.id = uuid.v4();
        this.items.push(entry);
      }
      this.storeEntries();
    },
    storeEntries: function() {
      localStorage.setItem("bookingItems", JSON.stringify(this.items));
    },
    storeCategories: function() {
      localStorage.setItem("categories", JSON.stringify(this.categories));
    }
  }
};
</script>

<style>
</style>