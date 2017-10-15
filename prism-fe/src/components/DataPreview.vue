<template >
  <div v-if="result">
    <label>数据预览</label>
    <div>{{ result }}</div>
  </div>
<template>


<script>
  import axios from 'axios'
  import inter from '../utils/interface'

  export default {
    data: () => {
      return {
        result: null
      }
    },
    props: ['preview_options'],
    watch: {
      preview_options: {
        handler (newValue, oldValue) {
          axios.get(inter.chartinstance + '?chart_info=' + JSON.stringify(newValue))
            .then((res) => {
              if (res.data) {
                let head = ''
                let body = ''
                let max = (res.data.length > 10) ? 10 : res.data.length
                for (let count = 0; count < max; count++) {
                  if (count === 0) head += res.data[count].keys().join(' | ') + '<br/>'
                  body += res.data[count].values().join(' | ') + '<br/>'
                }
                this.result = head + body
              } else {
                this.result = '查询有误'
              }
            })
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
