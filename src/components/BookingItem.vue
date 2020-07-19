<template>
  <tr>
    <td @click="toggleEdit()">
      <div v-if="isEditMode" class="ui transparent input" id="description">
        <input type="text" v-model="currentItem.description" required v-on:keyup.enter="saveEntry()" />
      </div>
      <div v-if="!isEditMode">{{currentItem.description}}</div>
    </td>
    <td @click="toggleEdit()" id="amount">
      <div v-if="isEditMode" class="ui transparent input">
        <input type="text" v-model="currentItem.amount" required v-on:keyup.enter="saveEntry()" />
      </div>
      <div v-if="!isEditMode">{{currentItem.amount | currency}}</div>
    </td>
    <td @click="toggleEdit()" id="dc">
      <div v-if="isEditMode" class="ui transparent input">
        <input type="text" v-model="currentItem.dc" required v-on:keyup.enter="saveEntry()" />
      </div>
      <div v-if="!isEditMode">{{currentItem.dc}}</div>
    </td>
    <td @click="toggleEdit()" id="periodestart">
      <div v-if="isEditMode" class="ui transparent input">
        <input type="date" v-model="currentItem.periodeStart" required v-on:keyup.enter="saveEntry()" />
      </div>
      <div v-if="!isEditMode">{{currentItem.periodeStart}}</div>
    </td>
    <td @click="toggleEdit()" id="periodeend">
      <div v-if="isEditMode" class="ui transparent input">
        <input type="date" v-model="currentItem.periodeEnd" required v-on:keyup.enter="saveEntry()" />
      </div>
      <div v-if="!isEditMode">{{currentItem.periodeEnd}}</div>
    </td>
    <td @click="toggleEdit()" id="recurence">
        <div v-if="isEditMode" class="ui transparent input">
        <input type="text" v-model="currentItem.recurence" required v-on:keyup.enter="saveEntry()" />
      </div>
        <div v-if="!isEditMode">
        {{currentItem.recurence}}</div></td>
    <td>
        <div class="ui icon buttons">
      <button class="ui compact icon button" @click="$emit('remove',currentItem)">
        <i class="trash alternate outline icon"></i>
      </button>
       <button v-if="isEditMode" class="ui icon compact button" @click="toggleEdit(true)">
          <i class="close icon"></i>
        </button>
        <button v-if="isEditMode" class="ui icon compact button" @click="saveEntry()">
          <i class="save icon"></i>
        </button>
        </div>
    </td>
  </tr>
</template>

<script>
import formatters from "../mixins/formatters";
export default {
  name: "currentItem",
  props: ["entry"],
  filters: formatters.get(["currency"]),
  data: function() {
    return {
      currentItem: this.entry || {},
      isEditMode: false
    };
  },
  methods: {
      saveEntry: function(){
          if(!((this.currentItem.description == "" || this.currentItem.description == null) 
                && (this.currentItem.amount == "" || this.currentItem.amount == null)
                && (this.currentItem.dc != "D" || this.currentItem.dc != "C"))
            ){
                this.$emit('save',this.currentItem)
                this.toggleEdit(true);
            }else{
                console.log('niet valid!!');
            }
      },
    toggleEdit: function(force) {
      if (event.target.tagName == "DIV" || force) {
        
          console.log("toggle edit");
          this.isEditMode = !this.isEditMode;
        
      }
    }
  }
};
</script>

<style>
</style>