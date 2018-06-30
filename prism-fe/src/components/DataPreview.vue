<template>
  <div v-if="result">
    <h1>数据预览</h1>
    <div>
      <div v-if="!re">
        查询有误
      </div>
      <div v-if="re">
        <Table stripe :columns="result['header']" :data="result['data']"></Table>
      </div>
    </div>
  </div>
</template>


<script>
  import axios from 'axios'
  import inter from '../utils/interface'

  export default {
    data: () => {
      return {
        result: null,
        re: false
      }
    },
    props: ['previewOption'],
    watch: {
      previewOption: {
        handler (newValue, oldValue) {
          axios.get(inter.chartinstance + '?chart_info=' + JSON.stringify(newValue))
            .then((res) => {
              let data = res.data.data
              if (data) {
                let head = []
                let body = []
                let max = (data.length > 10) ? 10 : data.length
                for (let count = 0; count < max; count++) {
                  for (let key in data[count]) {
                    if (count === 0) {
                      head.push({title: key, key: key})
                    }
                  }
                  body.push(data[count])
                }
                this.result = {header: head, data: body}
                this.re = true
              } else {
                this.result = '查询有误'
                this.re = false
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
  h1{
    text-align: center;
  }
</style>
