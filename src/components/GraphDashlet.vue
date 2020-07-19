<template>
  <div ref="chartdiv"></div>
</template>

<script>
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
export default {
  name: "GraphDashlet",
  props: ["title", "data","type"],
  data: function(){
    return{
        chartData: [],
        chart: null
    };
  },
  beforeDestroy() {
    if (this.chart) {
      this.chart.dispose();
    }
  },
  watch: {
    data: function(n,o){
      if(n!==o && n !== null && this.chart != null){
      if(n!== null){
        this.chart.data = this.massageData(n);
      }
      }
    }
  },
  methods: {
    massageData: function(entries){
        let data = new Map();
        entries.forEach((element)=>{
          if(data.get(element.description) === undefined){
            data.set(element.description,parseFloat(element.amount));
          }else{
            let tmpValue = data.get(element.description);
            tmpValue += parseFloat(element.amount);
            data.set(element.description,tmpValue);
          }
        });
        let chartData = [];
        for (var [key, value] of data) {
          chartData.push({
            "description":key,
            "amount": value
          });
        }
        return chartData;
    }
  },
  mounted() {
    
    switch(this.type){
      case 'pie':
        this.chart = am4core.create(this.$refs.chartdiv,am4charts.PieChart);
        var pieSeries = this.chart.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "amount";
        pieSeries.dataFields.category = "description"
        pieSeries.labels.template.disabled = true;
        pieSeries.ticks.template.disabled = true;
        break;
      case 'bar': 
      default:
        
    }
    
  }
};
</script>

<style>
</style>