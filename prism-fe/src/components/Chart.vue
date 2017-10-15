<template>
  <div ref="chart">

  </div>
</template>

<script>
  import axios from 'axios'
  import inter from '../utils/interface'

  import echarts from 'echarts'
  export default {
    data () {
      return {
        chartObj: null
      }
    },
    props: ['chart_options'],
    watch: {
      chart_options: {
        handler (newValue, oldValue) {
          if (!this.chartObj) {
            this.chartObj = echarts.init(this.$refs.chart)
          }
          let userData = newValue.xAxis.data.split(' | ')
          let dbLink = userData[0]
          let dataBase = userData[1]
          let table = userData[2]
          let categoryCol, valueCol
          if (newValue.xAxis.type === 'category') {
            categoryCol = userData[3]
            valueCol = newValue.yAxis.data.split(' | ')[3]
          } else {
            categoryCol = newValue.yAxis.data.split(' | ')[3]
            valueCol = userData[3]
          }

          let chartInfo = {
            'expressions': [categoryCol, valueCol],
            'from': [dataBase + '.' + table],
            'group': [categoryCol]
          }

          axios.get(inter.chartinstance + '?db_link_id=' + dbLink + '&chart_info=' + JSON.stringify(chartInfo))
            .then((res) => {
              console.log(res)
            })
          let options = {
            title: {
              text: newValue.title || ''
            },
            xAxis: {
              type: newValue.xAxis.type,
              name: newValue.xAxis.name || null
            },
            yAxis: {
              type: newValue.yAxis.type,
              name: newValue.yAxis.name || null
            },
            series: [{
              type: newValue.type,
              data: []
            }]
          }
          this.chartObj.setOption(options)
        },
        deep: true
      }
    }
  }
</script>

<style scoped>
  div{
    width: 100%;
    height: 100%;
  }
</style>
