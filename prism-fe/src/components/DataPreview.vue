<template>
  <div v-if="result">
    <label>数据预览</label>
    <div>
      <pre>
        {{ result }}
      </pre>
    </div>
  </div>
</template>


<script>
  import axios from 'axios'
  import inter from '../utils/interface'

  export default {
    data: () => {
      return {
        result: null
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
                  let bo = []
                  for (let key in data[count]) {
                    if (count === 0) {
                      head.push(key)
                    }
                    bo.push(data[count][key])
                  }
                  body.push(bo.join(' | '))
                }
                this.result = head.join(' | ') + '\n' + body.join('\n')
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
